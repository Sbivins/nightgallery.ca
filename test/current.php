<?php

	require_once("includes/connection.php");
	require_once("includes/functions.php");
	
?>

<?php build_head("NIGHT GALLERY"); ?>


</head>
<body class="publicsite">
<div class="wid">


			<div class="header">
				<?php require_once("includes/open_sign.php") ?>
				
				<?php build_nav("current") ?>
			</div>


	<div class="header" id="artistpage">
		
		
		<!-- ++++++++++++++++++++++    LEFT PANE; PERSISTANT     +++++++++++++++++++++++++++ -->
		<div id="eventlx">
		


<?php
							//$query = "SELECT * FROM ng_events WHERE event_start >= CURRENT_DATE() ORDER BY event_start ASC LIMIT 4";
							$query = "SELECT * ";
							$query .= "FROM ng_events ";
							$query .= " WHERE event_start <= CURRENT_DATE() AND event_type<>4 ORDER BY event_start DESC LIMIT 1";
					
							$contentset = mysql_query($query, $mysamconnec);
							testquery($contentset);
								
								
							$thisevent = mysql_fetch_array($contentset);
														
							// I need to take each one of the event_artist1, 2, 3 ids, and feed them all through a 
							// 15 is the number of fields that start with "event_artist" : "event_artist1", "event_artist2", etc.
							$query = "";
							for ($i = 1; $i <= 15; $i++) {
							$query .= "(SELECT * ";
							$query .= "FROM ng_artists ";
							$query .= "WHERE artist_id= ";
							//$query .= "1";
							$query .= $thisevent['event_artist' . $i] . ")";
								if ($i < 15) {
									$query .= " UNION ";
								}
							}
							$query .= "";
							
							
							$event_artists_set = mysql_query($query, $mysamconnec);
							testquery($event_artists_set);
								
							?>
							
							
							<?php
				echo "<H6 id=\"leftheader\" class=\"eventtitle\">";
				echo strtoupper( $thisevent['event_title'] );
				echo "</H6>";
			?>
							
						<ul id="artists">
							<?php
							while($event_artist = mysql_fetch_array($event_artists_set)){
							
										echo "<li><H3 class=\"artist\">";
										if ($event_artist['artist_type'] == 1){ echo "<a class=\"default\"href=\"artist.php?id=" . $event_artist['artist_id'] . "\">"; }
										echo strtoupper( $event_artist['artist_first_name'] . " " . $event_artist['artist_last_name'] );
										if ($event_artist['artist_type'] == 1) { echo "</a>"; }
										echo "</H3></li>";
									
							} ?>
							
						</ul>
						
						<h4>
							<?php
								echo format_dates($thisevent['event_start'] , $thisevent['event_end']);
							?>
						</h4>
						
						
						
			<div id="presspane">
				<div id="pressrelease">
				<?php echo "<span class=\"pressrelease\">" . stripslashes( nl2br( $thisevent['event_pr'] )) . "</span>"; ?>
				</div>
			</div>
			
			
			<div class="thumber" id="thumber-up"> &nbsp;</div>
			
			<div id="thumbspane">
			<div id="thumbsreel">
			
				<?php
				//$query = "SELECT * FROM ng_events WHERE event_start >= CURRENT_DATE() ORDER BY event_start ASC LIMIT 4";
				$query = "SELECT * ";
				$query .= "FROM ng_event_shots ";
				$query .= "WHERE event_shot_event =";
				$query .= $thisevent['event_id'];
				$query .= " ORDER BY event_shot_order_pos DESC";
		
				$artworkscontentset = mysql_query($query, $mysamconnec);
				testquery($artworkscontentset);
					
				$shotcount = 0;
				while($artwork = mysql_fetch_array($artworkscontentset)){
						$shotcount++;
						echo "<div class=\" shot";
						//this next line is taking the place of 'blue'.
						//this has to be a key that is the same as that in the other pane for each artwork.
						echo prep_string_for_filename($artwork['event_shot_id'] ); 
						echo " thumb unselected\" style=\"background-image:url(";
						echo $artwork['event_shot_url'];
						echo ")\">&nbsp;</div>";
				}
				
			?>
				
				<p id="shelf"></p>
				</div>
			</div>
			
			<div class="thumber" id="thumber-down"> &nbsp;</div>

		</div>
		
		
		<!-- ++++++++++++++++++++++    RIGHT PANE; CONTEXTUAL, SWITCHES BETWEEN "pane" DIVS     +++++++++++++++++++++++++++ -->
		<div id="eventright">
		
		
			<div id="eventrx">
		
				
				
				
				<div class="default images">
					
					<span><div class="artwork default" style="background-image:url(<?php echo $thisevent['event_pic_url'] ?>)">&nbsp;</div></span>
					<?php
				//$query = "SELECT * FROM ng_events WHERE event_start >= CURRENT_DATE() ORDER BY event_start ASC LIMIT 4";
				$query = "SELECT * ";
				$query .= "FROM ng_event_shots ";
				$query .= "WHERE event_shot_event =";
				$query .= $thisevent['event_id'];
				$query .= " ORDER BY event_shot_order_pos DESC";
				
		
				$artworkscontentset = mysql_query($query, $mysamconnec);
				testquery($artworkscontentset);
					
				while($artwork2 = mysql_fetch_array($artworkscontentset)){
						
						echo "<div class=\"artwork shot";
						//this next line is taking the place of 'blue'.
						//this has to be a key that is the same as that in the other pane for each artwork.
						echo prep_string_for_filename( $artwork2['event_shot_id'] ); 
						echo " \" style=\"background-image:url(";
						echo $artwork2['event_shot_url'];
						echo ")\">&nbsp;</div>";
				}
				//<div class="artwork blue" style="background-image:url(/nightgallery/img/alika/1.png)">&nbsp;</div>

			   ?>
					
				</div>
				
				
				
			</div>
		</div>
		<div id="shelf"></div>
	</div>



<?php build_footer(); ?>
	 </div>
	 
	</body>
</html>