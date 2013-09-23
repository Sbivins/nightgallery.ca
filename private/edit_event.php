<?php
	require_once("../includes/sessions.php");
	require_once("../includes/connection.php");
	require_once("../includes/functions.php");
	require_once("../includes/private_layout_functions.php");
	
	confirm_logged_in();
	confirm_top_level();
	
	
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$continue = false;
	} else if (isset($_GET['continue'])) {
		$continue = true;
	} else {
		header("Location: add_events.php");
		exit;
	}
	
	if (isset($_POST['submit'])){
		
		$start_month = $_POST['start_month'];
		$start_date = $_POST['start_date'];
		$start_year = $_POST['start_year'];
		$start_time = $_POST['start_time'];
		
		$end_month = $_POST['end_month'];
		$end_date = $_POST['end_date'];
		$end_year = $_POST['end_year'];
		$end_time = $_POST['end_time'];
	
		$title = $_POST['event_title'];
		$sql_start_date = $start_year . "-" . $start_month . "-" . $start_date . " " . $start_time . ":00:00" ;
		$sql_end_date = $end_year . "-" . $end_month . "-" . $end_date . " " . $end_time . ":00:00" ;
		//echo $sql_start_date . "<br>";
		//echo $sql_end_date;
		$pr = htmlentities( $_POST['event_pr'] );
		$subtext = htmlentities( $_POST['event_subtext'] );
		$event_location = $_POST['event_location'];
		$event_space = $_POST['event_space'];
		$url = $_POST['event_url'];
		$type = $_POST['event_type'];
		$artist1 = $_POST['event_artist1'];
		$artist2 = $_POST['event_artist2'];
		$artist3 = $_POST['event_artist3'];
		$artist4 = $_POST['event_artist4'];
		$artist5 = $_POST['event_artist5'];
		$artist6 = $_POST['event_artist6'];
		$artist7 = $_POST['event_artist7'];
		$artist8 = $_POST['event_artist8'];
		$artist9 = $_POST['event_artist9'];
		$artist10 = $_POST['event_artist10'];
		$artist11 = $_POST['event_artist11'];
		$artist12 = $_POST['event_artist12'];
		$artist13 = $_POST['event_artist13'];
		$artist14 = $_POST['event_artist14'];
		$artist15 = $_POST['event_artist15'];
		
		
		if (empty($_POST['event_title'])) {
			$errormessage .= "Please enter an event title.<br>";
		}
		
		if ($_POST['event_type'] == 10) {
			$errormessage .= "Please select an event type.";
		}
		
		if(!empty($errormessage)){
			$errors = true;
		}
		
		if (empty($errormessage)) {
		
			if($_FILES['event_pic']['name'] == "") {
				// No file was selected for upload
				} else {
				$imageuploaded = true;
				$tmp_file = $_FILES['event_pic']['tmp_name'];
				//$target_file = basename($_FILES['file_upload']['name']); //basename() gets just the name of the file, not the whole file directory.
				$target_file = prep_string_for_filename($_POST['event_title'] . time());
				$upload_dir = "../assets/img";
				if(move_uploaded_file($tmp_file, $upload_dir . '/' . $target_file)) {
					$message .= "Image Upload Was Successful. ";
				} else {
					$message .= "Image Upload Failed. ";
				}
				$final_path = "http://www.miekemarple.com/nightgallery/assets/img" . "/" . $target_file;
				}
				
				
			if($_FILES['pressitem_upload']['name'] == "") {
				// No file was selected for upload
				} else {
				//$imageuploaded = true;
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
			$query  = "UPDATE ng_events ";
			$query .= 	"SET  event_title =  '{$title}',
						event_start =  '{$sql_start_date}',
						event_end =  '{$sql_end_date}',
						event_pr =  '{$pr}',
						event_subtext =  '{$subtext}', ";
						
			if($imageuploaded) { 
			$query .= "event_pic_url = '{$final_path}', "; 
				}
			if($uploaded_pressitem) { 
			$query .= "event_pr_url = '{$pressitem_path}', "; 
				}
			$query .= 	" event_location =  '{$event_location}',
						event_space =  '{$event_space}',
						event_url =  '{$url}',
						event_type = '{$type}',
						event_artist1 =  '{$artist1}',
						event_artist2 =  '{$artist2}',
						event_artist3 =  '{$artist3}',
						event_artist4 =  '{$artist4}',
						event_artist5 =  '{$artist5}',
						event_artist6 =  '{$artist6}',
						event_artist7 =  '{$artist7}',
						event_artist8 =  '{$artist8}',
						event_artist9 =  '{$artist9}',
						event_artist10 =  '{$artist10}',
						event_artist11 =  '{$artist11}',
						event_artist12 =  '{$artist12}',
						event_artist13 =  '{$artist13}',
						event_artist14 =  '{$artist14}',
						event_artist15 =  '{$artist15}'
						WHERE event_id= '{$id}' LIMIT 1";
						
			$result = mysql_query($query, $mysamconnec);
			
			if (mysql_affected_rows()==1){
				$message .= "Update Succeeded";
				
			} else {
				$message .= "Update failed";
				echo mysql_error();
			}
		}	
			
	} else {
		//do nothing
	}
	
	
	if ($continue) {
		//if continue .... grab the most recently added event and $id becomes that, then it moves onto a normal EDIT page.
		$query = "SELECT * FROM ng_events ";
		$query .= "ORDER BY event_insert_time DESC LIMIT 1";
		$contentset = mysql_query($query, $mysamconnec);
		testquery($contentset);
		$thisevent = mysql_fetch_array($contentset);
		$id = $thisevent['event_id'];
		
	} else {
		//else .... query the event with the GET'd id...
		$query = "SELECT * FROM ng_events ";
		$query .= "WHERE event_id = ";
		$query .= $id;
		$query .= " LIMIT 1";
		$contentset = mysql_query($query, $mysamconnec);
		testquery($contentset);
		$thisevent = mysql_fetch_array($contentset);
		
	}
	
	$selected = " selected = \"selected\" ";
	
	
	$past_year = '2010';
	$time = time();
	$start_year = strftime("%Y", $time);
	$future_year = $start_year + 2;
	
	$eventstart = strtotime($thisevent['event_start']);
	$eventend = strtotime($thisevent['event_end']);
		
	$start_time = $end_time = 00;	
		
	$start_month = strftime("%m", $eventstart);
	$start_date = strftime("%d", $eventstart);
	$start_year = strftime("%Y", $eventstart);
	$start_time = strftime("%H", $eventstart);
	
	$end_month = strftime("%m", $eventend);
	$end_date = strftime("%d", $eventend);
	$end_year = strftime("%Y", $eventend);
	$end_time = strftime("%H", $eventend);
	
	
	
	if (isset($_POST['addeventshot'])){
		
		$event_shot_caption = $_POST['event_shot_caption'];
		$event_video = $_POST['event_video'];
		
		
		if($_FILES['event_shot']['name'] == "" && $_POST['event_video'] == "") {
				$shoterrors .= "Either upload a photo or enter a youtube code. ";
		}
			
		if(empty($shoterrors)){
			$tmp_file = $_FILES['event_shot']['tmp_name'];
			//$target_file = basename($_FILES['file_upload']['name']); //basename() gets just the name of the file, not the whole file directory.
			$target_file = prep_string_for_filename($_POST['event_title'] . time());
			$upload_dir = "../assets/img";
			if(move_uploaded_file($tmp_file, $upload_dir . '/' . $target_file)) {
				$shotmessage .= "Image Upload Was Successful. ";
			} else {
				$shotmessage .= "Image Upload Failed. ";
			}
			$final_shot_path = "http://www.miekemarple.com/nightgallery/assets/img" . "/" . $target_file;
			}
			
			//DO THE QUERY					
			$query  = "INSERT INTO ng_event_shots ";
			$query .= "(
					event_shot_url ,
					event_shot_event ,
					event_shot_caption, event_shot_videolink ) ";
			$query .= "VALUES ('{$final_shot_path}', '{$id}', '{$event_shot_caption}', '{$event_video}') ";
						
			$result = mysql_query($query, $mysamconnec);
			
			if (mysql_affected_rows()==1){
				$shotmessage .= "Update Succeeded";
			} else {
				$shotmessage .= "Update failed";
				die(mysql_error());
			}
			
			
	} else {
		//do nothing
	}
	
	
	
	
	if (isset($_GET['deleteshot'])) {
		
		$deleteshotid = mysql_prep( $_GET['deleteshot'] );
		
		$query = "DELETE FROM ng_event_shots WHERE event_shot_id = '$deleteshotid'";
		$content = mysql_query($query, $mysamconnec);
		testquery($content);
		
		if(mysql_affected_rows()==1){
			$shotmessage .= "Shot deleted!";
		} else {
			$shotmessage .= "Shot not deleted.";
		}
		
	}
	

	
	
	
	
	
	
	
	
	
