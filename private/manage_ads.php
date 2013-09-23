<?php
	require_once("../includes/sessions.php");
	require_once("../includes/connection.php");
	require_once("../includes/functions.php");
	require_once("../includes/private_layout_functions.php");
	
	confirm_logged_in();
	confirm_top_level();

	$artist_filter = 0;
	$artist_filter = $_GET['artist_filter'];
	
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
			if ($_FILES['main_upload']['name'] == "") {
				$errormessage .= "Please submit a main image.<br>";
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
			$query  = "INSERT INTO ng_ads ";
			$query .= "(ad_primary, ad_secondary, ad_pr, ad_hyperlink, ad_event_id, ";
			if($uploaded_main) { $query .=" ad_image_url, "; }
			if($uploaded_thumb) { $query .=" ad_thumb_url, "; }
			$query .= " ad_position, ad_direct ) ";
			$query .= "VALUES ('{$primary}', '{$secondary}', '{$pr}', '{$hyperlink}', '{$event_id}', ";
			if($uploaded_main) { $query .=" '{$main_path}', "; }
			if($uploaded_thumb) { $query .=" '{$thumb_path}', "; }
			$query .= " '{$pos}', '{$direct}' ) ";
			
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
		<title>NIGHT GALLERY | Manage Ads</title>
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
			<h3>MANAGE ADS</h3>
		
		</div>
		
		
		<? //BODY OF THE PAGE ?>
		<div class="shelf"></div>
		
		<div id="addartwork">
		<div id ="artworkform">
		
			<p>ADD AD</p>
		
			<?php if(!empty($errormessage)){ echo "<p>" . $errormessage . "</p>"; } ?>
			
			<form action="manage_ads.php" enctype="multipart/form-data" method="post">
			
				<input type="text" placeholder="Title" name="ad_primary" value="<?php if($errors){ echo $_POST['ad_primary']; } ?>" />
				<input type="text" placeholder="Secondary Info (date, tagline)..." name="ad_secondary" value="<?php if($errors){ echo $_POST['ad_secondary']; } ?>" />
				<input type="text" placeholder="http://www..." name="ad_hyperlink" value="<?php if($errors){ echo $_POST['ad_hyperlink']; } ?>" />
				
				<textarea placeholder="Press Release..." name="ad_pr" ></textarea>
				
				<p class="walkthrough">UPLOAD MAIN IMAGE</p>
				<input type="file" name="main_upload" value="15000000">
				<p class="walkthrough">UPLOAD THUMB</p>
				<input type="file" name="thumb_upload" value="15000000">
				
				<p class="walkthrough">Direct to:</p>
				<select name="ad_direct">
					<option value="0">Landing Page</option>
					<option value="1">External Link</option>
				</select>
				
				<p class="walkthrough">OR select a Night Gallery event<br>
				NOTE: If you select an event below, all the other info will not be used.</p>
				<select name="ad_event_id">
					<option value="0">NOT A N.G. EVENT
					<? 	$query = "SELECT * FROM ng_events ORDER BY event_start DESC";
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
				
				
				<input type="submit" value="Add Ad" id="submit" name="adsubmit" alt="1" class=""></p>
			</form>
		
		</div>
		</div>
		
		
		<section class="existingworks" id="inventory">
		
		<h4>EXISTING ADS</h4>
			
			<ul>
			<?php
			
			/* THIS IS WHERE THE ARTWORKS ARE SHOWN ON THIS PAGE. */
				$query = "SELECT * FROM ng_ads ORDER BY ad_position DESC";
	
				$contentset = mysql_query($query, $mysamconnec);
				testquery($contentset);
				$counter = 0;
				while($ad = mysql_fetch_array($contentset)){
					$counter++;
				
					echo "<li ";
					if ($counter <= 4) { echo "class=\"activead\"" ;}
					echo " >";
					
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
					
						if ($counter <= 4) { echo "<p class=\"activetext\"><em>ACTIVE</em></p>" ;}
						echo "<p class=\"positionoptions\"><a href=\"changeorder.php?forward_ad=" . $ad['ad_id'] . " \">Forward</a> | <a href=\"changeorder.php?back_ad=" . $ad['ad_id'] . "\">Back</a></p>";
					
					echo "<p class=\"options\">";
					//echo "<a href=\"edit_ad.php?id=" . $ad['ad_id'] . "\">Edit</a> | ";
					echo "<a href=\"edit_ad.php?id=" . $ad['ad_id'] . "\">Edit</a> | ";
					echo "<a href=\"edit_ad.php?delete=" . $ad['ad_id'] . "\">Delete</a>";
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
