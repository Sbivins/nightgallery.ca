<?php

	require_once("includes/connection.php");
	require_once("includes/functions.php");
	
?>

<?php build_head("NIGHT GALLERY | ARTISTS"); ?>

<link href="/styles/artists.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body class="publicsite">
<div class="wid">


	<div class="header">
		
		<?php require_once("includes/open_sign.php") ?>
		
	
		
		<?php build_nav("artists") ?>
		
	</div>


	<div class="header" id="artistspage">
		<!--<h3 class="sang">ARTISTS</h3>-->
		
		

		<?php
			
			
			//$query = "SELECT * FROM ng_events WHERE event_start >= CURRENT_DATE() ORDER BY event_start ASC LIMIT 4";
			$query = "SELECT * FROM ng_artists WHERE artist_type=1 ORDER BY artist_last_name ASC";
	
				$contentset = mysql_query($query, $mysamconnec);
				testquery($contentset);
				
				
				echo "<div class=\"\"></div>";
				
			
				
				
				while($content = mysql_fetch_array($contentset)){
					echo "<a href=\"/artist.php?id=" . $content['artist_id'] . "&name=" . add_dashes($content['artist_first_name'] . "-" . $content['artist_last_name']) . "\">";
					echo "<p class=\"name\">";
						echo strtoupper( $content['artist_first_name'] . " " . $content['artist_last_name'] );
					echo "</p>";
					
					echo "</a>";
			
				}
			
			
		?>
		
	</div>
	
	
<?php build_footer(); ?>
	 </div>
	 
	</body>
</html>