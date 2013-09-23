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
		header("Location: add_newsitem.php");
		exit;
	}
	$selected = " selected = \"selected\" ";
	
	//sees if this has just been submitted to itself
	if (isset($_POST['submit'])){
	
		$newsitem_title = $_POST['newsitem_title'];
		$newsitem_url = $_POST['newsitem_url'];
		$newsitem_body = htmlentities($_POST['newsitem_body']);
		
		//print_r($_FILES);
		
		if(!empty($errormessage)){
			$errors = true;
		}
		
		if (empty($errormessage)) {
		
			if($_FILES['profile_pic_upload']['name'] == "") {
				// No file was selected for upload
				} else {
				$tmp_file = $_FILES['profile_pic_upload']['tmp_name'];
				//$target_file = basename($_FILES['file_upload']['name']);
				//basename() gets just the name of the file, not the whole file directory.
				$target_file = prep_string_for_filename($_POST['newsitem_title'] . "profile" . time() );
				$upload_dir = "../assets/img";
				if(move_uploaded_file($tmp_file, $upload_dir . '/' . $target_file)) {
					$message .= "Uploaded Photo. ";
					$profpicuploaded = true;
				} else {
					$message .= "Image Upload Failed. ";
				}
				$final_path1 = "http://www.miekemarple.com/nightgallery/assets/img" . "/" . $target_file;
			}
			
			//DO THE QUERY
			
			$query = "UPDATE ng_newsitem ";
			$query .= "SET ";
			$query .= "newsitem_title = '{$newsitem_title}', ";
			$query .= "newsitem_body = '{$newsitem_body}', ";
			$query .= "newsitem_url = '{$newsitem_url}'";
			
			if($profpicuploaded) { $query .= ", artist_pic = '{$final_path1}' "; }
			
			$query .= " WHERE newsitem_id = {$id}";
			
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
	
	$query = "SELECT * FROM ng_newsitem WHERE newsitem_id =";
	$query .= $id;
	$query .= " LIMIT 1";
	$contentset = mysql_query($query, $mysamconnec);
	testquery($contentset);
	$thisitem = mysql_fetch_array($contentset);
	
?>

<html>
	<head>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans+Mono' rel='stylesheet' type='text/css'>
		<title>NIGHT GALLERY | Edit News Item</title>
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
			<h3>UPDATE <?php echo "\"" . strtoupper( $thisitem['newsitem_title'] . "\"" ); ?></h3>
		</div>
		
		
		<? //BODY OF THE PAGE ?>
		<div class="shelf"></div>
		
		<div id="addartwork">
		<div id ="artworkform">

			<p>UPDATE ITEM</p>

			<?php if(!empty($errormessage)){ echo "<p>" . $errormessage . "</p>"; } ?>
			
			<form action="edit_newsitem.php?id=<?php echo $id; ?>" enctype="multipart/form-data" method="post">
			
				<input type="text" placeholder="Title" name="newsitem_title" value="<?php echo $thisitem['newsitem_title']  ?>" />
				
				<input type="text" placeholder="Optional Direct URL" name="newsitem_url" value="<?php  echo $thisitem['newsitem_url']; ?>" />
				
				<textarea placeholder="Bio..." name="newsitem_body" ><?php echo $thisitem['newsitem_body']; ?></textarea>

				<p class="walkthrough">PROFILE PHOTO:</p>
				<input type="file" name="profile_pic_upload" value="15000000">
				
				<input type="submit" value="Update" id="submit" name="submit" alt="1" class="">
				
			</form>
			
			<?php echo "<a href=\"delete_newsitem.php?id=" . $id . "\" onclick=\"return confirm('Are you sure you want to delete?')\" >Delete News Item</a>"; ?>
			
		</div>
		</div>
		
		
		
		
		
		
		
		<section class="existingworks" id="inventory">
		
		<h4><a href="add_newsitem.php">< BACK TO MANAGE NEWS</a></h4>
			
			<ul>
			<?php
			
			/* THIS IS WHERE THE ARTIST WHO IS CURRENTLY BEING EDITED IS SHOWN. */
				$query = "SELECT * FROM ng_newsitem ";
				$query .= "WHERE newsitem_id=" . $id;
				
				$contentset = mysql_query($query, $mysamconnec);
				testquery($contentset);
				
				while($content = mysql_fetch_array($contentset)){
				
					echo "<li>";
					
					echo "<div class=\"pic\" style=\"background-image:url(" . $content['newsitem_photo'] . ")\"> &nbsp; ";
					echo "</div>";
					echo "<p>" . strtoupper($content['newsitem_title']) . "<p>";
					echo "<p class=\"artistbio\">" . nl2br( html_entity_decode( $content['newsitem_body'] )) . "<p>";
					echo "</li>";
				}
				
			?>
			
			</ul>
			
		</section>
		
		
		</div>
		
		
		<?php build_footer(); ?>
	 </div>
	 
	</body>
	
	<?php ?>
</html>