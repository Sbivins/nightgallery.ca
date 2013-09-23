<?php

	require_once("includes/connection.php");
	require_once("includes/functions.php");
	
	
	$year="0"
?>

<?php build_head("NIGHT GALLERY | CALENDAR"); ?>

</head>
<body class="publicsite">
<div class="wid">


	<div class="header">
		
		<?php require_once("includes/open_sign.php") ?>
		
		
		<?php build_nav("fairs") ?>
		
	</div>


	<div class="header" id="previouspage">
		<!--<h3 class="sang">PREVIOUS</h3>-->
		
		<!-- previouslx is the visible area on the left, and previous list is the full list -->
		<div id="previouslx">
			<div class="scroll-pane" id="previouslist">
			
			<ul>
			<?php
				
				
				//$query = "SELECT * FROM ng_events WHERE event_start <= CURRENT_DATE() ORDER BY event_start DESCENDING";
				$query = "SELECT * FROM ng_events ";
				$query .= "WHERE event_start <= CURRENT_DATE() AND event_type =4 ORDER BY event_start DESC";
				$contentset = mysql_query($query, $mysamconnec);
				testquery($contentset);
					
					while($content = mysql_fetch_array($contentset)){
							//this first line creates the opening tag for the containing div, and gives it the class event1 or event2 etc.
							
							if (get_year($content['event_start']) != $year) {
								$year = get_year($content['event_start']);
								echo "<li class=\"eventyear\">";
								echo $year;
								echo "</li>";
							}
							
							echo "<li class=\"eventunit\">";
							echo "<a href=\"/nightgallery/event.php?id=" . $content['event_id'] . "\" class=\"event" . $content['event_id'] . " calitem inactive\">";
								echo "<p class=\"title\">";
									echo strtoupper( $content['event_title'] );
								echo "</p>";
								
								echo "<p class=\"date " . nl_date_style($content['event_start']) . "\">";
									echo format_dates($content['event_start'] , $content['event_end']) . " </p>";
									
									//echo "<p>" . $content['event_pr']. "</p>";		
							echo "</a>";
							echo "</li>";
						}
				
				
			?>
			</ul>
			</div>
		</div>
		<div id="previousrx">
		
		
		<?php
		
		//$query = "SELECT * FROM ng_events WHERE event_start <= CURRENT_DATE() ORDER BY event_start DESCENDING";
				$query2 = "SELECT * FROM ng_events ";
				$query2 .= "WHERE event_start <= CURRENT_DATE() ORDER BY event_start DESC";
				$contentset2 = mysql_query($query, $mysamconnec);
				testquery($contentset2);
		
		
		while($content2 = mysql_fetch_array($contentset2)){
		
			echo "<div class=\"event" . $content2['event_id'] . " eventpreview\">";
			echo"<div class=\"preview-artists-pane\"><div class=\"preview-artists\">";
			//echo "<p>ARTIST1</p><p>ARTIST2</p>";
			//echo "<p>ARTIST3</p>
			echo "</div></div>";
			echo "<div class=\"preview-poster\" style=\"background-image:url(" . $content2['event_pic_url'] . ")\">&nbsp;</div>";
				echo "<div class=\"preview-date\">";
				//echo "<p>" . strtoupper( $content2['event_title'] ) . "</p>";
				//echo "<p>";
				 //echo nl_date($content2['event_start']) . " - " . nl_date($content2['event_end']) . "</p>";
				 echo "</div></div>";
		
		}
		?>
					
		</div>
		
		<div id="shelf"></div>
		
	</div>
	
	
		<?php build_footer(); ?>
	 </div>
	 
	</body>
</html>
