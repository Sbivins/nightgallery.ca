<?php
	require_once("../includes/sessions.php");
	require_once("../includes/connection.php");
	require_once("../includes/functions.php");
	require_once("../includes/private_layout_functions.php");
	
	
	confirm_logged_in();
	$level = get_user_level();	
	if ($level == 5) {
		$userisadmin = true;
	}
	
?>
<html>
	<head>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans+Mono' rel='stylesheet' type='text/css'>
		<title>NIGHT GALLERY | Private Gallery</title>
		<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/reset/reset-min.css">
		      <link href="styles/style.css" rel="stylesheet" type="text/css">
		      
	</head>
	<body class="private">
	
		<div class="wid">
		
		
		
		
			<div class="header">
				<div id="userbar" ><p class="user<?php echo $level;?>"><?php echo $_SESSION['username']; if($userisadmin) { echo "<a href=\"admin.php\">ADMIN</a>"; } ?> </p>
				</div>
				
				<?php build_private_nav("contact") ?>
				
			</div>
	
	
	
			<div class="content header">
	
	
	
				<div id="contact">
					
					<p>Meike Marple: 650 646 8223</p>
					<p>2717 S. Robertson Ave. Los Angeles, CA | real_email_address@nightgallery.ca</p>
					<p>Tues - Sun | 10am - 5pm</p>
					<p>Email Subscription</p>
				
				
				</div>
	
	
			<div>
			
			
			<?php build_footer(); ?>
	 </div>
	 
	</body>
</html>
