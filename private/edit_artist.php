<?php
	require_once("../includes/sessions.php");
	require_once("../includes/connection.php");
	require_once("../includes/functions.php");
	require_once("../includes/private_layout_functions.php");
	
	confirm_logged_in();
	confirm_top_level();

	if (isset($_GET['id'])) {
		$id = $_GET['id'];
	} else {
		header("Location: add_artist.php");
		exit;
	}
	$selected = " selected = \"selected\" ";
	
	//sees if this has just been submitted to itself
	if (isset($_POST['submit'])){
	
		$artist_first_name = $_POST['artist_first_name'];
		$artist_last_name = $_POST['artist_last_name'];
		$artist_bio = htmlentities( $_POST['artist_bio'] );
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
				$upload_dir = "../assets/img";
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
				$upload_dir = "../assets/img";
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
			
			$query = "UPDATE ng_artists ";
			$query .= "SET ";
			$query .= "artist_first_name = '{$artist_first_name}', ";
			$query .= "artist_last_name = '{$artist_last_name}', ";
			$query .= "artist_bio = '{$artist_bio}', ";
			
			if($profpicuploaded) { $query .= "artist_pic = '{$final_path1}', "; }
			if($mainimageuploaded) { $query .= "artist_image = '{$final_path2}', "; }
			if($cvuploaded) { $query .= "artist_cv_url = '{$cv_path}', "; }
			
			$query .= "artist_type = '{$artist_type}' ";
			$query .= "WHERE artist_id = {$id}";
			
			$result = mysql_query($query, $mysamconnec);
			
			if (mysql_affected_rows()==1){
				$message .= "Update was successful";
			} else {
				$message .= "Nothing was updated.";
			}
		}	




	} else if (isset($_POST['addpressitem'])){
		
		
		$title = mysql_prep( $_POST['pressitem_title'] );
		$date = $_POST['pressitem_date'];
		$outlet = $_POST['pressitem_outlet'];
		$writer = $_POST['pressitem_writer'];
		
		//print_r($_FILES);
		
		if (empty($_POST['pressitem_title'])) {
			$presserrors .= "Please enter an artwork title.";
		}
		
		
		if ($_FILES['pressitem_upload']['name'] == "") { 
			$presserrors .= "Please Upload a file.";
		}

		
		if (empty($presserrors)) {
			if($_FILES['pressitem_upload']['name'] == "") {
				// No file was selected for upload
				} else {
				$imageuploaded = true;
				$tmp_file = $_FILES['pressitem_upload']['tmp_name'];
				//$target_file = basename($_FILES['file_upload']['name']); //basename() gets just the name of the file, not the whole file directory.
				$target_file = prep_string_for_filename($title . time()) . ".pdf";
				$upload_dir = "../assets/img";
				if(move_uploaded_file($tmp_file, $upload_dir . '/' . $target_file)) {
					$uploaded_pressitem = true;
					$message .= "Upload Was Successful. ";
				} else {
					$message .= "Upload Failed. ";
				}
				$pressitem_path = "http://www.miekemarple.com/nightgallery/assets/img" . "/" . $target_file;
			}
			
			//DO THE QUERY
			$query  = "INSERT INTO ng_pressitems ";
			$query .= "(pressitem_title, pressitem_date, pressitem_outlet, pressitem_writer, ";
			if($uploaded_pressitem) { $query .=" pressitem_url, "; }
			$query .= " pressitem_artist_id) ";
			$query .= "VALUES ('{$title}', '{$date}', '{$outlet}', '{$writer}', ";
			if($uploaded_pressitem) { $query .=" '{$pressitem_path}', "; }
			$query .= " '{$id}' ) ";
			
			//echo $query;
			
			$result = mysql_query($query, $mysamconnec);
			
			if (mysql_affected_rows()==1){
				$message .= "Update was successful";
			} else {
				$message .= "Update failed";
				die(mysql_error());
			}		
		
		
			} else {
		//do nothing
		}
	}
	
?>


<?php
	
	$query = "SELECT * FROM ng_artists WHERE artist_id =";
	$query .= $id;
	$query .= " LIMIT 1";
	$contentset = mysql_query($query, $mysamconnec);
	testquery($contentset);
	$thisartist = mysql_fetch_array($contentset);
	
?>

<html>
	<head>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans+Mono' rel='stylesheet' type='text/css'>
		<title>NIGHT GALLERY | Edit Artist</title>
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
				<p class="walkthrough">CV (PDF):</p>
				<input type="file" name="cv_upload" value="15000000">
				<input type="submit" value="Update" id="submit" name="submit" alt="1" class="">
				
			</form>
			
			<?php echo "<a href=\"delete_artist.php?id=" . $id . "\" onclick=\"return confirm('Are you sure you want to delete?')\" >Delete Artist</a>"; ?>
			
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
					
					echo "<div class=\"pic\" style=\"background-image:url(" . $content['artist_pic'] . ")\"> &nbsp; ";
					echo "<div class=\"mainimage\" style=\"background-image:url(" . $content['artist_image'] . ")\"> &nbsp; ";
					echo "</div>";
					echo "</div>";
					echo "<p>" . strtoupper($content['artist_first_name'] . " " . $content['artist_last_name']); 
					if ($content['artist_cv_url']) {
						echo "<a href=\"" . $content['artist_cv_url'] . "\" target=\"_blank\" class=\"cvlink popoutlink\">CV &#8663;</a>";
					}
					
					echo "</p>";
					
					echo "<p class=\"artistbio\">" . nl2br( html_entity_decode($content['artist_bio'] )) . "<p>";
					
					
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
					<input type="text" placeholder="Publication" name="pressitem_outlet">
					<input type="text" placeholder="Writer" name="pressitem_writer">
					
					<p class="walkthrough">Upload PDF or IMAGE...</p>
					
					<input type="file" name="pressitem_upload" value="15000000">
					
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
					
					echo "<p class=\"options\">";
					//echo "<a href=\"pressitem.php?edit=" . $content['pressitem_id'] . "\">Edit</a> | ";
					echo "<a href=\"pressitem.php?delete=" . $content['pressitem_id'] . "&return=" . $id . "\">Delete</a></p>";
					
					
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