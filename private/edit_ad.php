<?php
	require_once("../includes/sessions.php");
	require_once("../includes/connection.php");
	require_once("../includes/functions.php");
	require_once("../includes/private_layout_functions.php");
	
	confirm_logged_in();
	confirm_top_level();

	if (isset($_GET['id']) || isset($_GET['delete'])) {
		$id = $_GET['id'];
		$delete = $_GET['delete'];
	} else {
		header("Location: manage_ads.php");
		exit;
	}
	
	$selected = " selected = \"selected\" ";
	//sees if this has just been submitted to itself
	if (isset($_POST['adsubmit'])){
	
		$primary = $_POST['ad_primary'];
		$secondary = $_POST['ad_secondary'];
		$pr = htmlentities( nl2br($_POST['ad_pr'] ) );
		$hyperlink = $_POST['ad_hyperlink'];
		$event_id = $_POST['ad_event_id'];
		$direct = $_POST['ad_direct'];
		
		 
		
		if ($_POST['ad_event_id'] > 0) {
			//if it HAS an event_id > 0, then that's chill no matter what,
			
			
		} else {
			//MUST be a fully fleshed-out advert
			//but if it doesn't, then check that there is both an ad_image, and an ad_primary, and an ad_hyperlink.
			if (empty($_POST['ad_primary'])) {
				$errormessage .= "Please enter a title.<br>";
			}
			if (empty($_POST['ad_hyperlink'])) {
				$errormessage .= "Please enter a URL to link to.<br>";
			}
			if (!empty($errormessage)) {
				$errormessage .= "Or, simply choose an event.";
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
			
			
			
			if($_FILES['thumb_upload']['name'] == "") {
				// No file was selected for upload
				
				} else {
				$tmp_file = $_FILES['thumb_upload']['tmp_name'];
				//$target_file = basename($_FILES['file_upload']['name']);
				$target_file = prep_string_for_filename($primary . "thumb" . time());
				$upload_dir = "../assets/img";
				if(move_uploaded_file($tmp_file, $upload_dir . '/' . $target_file)) {
					$uploaded_thumb = true;
					$message .= "Image Upload Was Successful. ";
				} else {
					$message .= "Image Upload Failed. ";
				}
				$thumb_path = "http://www.miekemarple.com/nightgallery/assets/img" . "/" . $target_file;
			}
			
			
			$pos = time();
			
			//DO THE QUERY
			
			
			$query = "UPDATE ng_ads ";
			$query .= "SET ";
			$query .= "ad_primary = '{$primary}', ";
			$query .= "ad_secondary = '{$secondary}', ";
			$query .= "ad_pr = '{$pr}', ";
			$query .= "ad_hyperlink = '{$hyperlink}', ";
			$query .= "ad_event_id = '{$event_id}', ";
			
			if($uploaded_main) { $query .= "ad_image_url= '{$main_path}', "; }
			if($uploaded_thumb) { $query .= "ad_thumb_url = '{$thumb_path}', "; }
			
			$query .= "ad_position = '{$artist_type}', ";
			$query .= "ad_direct = '{$artist_type}' ";
			$query .= "WHERE ad_id = {$id}";
			
			
			
			//echo $query;
			
			$result = mysql_query($query, $mysamconnec);
			
			if (mysql_affected_rows()==1){
				$message .= "Update was successful";
			} else {
				$message .= "Update failed";
				die(mysql_error());
			}
		}	
			
	} else if ($delete){
		
			$query = "DELETE FROM ng_ads WHERE ad_id ='{$delete}'";
			$delete = mysql_query($query, $mysamconnec);
			testquery($delete);
			
			if(mysql_affected_rows()==1){
				header("Location: manage_ads.php");
				exit;
			} else {
				header("Location: manage_ads.php");
				exit;
			}
	
	} else {
		//do nothing
	}
	
?>


<?php
	
	$query = "SELECT * FROM ng_ads WHERE ad_id =";
	$query .= $id;
	$query .= " LIMIT 1";
	$contentset = mysql_query($query, $mysamconnec);
	testquery($contentset);
	$ad = mysql_fetch_array($contentset);
	
?>

<html>
	<head>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans+Mono' rel='stylesheet' type='text/css'>
		<title>Night Gallery | Edit Ad</title>
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
			<h3>EDIT <?php echo "\""  . $ad['ad_primary'] . "\"" ?></h3>
		
		</div>
		
		
		<? //BODY OF THE PAGE ?>
		<div class="shelf"></div>
		
		<div id="addartwork">
		<div id ="artworkform">
		
			<p>EDIT</p>
		
			<?php if(!empty($errormessage)){ echo "<p>" . $errormessage . "</p>"; } ?>
			
			<form action="edit_ad.php?id=<?php echo $id; ?>" enctype="multipart/form-data" method="post">
			
				<input type="text" placeholder="Title" name="ad_primary" value="<?php echo $ad['ad_primary'] ?>" />
				<input type="text" placeholder="Secondary Info (date, tagline)..." name="ad_secondary" value="<?php echo $ad['ad_secondary'] ?>" />
				<input type="text" placeholder="http://www..." name="ad_hyperlink" value="<?php echo $ad['ad_hyperlink'] ?>" />
				
				<textarea placeholder="Press Release..." name="ad_pr" ><?php
					echo $ad['ad_pr'];
				?></textarea>
				
				<p class="walkthrough">UPLOAD MAIN IMAGE</p>
				<input type="file" name="main_upload" value="15000000">
				<p class="walkthrough">UPLOAD THUMB</p>
				<input type="file" name="thumb_upload" value="15000000">
				
				<p class="walkthrough">Direct to:</p>
				<select name="ad_direct">
					<option value="0" <?php if($ad['ad_direct'] == 0) {echo $selected;} ?>>Landing Page</option>
					<option value="1" <?php if($ad['ad_direct'] == 1) {echo $selected;} ?>>External Link</option>
				</select>
				
				<p class="walkthrough">OR select a Night Gallery event<br>
				NOTE: If you select an event below, all the other info will not be used.</p>
				<select name="ad_event_id">
					<option value="0">NOT A N.G. EVENT
					<? 	$query = "SELECT * FROM ng_events ORDER BY event_start DESC";
						$contentset = mysql_query($query, $mysamconnec);
						testquery($contentset);
						while($content = mysql_fetch_array($contentset)){
						
							echo "<option value=\"" .  $content['event_id'] . "\" ";
							if ($ad['ad_event_id'] == $content['event_id']) {
								echo $selected;
							}
							echo ">";
							echo strtoupper($content['event_title']) . " - " . format_dates($content['event_start'] , $content['event_end']);
							echo "</option>";
						}
					?>
				</select>
				
				
				<input type="submit" value="Add Ad" id="submit" name="adsubmit" alt="1" class=""></p>
			</form>
		
		</div>
		</div>
		
		
		<section class="existingworks" id="inventory">
		
		<h4><a href="manage_ads.php">< BACK TO MANAGE ADS</a></h4>
			
			<ul>
			<?php
			
			/* THIS IS WHERE THE ARTWORKS ARE SHOWN ON THIS PAGE. */
				
				$counter = 0;
				
				echo "<li>";
				
				if(!$ad['ad_event_id']){
					//make the custom one
					
					echo "<div class=\"pic\" style=\"background-image:url(" . $ad['ad_image_url'] . ")\"> &nbsp; </div>";
					echo "<p>" . strtoupper( $ad['ad_primary'] ) . "</p>" ;
					echo "<p class=\"capitalize\">" . $ad['ad_secondary'] . "<p>";
					echo "<p><em>";
					echo "<a target=\"_blank\" href=\"" . $ad['ad_hyperlink'] . "\">" . truncate($ad['ad_hyperlink']) . "</a>";
					echo "</em></p>";
					
					
				} else {
					//just grab the event shit
					
					$query = "SELECT * FROM ng_events WHERE event_id=";
					$query .= $ad['ad_event_id'];
					$query .= " LIMIT 1";
					
					$eventresults = mysql_query($query, $mysamconnec);
					testquery($eventresults);
					
					$event = mysql_fetch_array($eventresults);
					
						echo "<p>" . strtoupper($ad['ad_primary']) . "</p>" ;
						echo "<div class=\"pic\" style=\"background-image:url(" . $event['event_pic_url'] . ")\"> &nbsp; </div>";
						echo "<p>" . strtoupper($event['event_title']) . "</p>" ;
							
							
						echo "<p>" . format_dates($event['event_start'], $event['event_end']) . "<p>";
				}
				
				
				echo "</li>";
				
			?>
			</ul>
			
		</section>
		
		
		</div>
		
		
		<?php build_footer(); ?>
	 </div>
	 
	</body>
</html>
