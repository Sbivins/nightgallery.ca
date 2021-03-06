<?php
	require_once("../includes/sessions.php");
	require_once("../includes/connection.php");
	require_once("../includes/functions.php");
	require_once("../includes/private_layout_functions.php");
	
	
	confirm_logged_in();
	$level = get_user_level();
	if ($level == 2 || $level == 4 || $level == 5) {
		$showprices = true;
	} else { $showprices = false; }
	
	if ($level >=3) {
		$show_all = true;
	} else { $show_all = false; }
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
				
				
				<?php build_private_nav("newwork") ?>
				
			</div>
	
	
	
			<div class="content header">
	
	
	
				<div id="newwork">
					<ul>
						<? 
						if($showprices) {
							$query = "SELECT * FROM	ng_artworks ";
						} else {
							$query = "SELECT artwork_id, artwork_title, artwork_artist_id, artwork_image_url , artwork_dimensions, artwork_medium, artwork_date, artwork_insert_time FROM ng_artworks ";
						}
						if(!$show_all) {
							$query .= " WHERE artwork_permission_level<2 ";
						}
						
						$query .= " ORDER BY artwork_insert_time DESC LIMIT 6";
				$contentset = mysql_query($query, $mysamconnec);
				testquery($contentset);
				
				while($content = mysql_fetch_array($contentset)){
				
					echo "<li>";
					
					echo "<a href=\"artwork.php?id=" . $content['artwork_id']  . "\">";
					
					$query = "SELECT * FROM ng_artists WHERE artist_id = ";
						$query .= $content['artwork_artist_id'];
						$artworkartistresults = mysql_query($query, $mysamconnec);
						testquery($artworkartistresults);
						$thisartist = mysql_fetch_array($artworkartistresults);
						
					echo "<span>" . strtoupper($thisartist['artist_first_name'] . " " . $thisartist['artist_last_name']). "</span> - ";
					
					//echo "<div class=\"pic\" style=\"background-image:url(" . $content['artwork_image_url'] . ")\"> &nbsp; </div>";
					echo "<span><em>" . $content['artwork_title'] . "</em></span>" ;
						
						if($content['artwork_dimensions']) { echo "<span>, " . $content['artwork_dimensions'] . "</span>"; }
						
						if($content['artwork_medium']) { echo "<span>, " . $content['artwork_medium'] . "</span>"; }
						
						if($content['artwork_date']) {echo "<span>, " . $content['artwork_date'] . "</span>";}
						
						if ($showprices) {
							
							if ($content['artwork_price'] > 1) {
								echo " - <span id = \"price\">$" . $content['artwork_price'] . "</span>";
							} else if ($content['artwork_price'] == 1) {
							
							} else if ($content['artwork_price'] == 0){
								echo " - <span id = \"price\">NFS</span>";
							}	
						
						}
					
					
					echo "</a></li>";
				}
				
			?>
					</ul>
				
				
				</div>
	
	
			<div>
			
			
	
		<?php build_footer(); ?>
	 </div>
	 
	</body>
</html>
