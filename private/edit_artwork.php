<?php
	require_once("../includes/sessions.php");
	require_once("../includes/connection.php");
	require_once("../includes/functions.php");
	require_once("../includes/private_layout_functions.php");
	
	confirm_logged_in();
	confirm_top_level();
	
	$return = 0;
	$return = $_GET['return'];
	
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
	} else {
		header("Location: add_artwork.php");
		exit;
	}
	
	//sees if this has just been submitted to itself
	if (isset($_POST['submit'])){
		
		$title = $_POST['artwork_title'];
		$artist = $_POST['artwork_artist'];
		$date = $_POST['artwork_date'];
		$medium = $_POST['artwork_medium'];
		$dims = $_POST['artwork_dims'];
		$price = $_POST['artwork_price'];
		$level = $_POST['artwork_level'];
		$video = $_POST['artwork_video'];
		//print_r($_FILES);
		
		if (empty($_POST['artwork_title'])) {
			$errormessage .= "Please enter an artwork title.";
		}
		
		if (empty($errormessage)) {
			
			
			if($_FILES['file_upload']['name'] == "") {
				// No file was selected for upload
				} else {
				$imageuploaded = true;
				$tmp_file = $_FILES['file_upload']['tmp_name'];
				$target_file = prep_string_for_filename($_POST['artwork_title'] . $_POST['artwork_date'] . time() );
				$upload_dir = "../assets/img";
				if(move_uploaded_file($tmp_file, $upload_dir . '/' . $target_file)) {
					$message .= "Image Upload Was Successful. ";
				} else {
					//
				}
				$final_path = "http://www.miekemarple.com/nightgallery/assets/img" . "/" . $target_file;
			}
			
			
			//DO THE QUERY
			$query = "UPDATE ng_artworks ";
			$query .= "SET ";
			$query .= "artwork_title = '{$title}', ";
			$query .= "artwork_artist_id = '{$artist}', ";
			$query .= "artwork_date = '$date', ";
			$query .= "artwork_medium = '$medium', ";
			$query .= "artwork_dimensions = '$dims', ";
			$query .= "artwork_video_embed = '$video', ";
			$query .= "artwork_price = '$price', ";
			if($imageuploaded) { 
				$query .= "artwork_image_url = '$final_path', "; 
			}
			$query .= "artwork_permission_level = '$level' ";
			$query .= "WHERE artwork_id = {$id}";
			 
			$result = mysql_query($query, $mysamconnec);
			
			if (mysql_affected_rows()==1){
				$message .= "Update was successful";
			} else {
				$message .= "Nothing was updated.";
			}
		}	
	} else {

	}
	
?>


<?php
	
		
	$query = "SELECT * FROM ng_artworks WHERE artwork_id =";
	$query .= $id;
	$query .= " LIMIT 1";
	$contentset = mysql_query($query, $mysamconnec);
	testquery($contentset);
	$artwork = mysql_fetch_array($contentset);
	
	//gets which artist this artwork is by so it can preselect in the "select an artist" drop-down.
	$query = "SELECT * FROM ng_artists WHERE artist_id = ";
	$query .= $artwork['artwork_artist_id'];			
	$artworkartistresults = mysql_query($query, $mysamconnec);
	testquery($artworkartistresults);
	$thisartist = mysql_fetch_array($artworkartistresults);
	
?>

