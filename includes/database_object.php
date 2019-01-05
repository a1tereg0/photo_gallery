<?php 
// If it's going to need the database, the it's 
// probably smart to require it before we start
require_once(LIB_PATH.DS."database.php");


class DatabaseObject{

	public static function find_all(){
		return static::find_by_sql("SELECT * FROM ".static::$table_name);
	}

	public static function find_by_id($id=0){
		global $database;
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE id = ".$database->escape_value($id)." LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public static function find_by_sql($sql = ""){
		global $database;
		$result_set = $database->query($sql);
		$object_array = array();
		while ($row = $database->fetch_array($result_set)){
			$object_array[] = static::instantiate($row);
		}
		return $object_array;
	}

	public static function count_all(){
		global $database;
		$sql = "SELECT COUNT(*) FROM ".static::$table_name;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}





	private static function instantiate($record){
		// Could check if $record exists and is an array

		// $class_name = get_called_class();
		// $object = new $class_name; or
		$object = new static;

		// Simple, long form approach
		// $this->id = $record["id"];
		// $this->username = $record["username"];
		// $this->password = $record["password"];
		// $this->first_name = $record["first_name"];
		// $this->last_name = $record["last_name"];

		foreach ($record as $attribute => $value) {
			if($object->has_attribute($attribute)){
				$object->$attribute = $value;
			}
		}
		return $object;

	}

	private function has_attribute($attribute){
		// get object vars return an associative array with all the attributes
		// (incl. private ones!) as the keys and the current values as value
		$object_vars = $this->attributes();
		// We don't care about the value, we just wan't to know if the key exists
		// Will return true or false
		return array_key_exists($attribute, $object_vars);
	}

	protected function attributes(){
		// return an array of attribute keys and their values
		// return get_object_vars($this); this is not a good practice
		$attributes = array();
		foreach (static::$db_fields as $field) {
			if (property_exists($this, $field)) {
				$attributes[$field] = $this->$field; //dynamic variable

			}
		}
		return $attributes;
	}

	protected function sanitized_attributes(){
		global $database;
		$clean_attributes = array();
		// sanitize the value before submitting
		// Note: doesnot alter the actual value of each attributes
		foreach ($this->attributes() as $key => $value) {
			$clean_attributes[$key] = $database->escape_value($value);

		}
		return $clean_attributes;
	}

	public function save(){
		// A new record won't have an id yet.
		return isset($this->id) ? $this->update() : $this->create();
	}


	public function create(){
		global $database;
		// Don't forget your SQL syntax and good habits:
		// - INSERT INTO TABLE (key, key) VALUES ('value', 'value')
		// - single-quotes around all values
		// - escape all values to prevent SQL injection
		$attributes = $this->sanitized_attributes();
		unset($attributes["id"]);
		$sql = "INSERT INTO ".static::$table_name." (";
		$sql .= join(", ",array_keys($attributes));
		$sql .= ") VALUES ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";
		
		if ($database->query($sql)) {
			$this->id = $database->insert_id();
			return true; 
		} else {
			return false;
		}
	}

	public function update(){
		global $database;
		// Don't forget your SQL syntax and good habits:
		// - UPDATE TABLE SET key='value', key='value' WHERE condition
		// - single-quotes around all values
		// - escape all values to prevent SQL injection
		$attributes = $this->sanitized_attributes();
		$attribute_pairs = array();
		foreach ($attributes as $key => $value) {
			$attribute_pairs[] = "{$key}='{$value}'"; 
		}
		$sql = "UPDATE ".static::$table_name." SET ";
		$sql .= join(", ",$attribute_pairs);
		$sql .= " WHERE id =".$database->escape_value($this->id);
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;

	}

	public function delete(){
		// Don't forget your SQL syntax and good habits:
		// - DELETE FROM table WHERE condition LIMIT 1
		// - escape all values to prevent SQL injection
		// - use LIMIT 1
		global $database;
		$sql = "DELETE FROM ".static::$table_name;
		$sql .= " WHERE id=".$database->escape_value($this->id);
		$sql .= " LIMIT 1";
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;

	}



}


 ?>