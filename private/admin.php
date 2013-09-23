<?php
	require_once("../includes/sessions.php");
	require_once("../includes/connection.php");
	require_once("../includes/functions.php");
	require_once("../includes/private_layout_functions.php");
	
	
	confirm_logged_in();
	confirm_top_level();
	
	
	
	if (isset($_GET['open'])){
		//the number that gets passed is what it currently is
		if ($_GET['open'] == 1) {
			$open = 1;
		}
		else {
			$open = 0;
		}
		//DO THE QUERY
			$id = 1;
			$query = "UPDATE ng_opencl SET open = '{$open}' WHERE id={$id}";
			$result = mysql_query($query, $mysamconnec);
			
			if (mysql_affected_rows()==1){
				$message = "Update was successful";
			} else {
				$message = "Update failed";
			}
			
	} else {
		//
	}
		
	
?>
<html>
	<head>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans+Mono' rel='stylesheet' type='text/css'>
		<title>NIGHT GALLERY | ADMIN</title>
		<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/reset/reset-min.css">
		      <link href="styles/style.css" rel="stylesheet" type="text/css">
		      
	</head>
	<body class="admin">
	
		<div class="wid">
		
		
		
		
			<div class="header">
				<div id="userbar" ><p class="user<?php echo $level;?>"><?php echo $_SESSION['username']; ?>
				
					<?php require_once("../includes/userbar.php");?>
				
				</div>
				
				<?php build_admin_nav(); ?>
		</div>
				
			</div>
	
	
	
			<div class="content header" id="admin">
	

	
	
				<?php
					$query = "SELECT * FROM ng_opencl ORDER BY id DESC";
					
					$contentset = mysql_query($query, $mysamconnec);
							testquery($contentset);
						
						while($content = mysql_fetch_array($contentset)){
							$open = $content['open'];
						}
				 ?>
				
				<ul>
				<li>
					
					<?php 
						/*
						if ($open == 1) {
							echo "Open";
						} else {
							echo "Closed";
						}
	*/
					?> 
					
					
					<a id="opensign" class="<?php if($open == 1) { echo "signopen"; } else { echo "signclosed";} ?>" href="admin.php?open=<?php if($open == 1) { echo "0"; } else { echo "1";} ?>" method="post">
						
						<?php
							if ($open) {
								echo "Close Night Gallery";
							} else {
								echo "Open Night Gallery";
							}
						?>
								
					</a>
				</li>
				
		</ul>		
	<ul>
	<li><a href="add_newsitem.php">News</a></li>
	<li><a href="add_artist.php">Artists</a></li>
	<li><a href="add_artwork.php">Artworks</a></li>
	<li><a href="add_events.php">Events / Art Fairs</a></li>
	
	<li><a href="front_page.php">Front Page</a></li>
	<li><a href="manage_ads.php">Ads</a></li>
	<li><a href="manage_info.php">Info Page</a></li>
	<li><a href="manage_nightpapers.php">Night Papers</a></li>
	<li><a href="manage_press.php">Press</a></li>
	
	<li><a href="new_user.php">Private Users / Admin</a></li>
	
	
	
	</ul>
	
	
	
	
			<div>
			
			
		<?php build_footer(); ?>
	 </div>
	 
	</body>
</html>