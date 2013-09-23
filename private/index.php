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
				
				<?php build_private_nav("inventory") ?>
				
			</div>
	
	
	
			<div class="content header">
	
	
	
				<div  id="galleryartists" class="artistgrid">
					<h3>GALLERY ARTISTS</h3>
					<ul>
					<?php
					//$query = "SELECT * FROM ng_events WHERE event_start >= CURRENT_DATE() ORDER BY event_start ASC LIMIT 4";
					$query = "SELECT * FROM ng_artists WHERE artist_type=1 ORDER BY artist_last_name ASC";
			
					$contentset = mysql_query($query, $mysamconnec);
					testquery($contentset);
					
					
					echo "<div class=\"\"></div>";
					
					while($content = mysql_fetch_array($contentset)){
					
								$query = "SELECT artwork_id FROM ng_artworks WHERE artwork_artist_id="; 
								$query .= $content['artist_id']; 
								$artistartworkresults = mysql_query($query, $mysamconnec);
								testquery($artistartworkresults);
								
								$counter = 0;
								while($artwork = mysql_fetch_array($artistartworkresults)){
									$counter++;
								}

						if($counter) {
							
							echo "<li>";
							echo "<a href=\"artist.php?id=" . $content['artist_id'] . "&name=" . add_dashes($content['artist_first_name']) . "-" . add_dashes($content['artist_last_name']) . "\">";
							echo "<p class=\"name\">";
							echo strtoupper( $content['artist_first_name'] . " " . $content['artist_last_name'] );
							echo "</p>";
							echo "</a>";
							echo "</li>";
							
							
						}
						
					}
					?>
					</ul>
				
				
				</div>
				<div id="exhibitedartists" class="artistgrid">
					<h3>EXHIBITED ARTISTS</h3>
					
					<ul>
						<?php
						//$query = "SELECT * FROM ng_events WHERE event_start >= CURRENT_DATE() ORDER BY event_start ASC LIMIT 4";
						$query = "SELECT artist_first_name, artist_last_name, artist_id FROM ng_artists WHERE artist_type=0"; 
						$query .= " ORDER BY artist_last_name ASC";
				
						$contentset = mysql_query($query, $mysamconnec);
						testquery($contentset);
						
						while($content = mysql_fetch_array($contentset)){
						
						
						
						$query = "SELECT artwork_id FROM ng_artworks WHERE artwork_artist_id="; 
								$query .= $content['artist_id']; 
								$artistartworkresults = mysql_query($query, $mysamconnec);
								testquery($artistartworkresults);
								
								$counter = 0;
								while($artwork = mysql_fetch_array($artistartworkresults)){
									$counter++;
								}

								if($counter) {
						
									echo "<li>";
									echo "<a href=\"artist.php?id=" . $content['artist_id'] . "&name=" . add_dashes($content['artist_first_name']) . "-" . add_dashes($content['artist_last_name']) . "\">";
									echo "<p class=\"name\">";
										echo strtoupper( $content['artist_first_name'] . " " . $content['artist_last_name'] );
									echo "</p>";
									
									echo "</a>";
									echo "</li>";
							
							
								}
							
							
							
						}
						?>
					</ul>
					
				
				</div>
	
	
			<div>
			
		<?php build_footer(); ?>
	 </div>
	 
	</body>
</html>