?>
<html>
	<head>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans+Mono' rel='stylesheet' type='text/css'>
		<title>Night Gallery | Edit Event</title>
		<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/reset/reset-min.css">
		      <link href="styles/style.css" rel="stylesheet" type="text/css">
		      
		      
	</head>
	<body class="crud1 admin">
	
	<div class="wid">
		
		<div class="header">
			<div id="userbar" ><p class="user<?php echo $level;?>"><?php echo $_SESSION['username']; ?>
				
					<?php require_once("../includes/userbar.php");?>
				
				</div>
			
			<?php build_admin_nav(); ?>
		</div>
	
	
	
	<div class="content">
		
		<div id="pagehead">
			<h3>EDIT EVENT</h3>
		</div>
		
		
		<? //BODY OF THE PAGE ?>
		<div class="shelf"></div>
		
		<div id="addevent">
		<div class="form">
		
			<p>EDIT BASIC INFO</p>
		
			<?php if(!empty($errormessage)){ echo "<p>" . $errormessage . "</p>"; } ?>
			
			<form action="edit_event.php?id=<?php echo $id; ?>" enctype="multipart/form-data" method="post">

				<input type="text" placeholder="Title" name="event_title" value="<?php echo $thisevent['event_title']; ?>" />
				
				
			
				<p class="walkthrough">Enter Secondary Text...</p>

				<textarea placeholder="Beautiful Young Men, etc." name="event_subtext" class="secondarytext" ><?php echo  $thisevent['event_subtext'] ; ?></textarea>
				
				
				
				
				
				
				
				
				<p class="walkthrough">START DATE:</p>
				
				<div class="dateselect">
					<select name="start_month">
						<option value="01" <?php if($start_month == 01) { echo $selected; } ?>>JAN</option>
						<option value="02" <?php if($start_month == 02) { echo $selected; } ?>>FEB</option>
						<option value="03" <?php if($start_month == 03) { echo $selected; } ?>>MAR</option>
						<option value="04" <?php if($start_month == 04) { echo $selected; } ?>>APR</option>
						<option value="05" <?php if($start_month == 05) { echo $selected; } ?>>MAY</option>
						<option value="06" <?php if($start_month == 06) { echo $selected; } ?>>JUN</option>
						<option value="07" <?php if($start_month == 07) { echo $selected; } ?>>JUL</option>
						<option value="08" <?php if($start_month == '08') { echo $selected; } ?>>AUG</option>
						<option value="09" <?php if($start_month == '09') { echo $selected; } ?>>SEPT</option>
						<option value="10" <?php if($start_month == 10) { echo $selected; } ?>>OCT</option>
						<option value="11" <?php if($start_month == 11) { echo $selected; } ?>>NOV</option>
						<option value="12" <?php if($start_month == 12) { echo $selected; } ?>>DEC</option>
					</select>
					
					<select name="start_date">
					<?php 
					for($i = 01; $i <= 31; $i++) {
						echo "<option value=\"" . sprintf("%02u", $i) . "\" "; 
						if($start_date == $i) { echo $selected; } 
						echo ">" . sprintf("%02u", $i) . "</option>";
					}
					
					?>
					</select>
				
					
					<select name="start_year">
					<?php
						for($i = 2010; $i <= $future_year; $i++) {
						echo "<option value=\"" . $i . "\" ";
						if($start_year == $i) { echo $selected; } 
						echo ">" . $i . "</option>";
						} 
					?>
					</select>
					
					<select name="start_time">
						<option value="00" <?php if($start_time == 00) { echo $selected; } ?>>...</option>
						<option value="01" <?php if($start_time == 01) { echo $selected; } ?>>1 AM</option>
						<option value="02" <?php if($start_time == 02) { echo $selected; } ?>>2 AM</option>
						<option value="03" <?php if($start_time == 03) { echo $selected; } ?>>3 AM</option>
						<option value="04" <?php if($start_time == 04) { echo $selected; } ?>>4 AM</option>
						<option value="05" <?php if($start_time == 05) { echo $selected; } ?>>5 AM</option>
						<option value="06" <?php if($start_time == 06) { echo $selected; } ?>>6 AM</option>
						<option value="07" <?php if($start_time == 07) { echo $selected; } ?>>7 AM</option>
						<option value="08" <?php if($start_time == '08') { echo $selected; } ?>>8 AM</option>
						<option value="09" <?php if($start_time == '09') { echo $selected; } ?>>9 AM</option>
						<option value="10" <?php if($start_time == 10) { echo $selected; } ?>>10 AM</option>
						<option value="11" <?php if($start_time == 11) { echo $selected; } ?>>11 AM</option>
						<option value="12" <?php if($start_time == 12) { echo $selected; } ?>>NOON</option>
						<option value="13" <?php if($start_time == 13) { echo $selected; } ?>>1 PM</option>
						<option value="14" <?php if($start_time == 14) { echo $selected; } ?>>2 PM</option>
						<option value="15" <?php if($start_time == 15) { echo $selected; } ?>>3 PM</option>
						<option value="16" <?php if($start_time == 16) { echo $selected; } ?>>4 PM</option>
						<option value="17" <?php if($start_time == 17) { echo $selected; } ?>>5 PM</option>
						<option value="18" <?php if($start_time == 18) { echo $selected; } ?>>6 PM</option>
						<option value="19" <?php if($start_time == 19) { echo $selected; } ?>>7 PM</option>
						<option value="20" <?php if($start_time == 20) { echo $selected; } ?>>8 PM</option>
						<option value="21" <?php if($start_time == 21) { echo $selected; } ?>>9 PM</option>
						<option value="22" <?php if($start_time == 22) { echo $selected; } ?>>10 PM</option>
						<option value="23" <?php if($start_time == 23) { echo $selected; } ?>>11 PM</option>
					</select>
					
					
				</div>
				
				
			
		
				<p class="walkthrough">END DATE:</p>
				
				<div class="dateselect">
					<select name="end_month">
						<option value="01" <?php if($end_month == 01) { echo $selected; } ?>>JAN</option>
						<option value="02" <?php if($end_month == 02) { echo $selected; } ?>>FEB</option>
						<option value="03" <?php if($end_month == 03) { echo $selected; } ?>>MAR</option>
						<option value="04" <?php if($end_month == 04) { echo $selected; } ?>>APR</option>
						<option value="05" <?php if($end_month == 05) { echo $selected; } ?>>MAY</option>
						<option value="06" <?php if($end_month == 06) { echo $selected; } ?>>JUN</option>
						<option value="07" <?php if($end_month == 07) { echo $selected; } ?>>JUL</option>
						<option value="08" <?php if($end_month == '08') { echo $selected; } ?>>AUG</option>
						<option value="09" <?php if($end_month == '09') { echo $selected; } ?>>SEPT</option>
						<option value="10" <?php if($end_month == 10) { echo $selected; } ?>>OCT</option>
						<option value="11" <?php if($end_month == 11) { echo $selected; } ?>>NOV</option>
						<option value="12" <?php if($end_month == 12) { echo $selected; } ?>>DEC</option>
					</select>
					
					<select name="end_date">
					<?php 
					for($i = 01; $i <= 31; $i++) {
						echo "<option value=\"" . sprintf("%02u", $i) . "\" "; 
						if($end_date == $i) { echo $selected; } 
						echo ">" . sprintf("%02u", $i) . "</option>";
					}
					
					?>
					</select>
					
					<select name="end_year">
					<?php
						for($i = $past_year; $i <= $future_year; $i++) {
						echo "<option value=\"" . $i . "\" ";
						if($end_year == $i) { echo $selected; } 
						echo ">" . $i . "</option>";
						} 
					?>
					</select>	
					
					<select name="end_time">
						<option value="00" <?php if($end_time == 00) { echo $selected; } ?>>...</option>
						<option value="01" <?php if($end_time == 01) { echo $selected; } ?>>1 AM</option>
						<option value="02" <?php if($end_time == 02) { echo $selected; } ?>>2 AM</option>
						<option value="03" <?php if($end_time == 03) { echo $selected; } ?>>3 AM</option>
						<option value="04" <?php if($end_time == 04) { echo $selected; } ?>>4 AM</option>
						<option value="05" <?php if($end_time == 05) { echo $selected; } ?>>5 AM</option>
						<option value="06" <?php if($end_time == 06) { echo $selected; } ?>>6 AM</option>
						<option value="07" <?php if($end_time == 07) { echo $selected; } ?>>7 AM</option>
						<option value="08" <?php if($end_time == '08') { echo $selected; } ?>>8 AM</option>
						<option value="09" <?php if($end_time == '09') { echo $selected; } ?>>9 AM</option>
						<option value="10" <?php if($end_time == 10) { echo $selected; } ?>>10 AM</option>
						<option value="11" <?php if($end_time == 11) { echo $selected; } ?>>11 AM</option>
						<option value="12" <?php if($end_time == 12) { echo $selected; } ?>>NOON</option>
						<option value="13" <?php if($end_time == 13) { echo $selected; } ?>>1 PM</option>
						<option value="14" <?php if($end_time == 14) { echo $selected; } ?>>2 PM</option>
						<option value="15" <?php if($end_time == 15) { echo $selected; } ?>>3 PM</option>
						<option value="16" <?php if($end_time == 16) { echo $selected; } ?>>4 PM</option>
						<option value="17" <?php if($end_time == 17) { echo $selected; } ?>>5 PM</option>
						<option value="18" <?php if($end_time == 18) { echo $selected; } ?>>6 PM</option>
						<option value="19" <?php if($end_time == 19) { echo $selected; } ?>>7 PM</option>
						<option value="20" <?php if($end_time == 20) { echo $selected; } ?>>8 PM</option>
						<option value="21" <?php if($end_time == 21) { echo $selected; } ?>>9 PM</option>
						<option value="22" <?php if($end_time == 22) { echo $selected; } ?>>10 PM</option>
						<option value="23" <?php if($end_time == 23) { echo $selected; } ?>>11 PM</option>
					</select>			
					
				</div>
			
			</p>
			
			
				<select name='event_type'>
					<option value="1" <?php if ($thisevent['event_type'] == 1) { echo $selected; }?> >Night Gallery Exhibition
					<option value="2" <?php if ($thisevent['event_type'] == 2) { echo $selected; }?> >One-Night Gallery Event
					<option value="3" <?php if ($thisevent['event_type'] == 3) { echo $selected; }?> >Outside Event
					<option value="4" <?php if ($thisevent['event_type'] == 4) { echo $selected; }?> >Art Fair
				</select>
				
				<p class="walkthrough">Upload main photo...</p>
				
				<input type="file" name="event_pic" value="15000000">
				
				<p class="walkthrough">Upload PDF of Press Release</p>
					
				<input type="file" name="pressitem_upload" value="15000000">
		
		 </div>
		</div>
		
		
		
		<div id="addevent">
		<div class="form" id="addartiststoevent">
		
			<p>EDIT EVENT ARTISTS</p>
			
			<p class="walkthrough">Enter any number of artists that are in this show:</p>

			
			<?php
				for ($i = 1; $i <= 10; $i++) {
				echo "<select name=\"event_artist";
				echo $i;
				echo "\">";
					echo "<option value=\"0\">Select an artist...";
					$query = "SELECT * FROM ng_artists ORDER BY artist_last_name ASC";
						$contentset = mysql_query($query, $mysamconnec);
						testquery($contentset);
						while($artist = mysql_fetch_array($contentset)){
						
							echo "<option value=" .  $artist['artist_id'] . " ";
							
							if($thisevent['event_artist' . $i] > 0) {
								if ($thisevent['event_artist' . $i] == $artist['artist_id']) {
									echo $selected;
								}
							}
							echo ">" . $j;
							echo $artist['artist_last_name'] . ", " . $artist['artist_first_name'];
							echo "</option>";
						}
					
				echo "</select>";
				}
				?>
				
				<textarea placeholder="Other PR..." name="event_pr" class="secondarytext" ><?php echo $thisevent['event_pr'] ; ?></textarea>
				
				<input type="submit" value="Update Event" id="submit" name="submit" alt="1" class=""></p>
				
				</form>
				
				<?php echo "<a href=\"delete_event.php?id=" . $id . "\" onclick=\"return confirm('Are you sure you want to delete?')\" >Delete Event</a>"; ?>
		
		 </div>
		</div>
		
		
		

		
		<section class="existingworks" id="inventory">
		
		<h4><a href="add_events.php">< BACK TO MANAGE EVENTS</a></h4>
			
			<ul>
			<?php
			
			/* THIS IS WHERE THE ARTWORKS ARE SHOWN ON THIS PAGE. */
				$query = "SELECT * FROM ng_events WHERE event_id=";
				$query .= $id;
				$query .= " LIMIT 1";
				
				$contentset = mysql_query($query, $mysamconnec);
				testquery($contentset);
				
				while($content = mysql_fetch_array($contentset)){
				
					echo "<li>";
					
					echo "<div class=\"pic\" style=\"background-image:url(" . $content['event_pic_url'] . ")\"> &nbsp; </div>";
					echo "<p>" . strtoupper($content['event_title']) . "</p>" ;
					echo "<p>" . format_dates($content['event_start'], $content['event_end']) . "<p>";
					echo "<p><a href=\"" . $content['event_pr_url'] . "\">PRESS RELEASE</a></p>";
					echo "<p><em>";
					if($content['event_type'] == 1) {
						echo "Night Gallery Exhibition";
					} else if ($content['event_type'] == 2) {
						echo "One Night Gallery Event";
					} else if ($content['event_type'] == 3) {
						echo "Outside Event";
					} else {
						echo "Art Fair";
					}
					echo "</em></p>";
					
					
					echo "</li>";
				}
				
			?>
			</ul>
			
		</section>
		
		<section class="existingworks clear" id="event_shots">
		
		<h4>EVENT SHOTS</h4>
		
			<ul>
			
			<li class="form">
				<p>ADD EVENT SHOT</p>
				<form action="edit_event.php?id=<?php echo $id; ?>" enctype="multipart/form-data" method="post">
				
				<p class="walkthrough">Upload image...</p>
				
				<input type="file" name="event_shot" value="15000000">
				
				<p class="walkthrough">OR</p>
				
				<input type="text" placeholder="YouTube Embed Code" name="event_video">
				
				<input type="text" placeholder="Caption (optional)" name="event_shot_caption">
			
				<input type="submit" value="Add Shot" id="submit" name="addeventshot" alt="1" class=""></p>
			</form>
			
			</li>
			
			
			
			
			
			<?php
			
			/* THEN IT SHOWS THEIR PRESS ITEMS. */
				$query = "SELECT * FROM ng_event_shots ";
				$query .= "WHERE event_shot_event=" . $id;
				$query .= " ORDER BY event_shot_order_pos DESC";
				
				$contentset = mysql_query($query, $mysamconnec);
				testquery($contentset);
				
				while($content = mysql_fetch_array($contentset)){
				
					echo "<li>";
					
					
					if($content['event_shot_videolink']){
						echo "<div class=\"vid\"> " . embed_from_youtube($content['event_shot_videolink']) . " </div>";
					} else {
						echo "<div class=\"pic\" style=\"background-image:url(" . $content['event_shot_url'] . ")\"> &nbsp; </div>";
					}
					
					echo "<p>" . $content['event_shot_caption'] . "<p>";
					
					echo "<p class=\"positionoptions\"><a href=\"changeorder.php?forward_eventshot=" . $content['event_shot_id'] . "&return=" . $id . " \">Forward</a> | <a href=\"changeorder.php?back_eventshot=" . $content['event_shot_id'] . "&return=" . $id . " \">Back</a></p>";
					
					echo "<p class=\"options\"><a href=\"edit_eventshot.php?id=" . $content['event_shot_id'] ."\">Edit</a> | <a href=\"edit_event.php?id=" . $id . "&deleteshot=" . $content['event_shot_id'] . "\">Delete</a></p>";
					
					
					
					echo "</li>";
				}
				
			?>
			
			</ul>
		</section>
		
		
		</div>
		
		
		<?php build_footer(); ?>
	 </div>
	 
	</body>
<?php 
	
	/*
<select name="event_artist1">
					<option value="0">Select an artist...
					<? 	$query = "SELECT * FROM ng_artists ORDER BY artist_last_name ASC";
						$contentset = mysql_query($query, $mysamconnec);
						testquery($contentset);
						
						while($content = mysql_fetch_array($contentset)){
						
							echo "<option value=" .  $content['artist_id'] . " ";
							if($errors){
								if ($_POST['artwork_artist'] == $content['artist_id']) {
									echo "selected = \"selected\"";
								}
							}
							if($artist_filter > 0) {
								if ($artist_filter == $content['artist_id']) {
									echo "selected = \"selected\"";
								}
							}
							echo ">";
							echo strtoupper($content['artist_first_name']. " " . $content['artist_last_name']);
							echo "</option>";
						}
					?>
				</select>
*/
	
	
?>

</html>			