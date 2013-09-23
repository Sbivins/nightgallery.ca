<?php
	require_once("../includes/sessions.php");
	require_once("../includes/connection.php");
	require_once("../includes/functions.php");
	require_once("../includes/private_layout_functions.php");
	
	confirm_logged_in();
	confirm_top_level();

	//$artist_type_filter = 0;
	//$artist_type_filter = $_GET['artist_type_filter'];
	$selected = " selected = \"selected\" ";
	
	//sees if this has just been submitted to itself
	if (isset($_POST['addsubmit'])){
	
		$uploaded_file = false;
		$newsitem_title = $_POST['newsitem_title'];
		$newsitem_url = $_POST['newsitem_url'];
		$newsitem_body = htmlentities( nl2br( $_POST['newsitem_body'] ));
		
				
		//print_r($_FILES);
		
		if(!empty($errormessage)){
			$errors = true;
		}
		
		if (empty($errormessage)) {
		
			if($_FILES['newsitem_photo_upload']['name'] == "") {
				// No file was selected for upload
				} else {
				$tmp_file = $_FILES['newsitem_photo_upload']['tmp_name'];
				//$target_file = basename($_FILES['file_upload']['name']);
				//basename() gets just the name of the file, not the whole file directory.
				$target_file = prep_string_for_filename($_POST['newsitem_title'] . "profile" . time() );
				$upload_dir = "../assets/img";
				if(move_uploaded_file($tmp_file, $upload_dir . '/' . $target_file)) {
					$message .= "Uploaded Image. ";
					$profpicuploaded = true;
				} else {
					$message .= "Image Upload Failed. ";
				}
				$final_path1 = "http://www.miekemarple.com/nightgallery/assets/img" . "/" . $target_file;
			}
			
			//DO THE QUERY
			$query  = "INSERT INTO ng_newsitem ";
			$query .= "(newsitem_title, newsitem_body, newsitem_url ";
			if($profpicuploaded) { $query .=" ,newsitem_photo "; }
			$query .= " ) ";
			$query .= "VALUES ('{$newsitem_title}', '{$newsitem_body}', '{$newsitem_url}' ";
			if($profpicuploaded) { $query .=" ,'{$final_path1}' "; }
			
			$query .= " ) ";
			
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
		<title>NIGHT GALLERY | Manage News</title>
		<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/reset/reset-min.css">
		      <link href="styles/style.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="js/javascript.js"> </script>
		      
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
			<h3>MANAGE NEWS</h3>
		</div>
		
		
		<? //BODY OF THE PAGE ?>
		<div class="shelf"></div>
		
		<div id="addartwork">
		<div id ="artworkform">
		
			<p>POST NEWS</p>
		
			<?php if(!empty($errormessage)){ echo "<p>" . $errormessage . "</p>"; } ?>
			
			<form action="add_newsitem.php" enctype="multipart/form-data" method="post">

				<input type="text" placeholder="Title..." name="newsitem_title" value="<?php if($errors){ echo $_POST['newsitem_title']; } ?>" />
				
				<input type="text" placeholder="Optional Direct URL" name="newsitem_url" value="<?php if($errors){ echo $_POST['newsitem_url']; } ?>" />
				
				<textarea placeholder="Text..." name="newsitem_body" ><?php if($errors){ echo nl2br( $_POST['newsitem_body'] ); } ?></textarea>
				<p class="walkthrough">PHOTO:</p>
				<input type="file" name="newsitem_photo_upload" value="15000000">
				<input type="submit" value="Post Item" id="submit" name="addsubmit" alt="1" class="">
				
			</form>
		</div>
		</div>
		
		
		
		
		
		
		
		<section class="existingworks" id="inventory">
		
		<h4>NEWS ITEMS</h4>
			
			<ul>
			<?php
			
			/* THIS IS WHERE THE ARTWORKS ARE SHOWN ON THIS PAGE. */
				$query = "SELECT * FROM ng_newsitem ";
				$query .= " ORDER BY newsitem_date DESC";
				
				$contentset = mysql_query($query, $mysamconnec);
				testquery($contentset);
				
				while($content = mysql_fetch_array($contentset)){
				
					echo "<li>";
					echo "<div class=\"pic\" style=\"background-image:url(" . $content['newsitem_photo'] . ")\"> &nbsp; ";
					echo "</div>";
					echo "<p>" . strtoupper($content['newsitem_title'] . " " . $content['artist_last_name']) . "<p>";
					//echo "<p class=\"artistbio\">" . stripslashes( nl2br( $content['newsitem_body'] )) . "<p>";
					echo "<p class=\"artistbio\">" . nl2br( html_entity_decode(  $content['newsitem_body'] )) . "<p>";
					echo "<p class=\"options\"><a href=\"edit_newsitem.php?id=" . $content['newsitem_id'] . "\">Edit</a> | <a href=\"delete_newsitem.php?id=" . $content['newsitem_id'] . "\" onclick=\"return confirm('Are you sure you want to delete?')\" >Delete</a></p>";
					
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
