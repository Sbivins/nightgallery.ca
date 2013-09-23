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
	
	
	$id = $_GET['id'];
	
	//sees if this has just been submitted to itself
	if (isset($_POST['submit'])){
	
		//the number that gets passed is what it currently is_array()
		
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
	
	
	
	if($showprices) {
		$query = "SELECT * FROM	ng_artworks ";
		} else {
			$query = "SELECT artwork_id, artwork_title, artwork_artist_id, artwork_image_url , artwork_dimensions, artwork_medium, artwork_date, artwork_insert_time FROM ng_artworks ";
		}
		$query .= " WHERE artwork_id =" . $id;
		if(!$show_all) {
			$query .= " AND artwork_permission_level<2 ";
		}
		
		$query .= " LIMIT 1";
$contentset = mysql_query($query, $mysamconnec);
testquery($contentset);

		$artwork = mysql_fetch_array($contentset);
	
	
	
	
	
?>
<html>
	<head>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans+Mono' rel='stylesheet' type='text/css'>
		<title>NIGHT GALLERY | Private Gallery</title>
		<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/reset/reset-min.css">
		      <link href="styles/style.css" rel="stylesheet" type="text/css">
		      
	</head>
	<body id="private" class="private">
	
		<div class="wid">
		
		
		
		
			<div class="header">
				<div id="userbar" ><p class="user<?php echo $level;?>"><?php echo $_SESSION['username']; if($userisadmin) { echo "<a href=\"admin.php\">ADMIN</a>"; } ?> </p>
				</div>
				
				<?php build_private_nav("") ?>
				
			</div>
	
	
	
			<div class="content header">
	
	
	
				<div id="artwork">
					
					
				<?php
					
					$query = "SELECT * FROM ng_artists WHERE artist_id = ";
						$query .= $artwork['artwork_artist_id'];
						$artworkartistresults = mysql_query($query, $mysamconnec);
						testquery($artworkartistresults);
						$thisartist = mysql_fetch_array($artworkartistresults);
						
					echo "<h3><a href=\"artist.php?id=";
					echo $thisartist['artist_id'];
					echo "\">" . strtoupper($thisartist['artist_first_name'] . " " . $thisartist['artist_last_name']). "</a></h3>";
					
					
					echo "<p><span><em>" . $artwork['artwork_title'] . "<em></span>" ;
					if ($artwork['artwork_dimensions']) {echo "<span>, " . $artwork['artwork_dimensions'] . " </span>"; }
					if ($artwork['artwork_medium']) { echo "<span>, " . $artwork['artwork_medium'] . "</span>"; }
					if ($artwork['artwork_date']) { echo "<span>, " . $artwork['artwork_date'] . "</span></p>"; }
					if ($showprices) {
						if ($artwork['artwork_price'] > 1) {
						echo "<p id = \"price\">$" . $artwork['artwork_price'] . "<p>";
						} else if ($artwork['artwork_price'] == 1) {
						
						} else if ($artwork['artwork_price'] == 0){
						echo "<p id = \"price\">NFS<p>";
						}
					}
					
					
					echo "</li>";
				
				
			?>
					
					<?php 
					
					if ($artwork['artwork_video_embed']){
						echo "<div id=\"artworkview\">";
						echo embed_from_youtube( $artwork['artwork_video_embed']  );
						echo " </div>";

						
					} else {
						echo "<div id=\"artworkview\" style=\"background-image:url(" . $artwork['artwork_image_url'] . ")\"> &nbsp; </div>";
					
					}
					
					
					?>
				
				
				</div>
	
	
			<div>
			
			
			<?php build_footer(); ?>
	 </div>
	 
	</body>
</html>
