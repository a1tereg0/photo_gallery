<?php 
require_once("../../includes/initialize.php");
if(!$session->is_logged_in()) { redirect_to("login.php"); } 
?>
<?php 
$max_file_size = 1048576;

if (isset($_POST["submit"])) {
	$photo = new Photograph();
	$photo->caption = $_POST["caption"];
	$photo->attach_file($_FILES["file_upload"]);
	if ($photo->save()) {
		// Success
		$session->message("Photograph uploaded successfully");
		log_action("New photo uploaded", "by User ID {$session->user_id}");
		redirect_to("list_photos.php");
	} else {
		// Failure
		$message = join("<br />", $photo->errors);
	}

}

 ?>
<?php include_layout_template("admin_header.php"); ?>

<h2>Photo Upload</h2>
<?php echo output_message($message); ?>
<form action = "photo_upload.php" enctype="multipart/form-data" method="POST" >
	<input type="hidden" name="MAX_FILE_SIZE" value = "<?php echo $max_file_size; ?>" />
	<p><input type="file" name="file_upload"></p>
	<p>Caption: <input type="text" name="caption" value = "" /></p>
	<input type="submit" name="submit" value="Upload" />
	
</form>
<br />
<a href="list_photos.php?page=<?php echo $_GET["page"]; ?>">Cancel</a>

<?php include_layout_template("admin_footer.php"); ?>