<html>
	<head>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans+Mono' rel='stylesheet' type='text/css'>
		<title>NIGHT GALLERY | Edit Artwork</title>
		<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/reset/reset-min.css">
		      <link href="styles/style.css" rel="stylesheet" type="text/css">
		      
	</head>
	<body class="crud1 admin manageartworks">
	
	<div class="wid">
		
		
		
		
			<div class="header">
				<div id="userbar" ><p class="user<?php echo $level;?>"><?php echo $_SESSION['username']; ?>
				
					<?php require_once("../includes/userbar.php");?>
				
				</div>
				
				<?php build_admin_nav(); ?>
				
			</div>
	
	
	
			<div class="content">
		
		
		
		<div id="pagehead">
		<h3>EDIT <em>"<?php echo strtoupper($artwork['artwork_title']); ?>"</em></h3>
		</div>
		
		<div id ="addartwork">
		<div id ="artworkform">
		
		
		<?php if(!empty($errormessage)){ echo "<p>" . $errormessage . "</p>"; } ?>
		<?php if(!empty($message)){ echo "<p>" . $message . "</p>"; } ?>
		
		<form action="edit_artwork.php<?php echo "?id=" . $id ?> " enctype="multipart/form-data" method="post">
		
		<input type="text" placeholder="Title" name="artwork_title" value="<?php echo $artwork['artwork_title']; ?>" />
		
		<input type="text" placeholder="Date" name="artwork_date" value="<?php echo $artwork['artwork_date']; ?>" />
		
		<select name="artwork_artist">
			<option value="0">Select an artist...
			<? 	$artistsquery = "SELECT * FROM ng_artists ORDER BY artist_last_name ASC";
				$artistscontentset = mysql_query($artistsquery, $mysamconnec);
				testquery($contentset);
				
				while($artist = mysql_fetch_array($artistscontentset)){
				
						
					echo "<option value=" .  $artist['artist_id'] . " ";
						if ($thisartist['artist_id'] == $artist['artist_id']) {
							echo "selected = \"selected\"";
						}
					echo ">";
					echo $artist['artist_last_name'] . ", " . $artist['artist_first_name'];
					echo "</option>";
				}
				
			?>
		</select>
		
		
		
		<input type="text" placeholder="Medium" name="artwork_medium" value="<?php echo $artwork['artwork_medium']; ?>" />
		<input type="text" placeholder="Dimensions/Duration" name="artwork_dims" value="<?php echo $artwork['artwork_dimensions']; ?>" />
		<input type="text" placeholder="Price" name="artwork_price" value="<?php echo $artwork['artwork_price']; ?>" />
		
		<select name="artwork_level">
			<option value="0" <?php if ($artwork['artwork_permission_level'] == 0) {
							echo "selected = \"selected\"";
						} ?>
						>Public
			<option value="1" <?php if ($artwork['artwork_permission_level'] == 1) {
							echo "selected = \"selected\"";
						} ?>
						>Private: Basic
			<option value="2" <?php if ($artwork['artwork_permission_level'] == 2) {
							echo "selected = \"selected\"";
						} ?>
						>Private: Elite
			<option value="3" <?php if ($artwork['artwork_permission_level'] == 3) {
							echo "selected = \"selected\"";
						} ?>
						>Profile Work (Not in inventory)
		</select>
		
		<input type="file" name="file_upload" value="15000000">
		
		
		<input type="text" placeholder="Youtube Code" name="artwork_video" value="<?php echo $artwork['artwork_video_embed']; ?>" />
		
		<input type="submit" value="Update Artwork" id="submit" name="submit" alt="1" class=""></p>
		</form>
		<?php echo "<a href=\"delete_artwork.php?id=" . $id . "\" onclick=\"return confirm('Are you sure you want to delete?')\" >Delete Artwork</a>"; ?>
		
		</div>
		</div>
		
		
		<section class="existingworks">
			
			<h4><a href="add_artwork.php<?php echo "?artist_filter=" . $return; ?>">< BACK TO MANAGE ARTWORKS</a></h4>
			
			<ul>
			<? 		
				
					echo "<li>";
					
					if($artwork['artwork_video_embed']){
						echo "<div class=\"vid\"> " . embed_from_youtube($artwork['artwork_video_embed']) . " </div>";
					} else {
						echo "<div class=\"pic\" style=\"background-image:url(" . $artwork['artwork_image_url'] . ")\"> &nbsp; </div>";
					}
					
					echo "<p>" . strtoupper($artwork['artwork_title']) . " (" . $artwork['artwork_date'] . ")</p>" ;
					
						
					echo "<p>" . $thisartist['artist_first_name'] . " " . $thisartist['artist_last_name'] . "<p>";
					echo "<p>" . $artwork['artwork_dimensions'] . "<p>";
					echo "<p>" . $artwork['artwork_medium'] . "<p>";
					if ($artwork['artwork_price'] > 1) {
						echo "<p id = \"price\">$" . $artwork['artwork_price'] . "<p>";
					} else if ($artwork['artwork_price'] == 1) {
						
					} else if ($artwork['artwork_price'] == 0){
						echo "<p id = \"price\">NFS<p>";
					}
					echo "<p><em>";
						if($artwork['artwork_permission_level'] == 0) {
							echo "Publicly Visible";
						} else if ($artwork['artwork_permission_level'] == 1) {
							echo "Private: Basic";
						} else if ($artwork['artwork_permission_level'] == 2){
							echo "Private: Elite";
						} else if ($artwork['artwork_permission_level'] == 3) {
							echo "Profile Work";
						}
					echo "</em></p>";
					
					
					echo "</li>";
				
			?>
			</ul>
			
			
			
		</section>
		
		</div>
		
		<?php build_footer(); ?>
	 </div>
	 
	</body>
</html>
