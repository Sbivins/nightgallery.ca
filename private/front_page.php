<?php
	require_once("../includes/sessions.php");
	require_once("../includes/connection.php");
	require_once("../includes/functions.php");
	require_once("../includes/private_layout_functions.php");
	
	confirm_logged_in();
	confirm_top_level();


	if(isset($_GET['make_active'])){
		
		$pos = time();
		
		$id = mysql_prep( $_GET['make_active'] );
		
		$query = "UPDATE ng_frontpage ";
		$query .= "SET ";
		$query .= "frontpage_position = '{$pos}' ";
		$query .= "WHERE frontpage_id = {$id}";
			
		$content = mysql_query($query, $mysamconnec);
		testquery($content);
		
		if(mysql_affected_rows()==1){
			header("Location: front_page.php");
			exit;
		} else {
			header("Location: front_page.php");
			exit;
		}
	}
	
	if(isset($_GET['delete'])){
		$id = mysql_prep( $_GET['delete'] );


		$query = "DELETE FROM ng_frontpage WHERE frontpage_id = '$id'";
		$content = mysql_query($query, $mysamconnec);
		testquery($content);

		if(mysql_affected_rows()==1){
			header("Location: front_page.php");
			exit;
		} else {
			header("Location: front_page.php");
			exit;
		}
	}

	//sees if this has just been submitted to itself
	if (isset($_POST['submit'])){
	
		$event_id = $_POST['frontpage_event_id'];
		
		if ($_POST['frontpage_event_id'] > 0) {
			//if it HAS an event_id > 0, then that's chill no matter what,
		
		} else {
			//MUST be a fully fleshed-out advert
			//but if it doesn't, then check that there is both an ad_image, and an ad_primary, and an ad_hyperlink.
			if ($_FILES['main_upload']['name'] == "") {
				$errormessage .= "Please submit a main image.<br>";
			}
		}
		
		if (empty($errormessage)) {
		
			if($_FILES['main_upload']['name'] == "") {
				// No file was selected for upload
				} else {
				$tmp_file = $_FILES['main_upload']['tmp_name'];
				//$target_file = basename($_FILES['file_upload']['name']);
				$target_file = prep_string_for_filename($primary . "main" . time());
				$upload_dir = "../assets/img";
				if(move_uploaded_file($tmp_file, $upload_dir . '/' . $target_file)) {
					$uploaded_main = true;
					$message .= "Image Upload Was Successful. ";
				} else {
					$message .= "Image Upload Failed. ";
				}
				$main_path = "http://www.miekemarple.com/nightgallery/assets/img" . "/" . $target_file;
			}
			
			
			$pos = time();
			
			
			//DO THE QUERY
			$query  = "INSERT INTO ng_frontpage ";
			$query .= "(frontpage_event, ";
			if($uploaded_main) { $query .=" frontpage_image_url, "; }
			$query .= " frontpage_position ) ";
			$query .= "VALUES ('{$event_id}', ";
			if($uploaded_main) { $query .=" '{$main_path}', "; }
			$query .= " '{$pos}' ) ";
			
			//echo $query;
			
			$result = mysql_query($query, $mysamconnec);
			
			if (mysql_affected_rows()==1){
				$message .= "Update was successful";
			} else {
				echo "Update failed";
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
		<title>Night Gallery | Edit Frontpage</title>
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
			<h3>MANAGE FRONTPAGE</h3>
		
		</div>
		
		
		<? //BODY OF THE PAGE ?>
		<div class="shelf"></div>
		
		<div id="addartwork">
		<div id ="artworkform">
		
		
			<?php if(!empty($errormessage)){ echo "<p>" . $errormessage . "</p>"; } ?>
			
			<form action="front_page.php" enctype="multipart/form-data" method="post">
							
				
				
				<p class="walkthrough">UPLOAD BANNER IMAGE</p>
				<input type="file" name="main_upload" value="15000000">
				
				
				<p class="walkthrough">and/or select a Night Gallery event<br>
				</p>
				<select name="frontpage_event_id">
					<option value="0">NOT A N.G. EVENT
					<? 	$query = "SELECT * FROM ng_events ORDER BY event_start ASC";
						$contentset = mysql_query($query, $mysamconnec);
						testquery($contentset);
						while($content = mysql_fetch_array($contentset)){
						
							echo "<option value=" .  $content['event_id'] . " ";
							if($errors){
								if ($_POST['ad_event_id'] == $content['event_id']) {
									echo "selected = \"selected\"";
								}
							}
							echo ">";
							echo strtoupper($content['event_title']) . " - " . format_dates($content['event_start'] , $content['event_end']);
							echo "</option>";
						}
					?>
				</select>
				
				
				<input type="submit" value="UPDATE FRONTPAGE" id="submit" name="submit" alt="1" class=""></p>
			</form>
		
		</div>
		</div>
		
		
		<section class="existingworks" id="inventory">
		
		<h4>EXISTING ADS</h4>
			
			<ul>
			<?php
			
			/* THIS IS WHERE THE ARTWORKS ARE SHOWN ON THIS PAGE. */
				$query = "SELECT * FROM ng_frontpage ORDER BY frontpage_position DESC";
	
				$contentset = mysql_query($query, $mysamconnec);
				testquery($contentset);
				$counter = 0;
				while($frontpage = mysql_fetch_array($contentset)){
					$counter++;
				
					echo "<li ";
					if ($counter <= 1) { echo "class=\"activead\"" ;}
					echo " >";
					
					if(!$frontpage['frontpage_event']){
						//make the custom one
						
						echo "<div class=\"pic\" style=\"background-image:url(" . $frontpage['frontpage_image_url'] . ")\"> &nbsp; </div>";
						echo "</em></p>";
						
						
					} else {
						//just grab the event shit
						
						$query = "SELECT * FROM ng_events WHERE event_id=";
						$query .= $frontpage['frontpage_event'];
						$query .= " LIMIT 1";
						
						$eventresults = mysql_query($query, $mysamconnec);
						testquery($eventresults);
						
						$event = mysql_fetch_array($eventresults);
						
							echo "<div class=\"pic\" style=\"background-image:url(" . $event['event_pic_url'] . ")\"> &nbsp; </div>";
							echo "<p>" . strtoupper($event['event_title']) . "</p>" ;
							echo "<p>" . format_dates($event['event_start'], $event['event_end']) . "<p>";
					}
					
						if ($counter <= 1) { echo "<p class=\"activetext\"><em>ACTIVE</em></p>" ;}
						
					echo "<p class=\"positionoptions\"><a href=\"front_page.php?make_active=" . $frontpage['frontpage_id'] . " \">Make Active</a></p>";
					
					echo "<p class=\"options\">";
					echo "<a href=\"front_page.php?delete=" . $frontpage['frontpage_id'] . "\">Delete</a>";
					echo "</p>";
					
					
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
