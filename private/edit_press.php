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
		header("Location: manage_press.php");
		exit;
	}
	$selected = " selected = \"selected\" ";
	
	//sees if this has just been submitted to itself
	if (isset($_POST['updatepressitem'])){
		
		$title = $_POST['pressitem_title'];
		$date = $_POST['pressitem_date'];
		$outlet = $_POST['pressitem_outlet'];
		$writer = $_POST['pressitem_writer'];
		$start_month = $_POST['start_month'];
		$start_date = $_POST['start_date'];
		$start_year = $_POST['start_year'];
		
		
		$sql_start_date = $start_year . "-" . $start_month . "-" . $start_date . " 12:00:00" ;
		
		$timestamp = strtotime($sql_start_date);
		print $timestamp . " = " . strftime("%m %d %Y", $timestamp);
		
		
		if(!empty($errormessage)){
			$errors = true;
		}
		
		if (empty($errormessage)) {
		
			if($_FILES['pressitem_upload']['name'] == "") {
				// No file was selected for upload
				} else {
				$tmp_file = $_FILES['pressitem_upload']['tmp_name'];
				//$target_file = basename($_FILES['file_upload']['name']);
				//basename() gets just the name of the file, not the whole file directory.
				$target_file = prep_string_for_filename($_POST['pressitem_title'] . "profile" . time() );
				$upload_dir = "../assets/img";
				if(move_uploaded_file($tmp_file, $upload_dir . '/' . $target_file)) {
					$message .= "Uploaded Photo. ";
					$updated_pressitem_pdf = true;
				} else {
					$message .= "Image Upload Failed. ";
				}
				$final_path1 = "http://www.miekemarple.com/nightgallery/assets/img" . "/" . $target_file;
			}
			
			//DO THE QUERY
			$query = "UPDATE ng_gpress ";
			$query .= "SET ";
			$query .= "gpress_title = '{$title}', ";
			$query .= "gpress_date = '{$timestamp}', ";
			$query .= "gpress_outlet = '{$outlet}', ";
			$query .= "gpress_writer = '{$writer}'";
			if($updated_pressitem_pdf) { $query .= ", gpress_url = '{$pressitem_path}' "; }
			$query .= " WHERE gpress_id = {$id}";
			
			
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
	
	$query = "SELECT * FROM ng_gpress WHERE gpress_id =";
	$query .= $id;
	$query .= " LIMIT 1";
	$contentset = mysql_query($query, $mysamconnec);
	testquery($contentset);
	$thisitem = mysql_fetch_array($contentset);
	
	
	
	$past_year = '2010';
	$time = time();
	$start_year = strftime("%Y", $time);
	$future_year = $start_year + 2;
	
	$eventstart = $thisitem['gpress_date'];
	
	if (!preg_match('/^[0-9]*$/', $eventstart)) {
        $eventstart = strtotime($thisitem['gpress_date']);
    }
	
	$start_month = strftime("%m", $eventstart);
	$start_date = strftime("%d", $eventstart);
	$start_year = strftime("%Y", $eventstart);
	
?>

<html>
	<head>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans+Mono' rel='stylesheet' type='text/css'>
		<title>NIGHT GALLERY | Edit Press Item</title>
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
			<h3>UPDATE <?php echo "\"" . strtoupper( $thisitem['gpress_title'] . "\"" ); ?></h3>
		</div>
		
		
		<? //BODY OF THE PAGE ?>
		<div class="shelf"></div>
		
		<div id="addartwork">
		<div id ="artworkform">

			<p>UPDATE ITEM</p>

			<?php if(!empty($errormessage)){ echo "<p>" . $errormessage . "</p>"; } ?>
			<?php if(!empty($errormessage)){ echo "<p>" . $message . "</p>"; } ?>
			
			<form action="edit_press.php?id=<?php echo $id; ?>" enctype="multipart/form-data" method="post">	
				
				
				<input type="text" placeholder="Title" name="pressitem_title" value="<?php echo $thisitem['gpress_title']  ?>">
					<!-- <input type="text" placeholder="Date" name="pressitem_date" value="<?php echo $thisitem['gpress_date']  ?>"> -->
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
					
					
					<input type="text" placeholder="Publication" name="pressitem_outlet" value="<?php echo $thisitem['gpress_outlet']  ?>">
					<input type="text" placeholder="Writer" name="pressitem_writer" value="<?php echo $thisitem['gpress_writer']  ?>">
					
					<p class="walkthrough">Upload PDF or IMAGE...</p>
					
					<input type="file" name="pressitem_upload" value="15000000">
					
					<input type="submit" value="Update Press Item" id="addpressitem" name="updatepressitem" alt="1" class="">
				
				
				
			</form>
			
			<?php echo "<a href=\"manage_press.php?delete=" . $id . "\" onclick=\"return confirm('Are you sure you want to delete?')\" >Delete News Item</a>"; ?>
			
		</div>
		</div>
		
		
		
		
		
		
		
		<section class="existingworks" id="inventory">
		
		<h4><a href="manage_press.php">< BACK TO MANAGE NEWS</a></h4>
			
			<ul>
			<?php
			
			/* THIS IS WHERE THE ARTIST WHO IS CURRENTLY BEING EDITED IS SHOWN. */
				$query = "SELECT * FROM ng_gpress ";
				$query .= "WHERE gpress_id=" . $id;
				
				$contentset = mysql_query($query, $mysamconnec);
				testquery($contentset);
				
				while($content = mysql_fetch_array($contentset)){
				
					echo "<li>";
					
					echo "<div class=\"pic\" style=\"background-image:url(" . ngconvert( $content['gpress_url'] ) . ")\"> &nbsp; </div>";
					//echo "<p><a href=\"". ngconvert( $content['gpress_url'] ) . "\" target=\"_blank\">GO</a></p>";
					echo "<p>" . $content['gpress_title'] . " (" . strftime("%B %e, %G", $content['gpress_date']) . ")<p>";
					echo "<p>" . $content['gpress_outlet'] . "</p><p>" . $content['gpress_writer'] . "<p>";
					
					echo "<p class=\"options\">";
					echo "<a href=\"edit_press.php?id=" . $content['gpress_id'] . "\">Edit</a> | ";
					echo "<a href=\"manage_press.php?delete=" . $content['gpress_id'] . "&return=" . $id . "\">Delete</a></p>";
					
					
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