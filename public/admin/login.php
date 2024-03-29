<?php 
require_once("../../includes/initialize.php");


if($session->is_logged_in()){
	redirect_to("index.php");
}

// Remember to give your form's submit tag a name = "submit" attribute!
if(isset($_POST["submit"])){// From has been submitted
	$username = trim($_POST["username"]);
	$password = trim($_POST["password"]);
	
 // Check database to see if username/password exist
	$found_user = User::authenticate($username, $password);

	if($found_user){
		$session->login($found_user);
		log_action("Login","{$found_user->username} logged in.");
		redirect_to("index.php");

	} else{
 	// username/password combo was not found in the database
		$message = "Username/Password combination incorrect.";
	} 

}else{// Form has not been submitted
	$username = "";
	$password = "";
	$message = "";

} 

?>

<?php include_layout_template("admin_header.php") ?>
<h2>Staff Login</h2>
<?php echo output_message($message); ?>

<form action = "login.php" method = "post" >
	<table>
		<tr>
			<td>Username: </td>
			<td><input type="text" name="username" maxlength="30" value = "<?php echo htmlentities($username) ?>" /> </td>
		</tr>
		<tr>
			<td>Password: </td>
			<td><input type="password" name="password" maxlength="30" value = "<?php echo htmlentities($password) ?>" /> </td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit" value = "Login" /></td>
		</tr>
	</table>
</form>

<?php include_layout_template("admin_footer.php") ?>