<?php
	require_once("../includes/sessions.php");
	require_once("../includes/connection.php");
	require_once("../includes/functions.php");
	require_once("../includes/private_layout_functions.php");
	
	confirm_logged_in();
	confirm_top_level();

	if (isset($_GET['edit']) || isset($_GET['delete'])) {
		$edit = $_GET['edit'];
		$delete = $_GET['delete'];
	} else {
		header("Location: add_artist.php");
		exit;
	}
	
	$selected = " selected = \"selected\" ";
	//sees if this has just been submitted to itself
	if (isset($_POST['submit'])){
		
		$title = $_POST['pressitem_title'];
		$date = $_POST['pressitem_date'];
		$outlet = $_POST['pressitem_outlet'];
		$writer = $_POST['pressitem_writer'];
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
		
	} else if ($delete){
		
			$query = "DELETE FROM ng_pressitems WHERE pressitem_id ='{$delete}'";
			$delete = mysql_query($query, $mysamconnec);
			testquery($delete);
			
			if(mysql_affected_rows()==1){
				header("Location: edit_artist.php?id=" . $_GET['return']);
				exit;
			} else {
				header("Location: edit_artist.php?id=" . $_GET['return']);
				exit;
			}
	
	} else {
		//do nothing
	}
	
?>


<?php
	
	$query = "SELECT * FROM ng_pressitems WHERE pressitem_id =";
	$query .= $id;
	$query .= " LIMIT 1";
	$contentset = mysql_query($query, $mysamconnec);
	testquery($contentset);
	$thisartist = mysql_fetch_array($contentset);
	
?>

<html>
	<head>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans+Mono' rel='stylesheet' type='text/css'>
		<title>Night Gallery | Add Artwork</title>
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
			<h3>UPDATE <?php echo strtoupper( $thisartist['artist_first_name'] . " " . $thisartist['artist_last_name'] ); ?></h3>
		</div>
		
		
		<? //BODY OF THE PAGE ?>
		<div class="shelf"></div>
		
		<div id="addartwork">
		<div id ="artworkform">
		
			<p>UPDATE ARTIST</p>
		
			<?php if(!empty($errormessage)){ echo "<p>" . $errormessage . "</p>"; } ?>
			
			<form action="edit_artist.php?id=<?php echo $id; ?>" enctype="multipart/form-data" method="post">

				<input class="nameinput" type="text" placeholder="First Name, Middle..." name="artist_first_name" value="<?php echo $thisartist['artist_first_name'] ?>" />
				
				<input class="nameinput" type="text" placeholder="Last Name" name="artist_last_name" value="<?php echo $thisartist['artist_last_name'] ?>" />
	
				<select name="artist_type">
					<option value="0" <?php if($thisartist['artist_type'] == 0) {echo $selected;} ?>>Exhibited Artist
					<option value="1" <?php if($thisartist['artist_type'] == 1) {echo $selected;} ?>>Gallery Artist
				</select>
				
				<textarea placeholder="Bio..." name="artist_bio" ><?php
					echo $thisartist['artist_bio'];
				?></textarea>

				<p class="walkthrough">PROFILE PHOTO:</p>
				<input type="file" name="profile_pic_upload" value="15000000">
				<p class="walkthrough">ARTIST MAIN IMAGE:</p>
				<input type="file" name="main_image_upload" value="15000000">
				<input type="submit" value="Update" id="submit" name="submit" alt="1" class="">
				
			</form>
			
			<?php echo "<a href=\"delete_artist.php?id=" . $id . "\">Delete Artist</a>"; ?>
			
		</div>
		</div>
		
		
		
		
		
		
		
		<section class="existingworks" id="inventory">
		
		<h4><a href="add_artist.php">< BACK TO MANAGE ARTISTS</a></h4>
			
			<ul>
			<?php
			
			/* THIS IS WHERE THE ARTIST WHO IS CURRENTLY BEING EDITED IS SHOWN. */
				$query = "SELECT * FROM ng_artists ";
				$query .= "WHERE artist_id=" . $id;
				
				$contentset = mysql_query($query, $mysamconnec);
				testquery($contentset);
				
				while($content = mysql_fetch_array($contentset)){
				
					echo "<li>";
					
					echo "<div class=\"pic\" style=\"background-image:url(" . $content['artist_pic'] . ")\"> &nbsp; </div>";
					echo "<p>" . strtoupper($content['artist_first_name'] . " " . $content['artist_last_name']) . "<p>";
					echo "<p class=\"artistbio\">" . stripslashes( nl2br( $content['artist_bio'] )) . "<p>";
					echo "<p><em>";
					if($content['artist_type'] == 0) {
						echo "Exhibited Artist";
					} else if ($content['artist_type'] == 1) {
						echo "Gallery Artist";
					}
					echo "</em></p>";
					echo "</li>";
				}
				
			?>
			
			</ul>
			
		</section>
		
		<section class="existingworks pressitems" id="profileworks">
		
		<h4>PRESS ITEMS</h4>
			<ul>
			
			
			<li class="form">
				<p>ADD PRESS ITEM</p>
				<form action="edit_artist.php?id=<?php echo $id; ?>" enctype="multipart/form-data" method="post">
					
					<input type="text" placeholder="Title" name="pressitem_title">
					<input type="text" placeholder="Date" name="pressitem_date">
					<input type="text" placeholder="Publication" name="pressitem_publication">
					<input type="text" placeholder="Writer" name="pressitem_writer">
					
					<p class="walkthrough">Upload PDF or IMAGE...</p>
					
					<input type="file" name="pressitem" value="15000000">
					
					<input type="submit" value="Add Press Item" id="addpressitem" name="addpressitem" alt="1" class="">
					
				</form>
			
			</li>
			
			
			<?php
			
			/* THEN IT SHOWS THEIR PRESS ITEMS. */
				$query = "SELECT * FROM ng_pressitems ";
				$query .= "WHERE pressitem_artist_id=" . $id;
				
				$contentset = mysql_query($query, $mysamconnec);
				testquery($contentset);
				
				while($content = mysql_fetch_array($contentset)){
				
					echo "<li>";
					
					echo "<div class=\"pic\" style=\"background-image:url(" . $content['pressitem_url'] . ")\"> &nbsp; </div>";
					echo "<p>" . $content['pressitem_title'] . " (" . $content['pressitem_date'] . ")<p>";
					echo "<p>" . $content['pressitem_outlet'] . "</p><p>" . $content['pressitem_writer'] . "<p>";
					
					echo "<p class=\"options\"><a href=\"pressitem.php?edit=" . $content['pressitem_id'] . "\">Edit</a> | <a href=\"pressitem.php?delete=" . $content['pressitem_id'] . "\">Delete</a></p>";
					
					
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
