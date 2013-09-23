<?php
	require_once("../includes/sessions.php");
	require_once("../includes/connection.php");
	require_once("../includes/functions.php");
	require_once("../includes/private_layout_functions.php");
	
	confirm_logged_in();
	confirm_top_level();

	$artist_filter = 0;
	if($_GET['artist_filter']) {
		$artist_filter = $_SESSION['artist_filter'] = $_GET['artist_filter'];
	} else {
		$artist_filter = $_SESSION['artist_filter'];
	}
	
	
	//sees if this has just been submitted to itself
	if (isset($_POST['addsubmit'])){
	
		$uploaded_file = false;
		$title = $_POST['artwork_title'];
		$artist = $_POST['artwork_artist'];
		$date = $_POST['artwork_date'];
		$medium = $_POST['artwork_medium'];
		$dims = $_POST['artwork_dims'];
		$price = $_POST['artwork_price'];
		$level = $_POST['artwork_level'];
		$video = htmlentities( nl2br( $_POST['artwork_video'] ));
		
		//print_r($_FILES);
		
		if (empty($_POST['artwork_title'])) {
			$errormessage .= "Please enter an artwork title.";
		}
		
		if(!empty($errormessage)){
			$errors = true;
		}
		
		if (empty($errormessage)) {
		
			if($_FILES['file_upload']['name'] == "") {
				// No file was selected for upload
				} else {
				$imageuploaded = true;
				$tmp_file = $_FILES['file_upload']['tmp_name'];
				//$target_file = basename($_FILES['file_upload']['name']); //basename() gets just the name of the file, not the whole file directory.
				$target_file = prep_string_for_filename($_POST['artwork_title'] . $_POST['artwork_date'] . time());
				$upload_dir = "../assets/img";
				if(move_uploaded_file($tmp_file, $upload_dir . '/' . $target_file)) {
					$uploaded_file = true;
					$message .= "Image Upload Was Successful. ";
				} else {
					$message .= "Image Upload Failed. ";
				}
				$final_path = "http://www.miekemarple.com/nightgallery/assets/img" . "/" . $target_file;
			}
			
			$pos = time();
			
			//DO THE QUERY
			$query  = "INSERT INTO ng_artworks ";
			$query .= "(artwork_title, artwork_artist_id, artwork_date, artwork_medium, artwork_dimensions, artwork_price, artwork_video_embed, ";
			if($uploaded_file) { $query .=" artwork_image_url, "; }
			$query .= " artwork_permission_level, artwork_order_pos ) ";
			$query .= "VALUES ('{$title}', '{$artist}', '{$date}', '{$medium}', '{$dims}', '{$price}', '{$video}', ";
			if($uploaded_file) { $query .=" '{$final_path}',"; }
			$query .= " '{$level}', '{$pos}' ) ";
			
			//echo $query;
			
			$result = mysql_query($query, $mysamconnec);
			
			if (mysql_affected_rows()==1){
				$message .= "Update was successful";
			} else {
				$message .= "Update failed";
				die(mysql_error());
			}
		}	
			
	} else {
		//do nothing
	}
	
?>

