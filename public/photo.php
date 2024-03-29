<?php require_once("../includes/initialize.php");?>
<?php 
if (empty($_GET["id"])) {
	$session->message("No photograph ID was provided.");
	redirect_to("index.php");
}

$photo = Photograph::find_by_id($_GET["id"]);
if (!$photo) {
	$session->message("The photo couldn't be located.");
	redirect_to("index.php");
}

if (isset($_POST["submit"])) {
	$author = trim($_POST["author"]);
	$body = trim($_POST["body"]);
	$new_comment = Comment::make($photo->id ,$author, $body);
	if ($new_comment && $new_comment->save()) {
		// comment saved
		// No message needed; seeing the comment is proof enough

		// send email
		// $new_comment->send_notification();

		// Important! You could just let the page render from here
		// But then if the page is reloaded, the form will try
		// to resubmit the comment. So redirect instead making it a get request.
		redirect_to("photo.php?id={$photo->id}");
	} else {
		// Failed
		$message = "There was an error that prevented the comment from being saved.";
	}
} else {
	$author = "";
	$body = "";
}

$comments = $photo->comments();
 ?>
<?php include_layout_template("header.php"); ?>
<a href="index.php">&laquo; Back</a> <br />
<br />
<h2>Photos</h2>

	<div>
		<figure>
		<img src="<?php echo $photo->image_path(); ?>" style="box-shadow: none;" width="70%" height="50%" />
		<figcaption><?php echo $photo->caption; ?></figcaption>
		</figure>

	</div>	

<!-- list comments -->
<h3>Comments</h3>
<div id="comments" style="margin-bottom: 2em;">
	<?php foreach($comments as $comment): ?>
		<div class="author">
			<?php echo htmlentities($comment->author); ?> 
			<span class="meta-info" style="font-size: 0.6em;">
			<?php echo datetime_to_text($comment->created); ?>
		</span>
		</div>
		<div class="body">
			<?php echo strip_tags($comment->body,"<strong><em><p>"); ?>
		</div>
		<br />
		
	<?php endforeach; ?>
	<?php if(empty($comments)) { echo "No Comments."; } ?>
</div>


<div id="comment-form">
	<h3>New Comment</h3>
	<?php echo output_message($message); ?>
	<form action ="photo.php?id=<?php echo $photo->id; ?>" method="post">
		<table>
			<tr>
				<td>Your name:</td>
				<td><input type="text" name="author" value="<?php echo $author; ?>" /></td>
			</tr>
			<tr>
				<td>Your comment:</td>
				<td><textarea name="body" cols="40" rows="8"><?php echo $body; ?></textarea></td>
			</tr>
			<tr>
				<td> &nbsp;</td>
				<td><input type="submit" name="submit" value="Submit Comment" /></td>
			</tr>
		</table>
		
	</form>
</div>

<?php include_layout_template("footer.php"); ?>