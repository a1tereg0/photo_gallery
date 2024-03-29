<?php require_once("../includes/initialize.php");?>
<?php 

// 1. the current page number ($current_page)
$page = !empty($_GET["page"]) ? $_GET["page"] : 1;
// 2. records per page ($per_page)
$per_page = 10;
// 3. total record count ($total_count)
$total_count = Photograph::count_all();

// Find all photos
// user pagination instead
//$photos = Photograph::find_all();

$pagination = new Pagination($page, $per_page, $total_count);

// Instead of finding all records, just find the records for this page

$sql = "SELECT * FROM photographs ";
$sql .= "LIMIT {$per_page} ";
$sql .= "OFFSET {$pagination->offset()}"; 
$photos = Photograph::find_by_sql($sql);

// Need to add ?page=$page to all links we want to 
// maintain the current page (or store page in the $session)

?>
<?php include_layout_template("header.php"); ?>
<h2>Photos</h2>
<?php foreach($photos as $photo): ?>
	<div>
		<a href="photo.php?id=<?php echo $photo->id; ?>">
			<figure class="list-photos">
			<img src="<?php echo $photo->image_path(); ?>" width="300" height="225"/></a>
		<figcaption><?php echo $photo->caption; ?></figcaption>
		</figure>
	</div>	
<?php endforeach; ?>

<div id="pagination" style="clear:both;" >
	<?php 
	if($pagination->total_pages() > 1){

		if ($pagination->has_previous_page()) {
			echo "<a href=\"index.php?page=";
			echo $pagination->previous_page();
			echo "\">&laquo; Previous</a> ";
		}

		for($i=1;$i<=$pagination->total_pages();$i++){
			if ($i == $page) {
				echo "<span class=\"selected\">{$i}</span>";
			} else {
			echo " <a href =\"index.php?page={$i}\">{$i}</a> ";
			}
		}

		if ($pagination->has_next_page()) {
			echo "<a href=\"index.php?page=";
			echo $pagination->next_page();
			echo "\">Next &raquo;</a> ";
		}
	}

	 ?>
</div>

<?php include_layout_template("footer.php"); ?>