<?php 
// If it's going to need the database, then it's
// probably smart to require it before we start
require_once(LIB_PATH.DS."database.php");
require_once(LIB_PATH.DS."functions.php");
require_once(LIB_PATH.DS."mailer.php");

class Comment extends DatabaseObject {

protected static $table_name = "comments";
protected static $db_fields = array("id", "photograph_id", "created", "author", "body");

public $id;
public $photograph_id;
public $created;
public $author;
public $body;

public static function make($photo_id, $author="Anonymous", $body=""){
	if(!empty($photo_id) && !empty($author) && !empty($body)){
	$comment = new Comment();
	$comment->photograph_id = (int)$photo_id;
	$comment->created = strftime("%Y-%m-%d %H:%M:%S", time());
	$comment->author = $author;
	$comment->body = $body;
	return $comment;
} else {
	return false;
}
}

public static function find_comments_on($photo_id=0){
	global $database;
	$sql = "SELECT * FROM ".self::$table_name;
	$sql .= " WHERE photograph_id=".$database->escape_value($photo_id);
	$sql .= " ORDER BY created ASC";
	return self::find_by_sql($sql);
}

public function send_notification(){
// Set up required parameters
$created = datetime_to_text($this->created);	
$smtp = array(
'debug' => 2,
'host' => 'smtp.gmail.com',
'auth' => true,
'username' => SMTP_USER,
'password' => SMTP_PASS,
'secure' => 'ssl',
'port' => 465
);	

$to = array(
    array(
    	'name'  => "Prasant Dahal",
    	'email' => "prasantdahal@outlook.com"
    ),
);

$subject = "New comment on photo gallery";
$html =<<<EMAILBODY
<h2>A new comment has been received in the Photo Gallery.</h2>
<h3>At {$created}, {$this->author} wrote:</h3>
<p>{$this->body}</p>
EMAILBODY;

$from = array(
'name' => 'Prashant Dahal',
'email' => 'prashantdah41@gmail.com'
);

// Create a new instance and send the email
$mailer = new Mailer(true, $smtp);
$mailer->mail($to, $subject, $html, $from);


}


}


 ?>