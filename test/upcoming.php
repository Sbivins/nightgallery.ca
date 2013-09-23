<?php

	require_once("includes/connection.php");
	require_once("includes/functions.php");
	
?>

<?php build_head("NIGHT GALLERY | CALENDAR"); ?>

<link href="/nightgallery/styles/upcoming.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body class="publicsite">
<div class="wid">


	<div class="header">
		
		<?php require_once("includes/open_sign.php") ?>
		
		<?php build_nav("upcoming") ?>
		
	</div>


	<div class="header" id="upcoming">
		<!--<h3 class="sang">UPCOMING</h3>-->
		
		<div>

		<?php
			
			
			//$query = "SELECT * FROM ng_events WHERE event_start >= CURRENT_DATE() ORDER BY event_start ASCENDING";
			$query = "SELECT * FROM ng_events ";
			$query .= "WHERE event_start >= CURRENT_DATE() ORDER BY event_start ASC";
	
			$allevents = mysql_query($query, $mysamconnec);
			testquery($allevents);
			
			
			
			
			//TESTTEST
			$allevents2 = mysql_query($query, $mysamconnec);
			
			$counter=0;
			
			while( $content = mysql_fetch_array($allevents2) ){
			
				$counter++;
				
				//only if one event has indeed been loaded up, and only on the first one.
				if( $counter==1 ){
					echo "<div class=\"timeframe\">NEXT SHOW</div>";	
				}
				
				//if it loads a second one, it spits out "future shows" before that one, and only the second one.
				if( $counter==2 ){
					echo "<div class=\"timeframe\">FUTURE SHOWS</div>";	
				}
				
				//the following runs each time:
					//spit out opening anchor tag
					echo "<a class=\"eventinfo\" href=\"/nightgallery/event.php?id=" . $content['event_id'] . "&name=" . add_dashes($content['event_title']) . "\">";
					
					if( $counter==1 ){
						
							echo "<h3 class=\"eventtitle\">";
							echo strtoupper( $content['event_title'] );
							echo "</h3>";
						
					} else {
						echo "<p class=\"title\">";
							echo strtoupper( $content['event_title'] );
							echo "</p>";
							
					}
							
					
					echo "<p class=\"date " . nl_date_style($content['event_start']) . "\">";
					echo nl_date($content['event_start']) . "</p>";
					echo "</a>";
				}
			
			if( $counter==0 ){
				echo "There are no future shows scheduled.";
				
			}
			
			
			/*
			
			echo (sizeof($allevents));
			
			
			if(!$allevents){
				echo "There are no upcoming events.";
			} else {
				
				
				if($content = mysql_fetch_array($allevents)){
					echo "yep";
				} else {
					echo "nope";
				}
				
				
				$data = array();
				//print_r($allevents);
				echo "<br/>";
				$row = mysql_fetch_assoc($allevents);
				//print_r($row);
				$id = array_shift($row);
				
				
				echo "<div class=\"timeframe\">NEXT SHOW</div>";
				
					echo "<a class=\"eventinfo\" href=\"/nightgallery/event.php?id=" . $id . "&name=" . add_dashes($row['event_title']) . "\">";
					echo "<h3 class=\"eventtitle\">";
					echo strtoupper( $row['event_title'] );
					echo "</h3>";

				echo "<p class=\"date " . nl_date_style($row['event_start']) . "\">";
				echo nl_date($row['event_start']) . "</p>";
				echo "</a>";
				
			
				
				
				echo "<div class=\"timeframe\">FUTURE SHOWS</div>";
				
				while($content = mysql_fetch_array($allevents)){
					
					echo "<a class=\"eventinfo\" href=\"/nightgallery/event.php?id=" . $content['event_id'] . "&name=" . add_dashes($content['event_title']) . "\">";
					
		
						
						echo "<p class=\"title\">";
						echo strtoupper( $content['event_title'] );
						echo "</p>";
						
				
						
					echo "<p class=\"date " . nl_date_style($content['event_start']) . "\">";
					echo nl_date($content['event_start']) . "</p>";
					echo "</a>";
					$counter++;
				}
				
			} */
			
			
			
			
			/*echo "ALLEVENTS:<br>";
				print_r($allevents);
				echo "<br> DATA:";
				echo "<br>";
				print_r($data);
				echo "<br> ROW:";
				echo "<br>";
				print_r($row);
				echo "<br> ID:";
				echo "<br>";
				print_r($id);*/
							
			
		?>
		
		</div>
		<div id="shelf"></div>
	</div>
	
	

<?php build_footer(); ?>
	 </div>
	 
	</body>
</html>