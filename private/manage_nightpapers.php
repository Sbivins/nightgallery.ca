<?php
	require_once("../includes/sessions.php");
	require_once("../includes/connection.php");
	require_once("../includes/functions.php");
	require_once("../includes/private_layout_functions.php");
	
	confirm_logged_in();
	confirm_top_level();
	
	//sees if this has just been submitted to itself
	
	if(isset($_GET['delete'])) {
	
		$delete = $_GET['delete'];
		$query = "DELETE FROM ng_papers WHERE papers_id = '$delete'";
		$content = mysql_query($query, $mysamconnec);
		testquery($content);

		if(mysql_affected_rows()==1){
		} else {
		}
		
	} else if (isset($_POST['addpressitem'])){
		
		
		$title = mysql_prep( $_POST['pressitem_title'] );
		$date = $_POST['pressitem_date'];
		
		//print_r($_FILES);
		
		if (empty($_POST['pressitem_title'])) {
			$presserrors .= "Please enter a title.";
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
					$message .= "Image Upload Was Successful. ";
				} else {
					$message .= "Image Upload Failed. ";
				}
				$pressitem_path = "http://www.miekemarple.com/nightgallery/assets/img" . "/" . $target_file;
			}
			
			//DO THE QUERY
			$query  = "INSERT INTO ng_papers ";
			$query .= "(papers_title, papers_date, ";
			$query .=" papers_url ) ";
			
			$query .= "VALUES ('{$title}', '{$date}', ";
			$query .=" '{$pressitem_path}' ) ";
			
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

<html>
	<head>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans+Mono' rel='stylesheet' type='text/css'>
		<title>NIGHT GALLERY | Night Papers</title>
		<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/reset/reset-min.css">
		      <link href="styles/style.css" rel="stylesheet" type="text/css">
		      
		      
	</head>
	<body class="crud1 admin manageads">
	
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
			<h3>NIGHT PAPERS</h3>
		
		</div>
		
		
		<? //BODY OF THE PAGE ?>
		<div class="shelf"></div>
		
		<div id="addartwork">
		<div id ="artworkform">
		
			<p>ADD ISSUE</p>
		
			<?php if(!empty($presserrors)){ echo "<p>" . $presserrors . "</p>"; } ?>
			
			<form action="manage_nightpapers.php" enctype="multipart/form-data" method="post">
					<input type="text" placeholder="Title" name="pressitem_title">
					<input type="text" placeholder="Date" name="pressitem_date">
					
					<p class="walkthrough">Upload PDF or IMAGE...</p>
					
					<input type="file" name="pressitem_upload" value="15000000">
					
					<input type="submit" value="Add Issue" id="addpressitem" name="addpressitem" alt="1" class="">
			</form>
		
		</div>
		</div>
		
		
		<section class="existingworks" id="inventory">
		
		<h4>EXISTING ISSUES</h4>
			
			<ul>
			
			<?php
			
			/* THEN IT SHOWS THEIR PRESS ITEMS. */
				$query = "SELECT * FROM ng_papers ORDER BY papers_date DESC";
				
				$contentset = mysql_query($query, $mysamconnec);
				testquery($contentset);
				
				while($content = mysql_fetch_array($contentset)){
				
					echo "<li>";
					
					echo "<div class=\"pic\" style=\"background-image:url(" . $content['papers_url'] . ")\"> &nbsp; </div>";
					echo "<p>" . $content['papers_title'] . " (" . $content['papers_date'] . ")<p>";
					
					echo "<p class=\"options\">";
					//echo "<a href=\"pressitem.php?edit=" . $content['pressitem_id'] . "\">Edit</a> | ";
					echo "<a href=\"manage_nightpapers.php?delete=" . $content['papers_id'] . "&return=" . $id . "\">Delete</a></p>";
					
					
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
