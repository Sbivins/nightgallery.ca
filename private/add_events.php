<?php
	require_once("../includes/sessions.php");
	require_once("../includes/connection.php");
	require_once("../includes/functions.php");
	require_once("../includes/private_layout_functions.php");
	
	confirm_logged_in();
	confirm_top_level();

	
	$filter_by_type = $_GET['filter_by_type'];
	$selected = " selected = \"selected\" ";
	//sees if this has just been submitted to itself
	
	
	$time = time();
	$start_month = strftime("%m", $time);
	$start_date = strftime("%d", $time);
	$start_year = strftime("%Y", $time);
	//$future_year = $start_year + 2;
	
	$past_year = '2010';
	$this_year = strftime("%Y", $time);
	$future_year = $start_year + 2;
	
	$end_month = $start_month;
	$end_date = sprintf("%02u", ($start_date + 1));
	$end_year = $start_year;
	
	$sql_start_date = $start_year . "-" . $start_month . "-" . $start_date . " " . "00:00:00" ;
	$sql_end_date = $end_year . "-" . $end_month . "-" . $end_date . " " . "00:00:00" ;
	
	if (isset($_POST['addsubmit'])){
		
		
		$start_month = $_POST['start_month'];
		$start_date = $_POST['start_date'];
		$start_year = $_POST['start_year'];
		
		$end_month = $_POST['end_month'];
		$end_date = $_POST['end_date'];
		$end_year = $_POST['end_year'];
		
		$sql_start_date = $start_year . "-" . $start_month . "-" . $start_date . " " . "00:00:00" ;
		$sql_end_date = $end_year . "-" . $end_month . "-" . $end_date . " " . "00:00:00" ;
		
		$title = $_POST['event_title'];
		$type = $_POST['event_type'];
		
		//$subtext = $_POST['event_subtext'];
		
		/*
		2012-05-16 17:55:24
		*/
		
		//print_r($_FILES);
		
		if (empty($_POST['event_title'])) {
			$errormessage .= "Please enter an event title.<br>";
		}
		if ($_POST['event_type'] == 0) {
			$errormessage .= "Please select an event type.";
		}
		
		if(!empty($errormessage)){
			$errors = true;
		}
		
		if (empty($errormessage)) {
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
			
			
			
			//DO THE QUERY
			$query  = "INSERT INTO ng_events ";
			$query .= "(
					event_title ,
					event_start ,
					event_end ,
					event_pic_url ,
					event_type ) ";
			$query .= "VALUES ('{$title}', '{$sql_start_date}', '{$sql_end_date}', '{$final_path}', '{$type}') ";
			$result = mysql_query($query, $mysamconnec);
			
			if (mysql_affected_rows()==1){
				header('Location: edit_event.php?continue=yes');
				
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
		<title>NIGHT GALLERY | Manage Events</title>
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
			<h3>EVENTS</h3>
			
			<form action="add_events.php" method="get">
				
				SHOWING: 
				<select name='filter_by_type' onchange='this.form.submit()'>
				
					<option value="0" <?php if($filter_by_type == 0) {echo $selected;} ?>>All Events...
					<option value="1" <?php if($filter_by_type == 1) {echo $selected;} ?>>Night Gallery Exhibitions
					<option value="2" <?php if($filter_by_type == 2) {echo $selected;} ?>>One-Night Gallery Events
					<option value="3" <?php if($filter_by_type == 3) {echo $selected;} ?>>Outside Events
					<option value="4" <?php if($filter_by_type == 4) {echo $selected;} ?>>Art Fairs
					
				</select>
			</form>
		</div>
		
		
		<? //BODY OF THE PAGE ?>
		<div class="shelf"></div>
		
		<div id="addevent">
		<div id ="eventform">
		
			<p>ADD EVENT</p>
		
			<?php if(!empty($errormessage)){ echo "<p>" . $errormessage . "</p>"; } ?>
			
			<form action="add_events.php" enctype="multipart/form-data" method="post">
			
			<p class="walkthrough">NOTE: Enter basic info here, and add artists, images and a press release on the next page.</p>

				<input type="text" placeholder="Title" name="event_title" value="<?php if($errors){ echo $_POST['event_title']; } ?>" />
								
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
						for($i = 2010; $i <= $future_year; $i++) {
						echo "<option value=\"" . $i . "\" ";
						if($end_year == $i) { echo $selected; } 
						echo ">" . $i . "</option>";
						} 
					?>
					</select>				
					
				</div>
			
			</p>
			
			
				<select name='event_type'>
					<option value="0" >Choose A Type...
					<option value="1" >Night Gallery Exhibition
					<option value="2" >One-Night Gallery Event
					<option value="3" >Outside Event
					<option value="4" >Art Fair
				</select>
				
				<p class="walkthrough">Upload main photo...</p>
				
				<input type="file" name="event_pic" value="15000000">
				
				<input type="submit" value="Continue..." id="submit" name="addsubmit" alt="1" class=""></p>
			</form>
		
		</div>
		</div>
		
		
		<section class="existingworks existing_events" id="inventory">
		
		<h4>EXISTING EVENTS</h4>
			
			<ul>
			<?php
			
			/* THIS IS WHERE THE ARTWORKS ARE SHOWN ON THIS PAGE. */
				$query = "SELECT * FROM ng_events ";
				if($filter_by_type != 0) { $query .= "WHERE event_type=" . $filter_by_type ; }
				$query .= " ORDER BY event_insert_time DESC";
				
				$contentset = mysql_query($query, $mysamconnec);
				testquery($contentset);
				
				while($content = mysql_fetch_array($contentset)){
				
					echo "<li>";
					
					echo "<div class=\"pic\" style=\"background-image:url(" . $content['event_pic_url'] . ")\"> &nbsp; </div>";
					echo "<p>" . strtoupper($content['event_title']) . "</p>" ;
						
						
						
					echo "<p>" . format_dates($content['event_start'], $content['event_end']) . "<p>";
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
					
					
					$query = "SELECT * FROM ng_event_shots ";
					$query .= "WHERE event_shot_event=" . $content['event_id'];
					$shotscontentset = mysql_query($query, $mysamconnec);
					testquery($shotscontentset);
						$shotcount = 0;
						while($shot = mysql_fetch_array($shotscontentset)){
							$shotcount++;
						}
					echo "<p>" . $shotcount . " Event Shots | <a href=\"edit_event.php?id=" . $content['event_id'] . "\">+ Add Shots</a></p>";
					
										
					echo "<p class=\"options\"><a href=\"edit_event.php?id=" . $content['event_id'] . "\">Edit</a> | <a href=\"delete_event.php?id=" . $content['event_id'] . "\" onclick=\"return confirm('Are you sure you want to delete?')\" >Delete</a></p>";
					
					
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