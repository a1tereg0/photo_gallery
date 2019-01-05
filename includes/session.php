<?php 
// A class to help with Sessions
// In out case, primarily to manage logging users in and out

// keep in mind when working with sessions that it is generally
// inadvisable to store DB-related objects in sessions

class Session{

private $logged_in=FALSE;
public $user_id; 
public $message;

function __construct(){
	session_start();
	$this->check_message();
	$this->check_login();
	if($this->logged_in){
		// actions to take right away if the user is logged in
	}else{
		// action to take right away if the user is not logged in
	}
}

public function is_logged_in(){
	return $this->logged_in;
}

public function login($user){
		// database should find user based on username/password

if($user){
	$this->user_id = $_SESSION["user_id"] = $user->id;
	$this->logged_in = TRUE;
}
}

public function logout(){
	unset($_SESSION["user_id"]);
	unset($this->user_id);
	$this->logged_id = FALSE;
}

public function message($msg=""){
	if (!empty($msg)) {
		// then this is "set message"
		// make sure you understand why $this->message = $msg wouldn't work
		$_SESSION["message"] = $msg;
	} else {
		return $this->message;
	}
}

private function check_login(){
	if(isset($_SESSION["user_id"])){
		$this->user_id = $_SESSION["user_id"];
		$this->logged_in = TRUE;
	}else{
		unset($this->user_id);
		$this->logged_in = FALSE;
	}

}

private function check_message(){
	// Is there a message stored in the session ?
	if (isset($_SESSION["message"])) {
		// Add it as an attribute and erase the stored version
		$this->message = $_SESSION["message"];
		unset($_SESSION["message"]);
	} else {
		$this->message = "";
	}


}

}

$session = new Session();
$message = $session->message();
 ?>