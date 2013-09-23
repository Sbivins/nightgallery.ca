<?php
	require_once("../includes/sessions.php");
	require_once("../includes/connection.php");
	require_once("../includes/functions.php");
	require_once("../includes/private_layout_functions.php");
	
	confirm_logged_in();
	confirm_top_level();

	$artist_type_filter = 0;
	$artist_type_filter = $_GET['artist_type_filter'];
	$selected = " selected = \"selected\" ";
	
	//sees if this has just been submitted to itself
	if (isset($_POST['addsubmit'])){
	
		$uploaded_file = false;
		$artist_first_name = $_POST['artist_first_name'];
		$artist_last_name = $_POST['artist_last_name'];
		$artist_bio = htmlentities( $_POST['artist_bio']);
		$artist_type = $_POST['artist_type'];
				
		//print_r($_FILES);
		
		if (empty($_POST['artist_first_name']) || empty($_POST['artist_last_name'])) {
			$errormessage .= "Please enter a full name.";
		}
		
		if(!empty($errormessage)){
			$errors = true;
		}
		
		if (empty($errormessage)) {
		
			$upload_dir = "../assets/img";
			
			if($_FILES['profile_pic_upload']['name'] == "") {
				// No file was selected for upload
				} else {
				$tmp_file = $_FILES['profile_pic_upload']['tmp_name'];
				//$target_file = basename($_FILES['file_upload']['name']);
				//basename() gets just the name of the file, not the whole file directory.
				$target_file = prep_string_for_filename($_POST['artist_last_name'] . "profile" . time() );
				if(move_uploaded_file($tmp_file, $upload_dir . '/' . $target_file)) {
					$message .= "Uploaded Profile Picture. ";
					$profpicuploaded = true;
				} else {
					$message .= "Image Upload Failed. ";
				}
				$final_path1 = "http://www.miekemarple.com/nightgallery/assets/img" . "/" . $target_file;
			}
			
			if($_FILES['main_image_upload']['name'] == "") {
				// No file was selected for upload
				} else {
				$tmp_file = $_FILES['main_image_upload']['tmp_name'];
				//$target_file = basename($_FILES['file_upload']['name']);
				//basename() gets just the name of the file, not the whole file directory.
				$target_file = prep_string_for_filename($_POST['artist_last_name'] . "main" . time() );
				if(move_uploaded_file($tmp_file, $upload_dir . '/' . $target_file)) {
					$message .= "Uploaded Main Image. ";
					$mainimageuploaded = true;
				} else {
					$message .= "Image Upload Failed. ";
				}
				$final_path2 = "http://www.miekemarple.com/nightgallery/assets/img" . "/" . $target_file;
			}
			
			if($_FILES['cv_upload']['name'] == "") {
				// No file was selected for upload
				} else {
				$tmp_file = $_FILES['cv_upload']['tmp_name'];
				//$target_file = basename($_FILES['file_upload']['name']);
				//basename() gets just the name of the file, not the whole file directory.
				$target_file = prep_string_for_filename($_POST['artist_last_name'] . "_cv" . time() ) . ".pdf";
				if(move_uploaded_file($tmp_file, $upload_dir . '/' . $target_file)) {
					$message .= "Uploaded Main Image. ";
					$cvuploaded = true;
				} else {
					$message .= "Image Upload Failed. ";
				}
				$cv_path = "http://www.miekemarple.com/nightgallery/assets/img" . "/" . $target_file;
			}
			
			//DO THE QUERY
			$query  = "INSERT INTO ng_artists ";
			$query .= "(artist_first_name, artist_last_name, artist_bio, ";
			if($profpicuploaded) { $query .=" artist_pic, "; }
			if($mainimageuploaded) { $query .=" artist_image, "; }
			if($cvuploaded) { $query .=" artist_cv_url, "; }
			$query .= " artist_type) ";
			$query .= "VALUES ('{$artist_first_name}', '{$artist_last_name}', '{$artist_bio}', ";
			if($profpicuploaded) { $query .=" '{$final_path1}', "; }
			if($mainimageuploaded) { $query .=" '{$final_path2}', "; }
			if($cvuploaded) { $query .=" '{$cv_path}', "; }
			$query .= " '{$artist_type}' ) ";
			
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
		<title>NIGHT GALLERY | Manage Artists</title>
		<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/reset/reset-min.css">
		      <link href="styles/style.css" rel="stylesheet" type="text/css">
		      
		      
	</head>
	<body class="crud1 admin manageartists">
	
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
			<h3>MANAGE ARTISTS</h3>
			
			<form action="add_artist.php" method="get">
				
				SHOWING:
				<select name='artist_type_filter' onchange='this.form.submit()'>
				
					<option value="0" <?php if($artist_type_filter == 0) {echo $selected;} ?>>All Artists...
					<option value="1" <?php if($artist_type_filter == 1) {echo $selected;} ?>>Exhibited Artists
					<option value="2" <?php if($artist_type_filter == 2) {echo $selected;} ?>>Gallery Artists
					
				</select>
			</form>
		</div>
		
		
		<? //BODY OF THE PAGE ?>
		<div class="shelf"></div>
		
		<div id="addartwork">
		<div id ="artworkform">
		
			<p>ADD ARTIST</p>
		
			<?php if(!empty($errormessage)){ echo "<p>" . $errormessage . "</p>"; } ?>
			
			<form action="add_artist.php" enctype="multipart/form-data" method="post">

				<input class="nameinput" type="text" placeholder="First Name, Middle..." name="artist_first_name" value="<?php if($errors){ echo $_POST['artist_first_name']; } ?>" />
				
				<input class="nameinput" type="text" placeholder="Last Name" name="artist_last_name" value="<?php if($errors){ echo $_POST['artist_last_name']; } ?>" />
	
				<select name="artist_type">
					<option value="0">Exhibited Artist
					<option value="1">Gallery Artist
				</select>
				
				<textarea placeholder="Bio..." name="artist_bio" ><?php if($errors){ echo $_POST['artist_bio']; } ?></textarea>
				

				<p class="walkthrough">PROFILE PHOTO:</p>
				<input type="file" name="profile_pic_upload" value="15000000">
				<p class="walkthrough">ARTIST MAIN IMAGE:</p>
				<input type="file" name="main_image_upload" value="15000000">
				<p class="walkthrough">CV (PDF):</p>
				<input type="file" name="cv_upload" value="15000000">
				<p class="walkthrough">NOTE: Add press items after creating the artist by editing the artist.</p>
				<input type="submit" value="Add Artist" id="submit" name="addsubmit" alt="1" class="">
				
			</form>
		</div>
		</div>
		
		
		
		
		
		
		
		<section class="existingworks" id="inventory">
		
		<h4>EXISTING ARTISTS</h4>
			
			<ul>
			<?php
			
			/* THIS IS WHERE THE ARTWORKS ARE SHOWN ON THIS PAGE. */
				$query = "SELECT * FROM ng_artists ";
				if($artist_type_filter > 0) { $query .= "WHERE artist_type=" . ($artist_type_filter-1) ; }
				$query .= " ORDER BY artist_last_name ";
				
				$contentset = mysql_query($query, $mysamconnec);
				testquery($contentset);
				
				while($content = mysql_fetch_array($contentset)){
				
					echo "<li>";
					
					echo "<div class=\"pic\" style=\"background-image:url(" . $content['artist_pic'] . ")\"> &nbsp; ";
					echo "<div class=\"mainimage\" style=\"background-image:url(" . $content['artist_image'] . ")\"> &nbsp; ";
					
					echo "</div>";
					echo "</div>";
					echo "<p>" . strtoupper($content['artist_first_name'] . " " . $content['artist_last_name']);
					
					
					if ($content['artist_cv_url']) {
						echo "<a href=\"" . $content['artist_cv_url'] . "\" target=\"_blank\" class=\"cvlink popoutlink\">CV &#8663;</a>";
					}
					
					echo "</p>";
					
					
					
					echo "<p class=\"artistbio\">" . nl2br( html_entity_decode( $content['artist_bio'] )) . "<p>";
					echo "<p>" . $content['artwork_medium'] . "<p>";
					
					
					
					echo "<p><em>";
					if($content['artist_type'] == 0) {
						echo "Exhibited Artist";
					} else if ($content['artist_type'] == 1) {
						echo "Gallery Artist";
					}
					echo "</em>";
					echo "</p>";
					
					
					echo "<p class=\"options\"><a href=\"edit_artist.php?id=" . $content['artist_id'] . "\">Edit</a> | <a href=\"delete_artist.php?id=" . $content['artist_id'] . "\" onclick=\"return confirm('Are you sure you want to delete?')\" >Delete</a></p>";
					
					
					echo "</li>";
				}
				
			?>
			</ul>
			
		</section>
		
		
		</div>
		
		
		
	 </div>
	 
	 <?php build_footer(); ?>
	 
	</body>
</html>