<html>
	<head>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans+Mono' rel='stylesheet' type='text/css'>
		<title>NIGHT GALLERY | Add Artwork</title>
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
		
		<? // TOP BAR OF THE PAGE ?>
		<div id="pagehead">
			<h3>MANAGE ARTWORKS</h3>
			
			<form action="add_artwork.php" method="get">
				
				SHOWING: 
				<select name='artist_filter' onchange='this.form.submit()'>
				
					<option value="-1">All Artists...
					<?
					$query = "SELECT * FROM ng_artists WHERE artist_type>0 ORDER BY artist_last_name ASC";
					$contentset = mysql_query($query, $mysamconnec);
					testquery($contentset);
					
					while($content = mysql_fetch_array($contentset)){
							
						echo "<option value=" .  $content['artist_id'] . " ";
							if ($artist_filter == $content['artist_id']) {
								echo "selected = \"selected\"";
							}
						echo ">";
						echo $content['artist_last_name'] . ", " . $content['artist_first_name'];
						echo "</option>";
					}
					
					echo "<option value=''>-</option>";
					
					$query = "SELECT * FROM ng_artists WHERE artist_type=0 ORDER BY artist_last_name ASC";
					$contentset = mysql_query($query, $mysamconnec);
					testquery($contentset);
					
					while($content = mysql_fetch_array($contentset)){
							
						echo "<option value=" .  $content['artist_id'] . " ";
							if ($artist_filter == $content['artist_id']) {
								echo "selected = \"selected\"";
							}
						echo ">";
						echo $content['artist_last_name'] . ", " . $content['artist_first_name'];
						echo "</option>";
					}
					?>
				</select>
			</form>
		</div>
		
		
		<? //BODY OF THE PAGE ?>
		<div class="shelf"></div>
		
		<div id="addartwork">
		<div id ="artworkform">
		
			<p>ADD ARTWORK</p>
		
			<?php if(!empty($errormessage)){ echo "<p>" . $errormessage . "</p>"; } ?>
			
			<form action="add_artwork.php" enctype="multipart/form-data" method="post">

				<input type="text" placeholder="Title" name="artwork_title" value="<?php if($errors){ echo $_POST['artwork_title']; } ?>" />
				<input type="text" placeholder="Date" name="artwork_date" value="<?php if($errors){ echo $_POST['artwork_date']; } ?>" />
				
				<select name="artwork_artist">
					<option value="0">Select an artist...
					<? 	$query = "SELECT * FROM ng_artists ORDER BY artist_last_name ASC";
						$contentset = mysql_query($query, $mysamconnec);
						testquery($contentset);
						
						while($content = mysql_fetch_array($contentset)){
						
							echo "<option value=" .  $content['artist_id'] . " ";
							if($errors){
								if ($_POST['artwork_artist'] == $content['artist_id']) {
									echo "selected = \"selected\"";
								}
							}
							if($artist_filter > 0) {
								if ($artist_filter == $content['artist_id']) {
									echo "selected = \"selected\"";
								}
							}
							echo ">";
							echo $content['artist_last_name'] . ", " . $content['artist_first_name'];
							echo "</option>";
						}
					?>
				</select>
				
				<input type="text" placeholder="Medium" name="artwork_medium" value="<?php if($errors){ echo $_POST['artwork_medium']; } ?>" />
				<input type="text" placeholder="Dimensions/Duration" name="artwork_dims" value="<?php if($errors){ echo $_POST['artwork_dims']; } ?>" />
				<input type="text" placeholder="Price" name="artwork_price" value="<?php if($errors){ echo $_POST['artwork_price']; } ?>" />
				
				<select name="artwork_level">
					<option value="0">Public (Inventory & Profile)
					<option value="1">Private: Basic
					<option value="2">Private: Elite
					<option value="3">Profile Only (Not in Inventory)
				</select>
				
				<input type="file" name="file_upload" value="15000000">
				<p class="walkthrough">OR</p>
				<input type="text" placeholder="YouTube Embed Code" name="artwork_video" value="<?php if($errors){ echo $_POST['artwork_video']; } ?>" />

				<input type="submit" value="Add Artwork" id="submit" name="addsubmit" alt="1" class=""></p>
			</form>
		
		</div>
		</div>
		
		
		<section class="existingworks" id="inventory">
		
		<h4>INVENTORY</h4>
			
			<ul>
			<?php
			
				/* THIS IS WHERE THE ARTWORKS ARE SHOWN ON THIS PAGE. */
				$query = "SELECT * FROM ng_artworks WHERE ";
				if($artist_filter > 0) { $query .= "artwork_artist_id=" . $artist_filter . "  AND  "; }
				$query .= "artwork_permission_level < 3 ";
				if($artist_filter > 0) {
					$query .= "ORDER BY artwork_order_pos DESC";
				} else {
					$query .= "ORDER BY artwork_insert_time DESC";
				}
				
				
				$contentset = mysql_query($query, $mysamconnec);
				testquery($contentset);
				
				while($content = mysql_fetch_array($contentset)){
				
					echo "<li>";
					
					if($content['artwork_video_embed']){
						echo "<div class=\"vid\"> " . embed_from_youtube($content['artwork_video_embed']) . " </div>";
					} else {
						echo "<div class=\"pic\" style=\"background-image:url(" . $content['artwork_image_url'] . ")\"> &nbsp; </div>";
					}
					
					echo "<p>" . strtoupper($content['artwork_title']) . " (" . $content['artwork_date'] . ")</p>" ;
						$query = "SELECT * FROM ng_artists WHERE artist_id = ";
						$query .= $content['artwork_artist_id'];
						$artworkartistresults = mysql_query($query, $mysamconnec);
						testquery($artworkartistresults);
						$thisartist = mysql_fetch_array($artworkartistresults);
					echo "<p>" . $thisartist['artist_first_name'] . " " . $thisartist['artist_last_name'] . "<p>";
					echo "<p class=\"capitalize\">" . $content['artwork_medium'] . "<p>";
					echo "<p>" . $content['artwork_dimensions'] . "<p>";
					echo "<p><em>";
					if($content['artwork_permission_level'] == 0) {
						echo "Publicly Visible";
					} else if ($content['artwork_permission_level'] == 1) {
						echo "Private: Basic";
					} else {
						echo "Private: Elite";
					}
					echo "</em></p>";
					
					if ($content['artwork_price'] > 1) {
						echo "<p id = \"price\">$" . $content['artwork_price'] . "<p>";
					} else if ($content['artwork_price'] == 1) {
						
					} else if ($content['artwork_price'] == 0){
						echo "<p id = \"price\">NFS<p>";
					}
					
					if($artist_filter > 0) {
						echo "<p class=\"positionoptions\"><a href=\"changeorder.php?forward_artwork=" . $content['artwork_id'] . "&return=" . $artist_filter . " \">Forward</a> | <a href=\"changeorder.php?back_artwork=" . $content['artwork_id'] . "&return=" . $artist_filter . " \">Back</a></p>";
					}
					
					echo "<p class=\"options\"><a href=\"edit_artwork.php?id=" . $content['artwork_id'] . "&return=" . $artist_filter . " \">Edit</a> | <a href=\"delete_artwork.php?id=" . $content['artwork_id'] . "\" onclick=\"return confirm('Are you sure you want to delete?')\" >Delete</a></p>";
					
					
					echo "</li>";
				}
				
			?>
			</ul>
			
		</section>
		
		
		<section class="existingworks" id="profileworks">
		
		<h4>PROFILE WORKS (Not in Inventory)</h4>
			<ul>
			<?php
			
			/* THIS IS WHERE THE ARTWORKS ARE SHOWN ON THIS PAGE. */
				

				$query = "SELECT * FROM ng_artworks WHERE ";
				if($artist_filter > 0) { $query .= "artwork_artist_id=" . $artist_filter . "  AND  "; }
				$query .= "artwork_permission_level = 3 ORDER BY artwork_insert_time DESC";
				
				$contentset = mysql_query($query, $mysamconnec);
				testquery($contentset);
				
				while($content = mysql_fetch_array($contentset)){
				
					echo "<li>";
					
					echo "<div class=\"pic\" style=\"background-image:url(" . $content['artwork_image_url'] . ")\"> &nbsp; </div>";
					echo "<p>" . strtoupper($content['artwork_title']) . " (" . $content['artwork_date'] . ")</p>" ;
					
						$query = "SELECT * FROM ng_artists WHERE artist_id = ";
						$query .= $content['artwork_artist_id'];
						$artworkartistresults = mysql_query($query, $mysamconnec);
						testquery($artworkartistresults);
						$thisartist = mysql_fetch_array($artworkartistresults);
					echo "<p>" . $thisartist['artist_first_name'] . " " . $thisartist['artist_last_name'] . "<p>";
					echo "<p>" . $content['artwork_dimensions'] . "<p>";
					echo "<p>" . $content['artwork_medium'] . "<p>";
					echo "<p><em>Profile Work</em></p>";
					
					if ($content['artwork_price'] > 1) {
						echo "<p id = \"price\">$" . $content['artwork_price'] . "<p>";
					} else if ($content['artwork_price'] == 1) {
						
					} else if ($content['artwork_price'] == 0){
						echo "<p id = \"price\">NFS<p>";
					}
					
					echo "<p class=\"options\"><a href=\"edit_artwork.php?id=" . $content['artwork_id'] . "&return=" . $artist_filter . "\">Edit</a> | <a href=\"delete_artwork.php?id=" . $content['artwork_id'] . "\" onclick=\"return confirm('Are you sure you want to delete?')\" >Delete</a></p>";
					
					
					echo "</li>";
				}
				
			?>

			</ul>
			
		</section>
		
		
		</div>
		
		
		<?php build_footer(); ?>
	 </div>
	 
	</body>
</html>
