<?php
	require_once("../includes/sessions.php");
	require_once("../includes/connection.php");
	require_once("../includes/functions.php");
	require_once("../includes/private_layout_functions.php");
	
	confirm_logged_in();
	confirm_top_level();
	
	
	//sees if this has just been submitted to itself
	if (isset($_POST['submit'])){
		
		$field = htmlentities( $_POST['info_field']);
		
		$infolink1 = $_POST['info_link_1'];
		$infourl1 = $_POST['info_url_1'];
		
		$infolink2 = $_POST['info_link_2'];
		$infourl2 = $_POST['info_url_2'];
		
		$infolink3 = $_POST['info_link_3'];
		$infourl3 = $_POST['info_url_3'];
		
		$infolink4 = $_POST['info_link_4'];
		$infourl4 = $_POST['info_url_4'];
		
		$infolink5 = $_POST['info_link_5'];
		$infourl5 = $_POST['info_url_5'];
		
		if (empty($errormessage)) {
		
				
			$query  = "UPDATE ng_infopage ";
			$query .= 	"SET  `info_field` =  '{$field}',
						`info_link_1` =  '{$infolink1}',
						`info_url_1` =  '{$infourl1}',
						`info_link_2` =  '{$infolink2}',
						`info_url_2` =  '{$infourl2}',
						`info_link_3` =  '{$infolink3}',
						`info_url_3` =  '{$infourl3}',
						`info_link_4` =  '{$infolink4}',
						`info_url_4` =  '{$infourl4}',
						`info_link_5` =  '{$infolink5}',
						`info_url_5` =  '{$infourl5}'
						WHERE  `ng_infopage`.`info_id`=0 LIMIT 1";
			 
			$result = mysql_query($query, $mysamconnec);
			
			if (mysql_affected_rows()==1){
				$message .= "Update was successful";
			} else {

			}
		}	
	} else {

	}
	
?>


<?php
	
		
	
$query = "SELECT * FROM ng_infopage WHERE info_id=0";
	$query .= $id;
	$query .= " LIMIT 1";
	$contentset = mysql_query($query, $mysamconnec);
	testquery($contentset);
	$info = mysql_fetch_array($contentset);
	

?>

<html>
	<head>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans+Mono' rel='stylesheet' type='text/css'>
		<title>Night Gallery | Manage Info</title>
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
		<h3>EDIT INFO</em></h3>
		</div>
		
		
		
		<div id ="infoform">
		
		<p>To remove a link, just delete that info and hit the 'submit' button</p>
			<form action="manage_info.php" enctype="multipart/form-data" method="post">
		
		
		
				<textarea placeholder="INFO..." name="info_field" ><?php
					echo $info['info_field'];
				?></textarea>
				
				<div id="infolinkarea">
				
					<input type="text" class="info_link" placeholder="Link Name" name="info_link_1" value="<?php echo $info['info_link_1']; ?>" />
					<input type="text" class="info_url" placeholder="http://..." name="info_url_1" value="<?php echo $info['info_url_1']; ?>" />
					
					<input type="text" class="info_link" placeholder="Link Name" name="info_link_2" value="<?php echo $info['info_link_2']; ?>" />
					<input type="text" class="info_url" placeholder="http://..." name="info_url_2" value="<?php echo $info['info_url_2'];; ?>" />
					<input type="text" class="info_link" placeholder="Link Name" name="info_link_3" value="<?php echo $info['info_link_3']; ?>" />
					<input type="text" class="info_url" placeholder="http://..." name="info_url_3" value="<?php echo $info['info_url_3']; ?>" />
					
					<input type="text" class="info_link" placeholder="Link Name" name="info_link_4" value="<?php echo $info['info_link_4']; ?>" />
					<input type="text" class="info_url" placeholder="http://..." name="info_url_4" value="<?php echo $info['info_url_4'];; ?>" />
					<input type="text" class="info_link" placeholder="Link Name" name="info_link_5" value="<?php echo $info['info_link_5']; ?>" />
					<input type="text" class="info_url" placeholder="http://..." name="info_url_5" value="<?php echo $info['info_url_5'];; ?>" />
					
					<input type="submit" value="Submit" id="submit" name="submit" alt="1" class="">
					
				</div>
				
			</form>
		
		</div>
		
		
		</div>
		
		<?php build_footer(); ?>
	 </div>
	 
	</body>
</html>