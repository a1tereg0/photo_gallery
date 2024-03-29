<?php 
require_once("../../includes/initialize.php");
if(!$session->is_logged_in()) { redirect_to("login.php"); } 
?>
<?php 
if (empty($_GET["id"])) {
	$session->message("No photograph ID was provided.");
	redirect_to("index.php");
}

$photo = photograph::find_by_id($_GET["id"]);
if (!$photo) {
	$session->message("The photo couldn't be located.");
	redirect_to("index.php");
}

$comments = $photo->comments();

 ?>
<?php include_layout_template("admin_header.php"); ?>
<a href="list_photos.php">&laquo; Back</a> <br />
<br />
<h2>Comments on <?php echo $photo->filename; ?></h2>
<?php echo output_message($message); ?>

<div id="comments" style="margin-bottom: 2em;">
	<?php foreach($comments as $comment): ?>
		<div class="author">
			<?php echo htmlentities($comment->author); ?> wrote:
		</div>
		<div class="body">
			<?php echo strip_tags($comment->body,"<strong><em><p>"); ?>
		</div>
		<div class="meta-info" style="font-size: 0.6em;">
			<?php echo datetime_to_text($comment->created); ?>
		</div>
		<div class="actions" style="font-size: 0.8em;" >
			<a href="delete_comment.php?id=<?php echo $comment->id; ?>">Delete</a>
		</div>
	<?php endforeach; ?>
	<?php if(empty($comments)) { echo "No Comments."; } ?>
</div>


<?php include_layout_template("admin_footer.php"); ?>