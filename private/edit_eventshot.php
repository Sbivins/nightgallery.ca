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
		header("Location: admin.php");
		exit;
	}
	
	//sees if this has just been submitted to itself
	if (isset($_POST['addeventshot'])){
		
		$caption = $_POST['event_shot_caption'];
		$return = $_GET['return'];
		//print_r($_FILES);
		
		if (empty($errormessage)) {
			
			if($_FILES['event_shot']['name'] == "") {
				// No file was selected for upload
				} else {
				$imageuploaded = true;
				$tmp_file = $_FILES['event_shot']['tmp_name'];
				$target_file = prep_string_for_filename($caption . time() );
				$upload_dir = "../assets/img";
				if(move_uploaded_file($tmp_file, $upload_dir . '/' . $target_file)) {
					$message .= "Image Upload Was Successful. ";
				} else {
					//
				}
				$final_path = "http://www.miekemarple.com/nightgallery/assets/img" . "/" . $target_file;
			}
			
			//DO THE QUERY
			$query = "UPDATE ng_event_shots ";
			$query .= "SET ";
			$query .= "event_shot_caption = '{$caption}' ";
			if($imageuploaded) { 
				$query .= ", event_shot_url = '$final_path' "; 
			}
			$query .= "WHERE event_shot_id = {$id}";
			 
			$result = mysql_query($query, $mysamconnec);
			
			if (mysql_affected_rows()==1){
				header("Location: edit_event.php?id=" . $return . "#event_shots");
			} else {
				$message .= "Nothing was updated." . mysql_error();
			}
		}	
	} else {

	}
	
?>


<?php
	
		
	$query = "SELECT * FROM ng_event_shots ";
	$query .= "WHERE event_shot_id=" . $id;
	$query .= " LIMIT 1";
				
	$contentset = mysql_query($query, $mysamconnec);
	testquery($contentset);
				
	$eventshot = mysql_fetch_array($contentset);
	
	//gets which artist this artwork is by so it can preselect in the "select an artist" drop-down.
	
?>

<html>
	<head>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans+Mono' rel='stylesheet' type='text/css'>
		<title>NIGHT GALLERY | Edit Event Shot</title>
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
		<h3>EDIT EVENT SHOT</h3>
		</div>
		
		<div id ="addartwork">
		<div id ="artworkform">
		
		
		<?php if(!empty($errormessage)){ echo "<p>" . $errormessage . "</p>"; } ?>
		<?php if(!empty($message)){ echo "<p>" . $message . "</p>"; } ?>
		
		<form action="edit_eventshot.php?id=<?php echo $id. "&return=" . $eventshot['event_shot_event']; ?>" enctype="multipart/form-data" method="post">
				
				<p class="walkthrough">Upload image...</p>
				
				<input type="file" name="event_shot" value="15000000">
				
				<input type="text" placeholder="Caption (optional)" name="event_shot_caption" value="<?php echo $eventshot['event_shot_caption']; ?>">
			
				<input type="submit" value="Edit Shot" id="submit" name="addeventshot" alt="1" class=""></p>
			</form>
		<?php echo "<a href=\"edit_event.php?id=" . $eventshot['event_shot_event'] . "&deleteshot=" . $eventshot['event_shot_id'] . "\" onclick=\"return confirm('Are you sure you want to delete?')\" >Delete Event Shot</a>"; ?>
		
		</div>
		</div>
		
		
		<section class="existingworks">
			
			<h4><a href="edit_event.php<?php echo "?id=" . $eventshot['event_shot_event']; ?>">< BACK TO EVENT</a></h4>
			
			<ul>
			<? 		
				
					echo "<li>";
					
					if($eventshot['event_shot_videolink']){
						echo "<div class=\"vid\"> " . embed_from_youtube($eventshot['event_shot_videolink']) . " </div>";
					} else {
						echo "<div class=\"pic\" style=\"background-image:url(" . $eventshot['event_shot_url'] . ")\"> &nbsp; </div>";
					}
					
					echo "<p>" . $eventshot['event_shot_caption'] . "<p>";
										
					echo "</li>";
				
			?>
			</ul>
			
			
			
		</section>
		
		</div>
		
		<?php build_footer(); ?>
	 </div>
	 
	</body>
</html>
