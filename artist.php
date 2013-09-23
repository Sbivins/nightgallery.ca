<?php

	require_once("includes/connection.php");
	require_once("includes/functions.php");
	
	$artist_id = $_GET['id'];
	if (!$artist_id) {
		header("Location: index.php");
	}
	
	
	//$query = "SELECT * FROM ng_events WHERE event_start >= CURRENT_DATE() ORDER BY event_start ASC LIMIT 4";
	$query = "SELECT * ";
	$query .= "FROM ng_artists ";
	$query .= "WHERE artist_id =";
	$query .= $artist_id;
	$query .= " ORDER BY artist_last_name ASC";
	
	$artistcontentset = mysql_query($query, $mysamconnec);
	testquery($artistcontentset);
	$content = mysql_fetch_array($artistcontentset);
	
?>

<?php build_head("NIGHT GALLERY | " . strtoupper( $content['artist_first_name'] . " " . $content['artist_last_name'] ) ); ?>

</head>
<body class="publicsite">
<div class="wid">


	<div class="header">
		
		<?php require_once("includes/open_sign.php") ?>
		
		
		<?php build_nav("") ?>
		
	</div>


	<div class="contents" id="artistpage">
		
		
		<!-- ++++++++++++++++++++++    LEFT PANE; PERSISTANT     +++++++++++++++++++++++++++ -->
		<div id="artistlx">
		

			<?php		
			
					$query = "SELECT * ";
					$query .= "FROM ng_artists ";
					$query .= "WHERE artist_id =";
					$query .= $artist_id;
					$query .= " ORDER BY artist_last_name ASC";
					
					$artistcontentset = mysql_query($query, $mysamconnec);
					testquery($artistcontentset);
					$content = mysql_fetch_array($artistcontentset);
			
			
						echo "<div>";
	
							echo "<ul><a class=\"default\" href=\"#\"><H3 class=\"artist\">";
							echo strtoupper( $content['artist_first_name'] . " " . $content['artist_last_name'] );
							echo "</H3></a></ul>";
						echo "</div>";
				
			?>
			<ul>
				<a class="biography" href="#biography">
					<li>BIOGRAPHY</li>
				</a>
				<a class="exhibitions" href="#exhibitions">
					<li>EXHIBITIONS</li>
				</a>
				<a class="press" href="#press">
					<li>PRESS</li>
				</a>
				
				<?php
					
					if ($content['artist_cv_url']) {
						echo "<p><a href=\"" . ngconvert( $content['artist_cv_url'] ) . "\" target=\"_blank\">CV &#8663;</a></p>";
					}
					
				?>
				
				
			</ul>
			
			
			
			<div id="artworkinfo">
				
				<?php
				//$query = "SELECT * FROM ng_events WHERE event_start >= CURRENT_DATE() ORDER BY event_start ASC LIMIT 4";
				$query = "(SELECT * ";
				$query .= "FROM ng_artworks ";
				$query .= "WHERE artwork_artist_id =";
				$query .= $artist_id;
				$query .= " AND artwork_permission_level =3)";
				$query .= " UNION (SELECT * ";
				$query .= "FROM ng_artworks ";
				$query .= "WHERE artwork_artist_id =";
				$query .= $artist_id;
				$query .= " AND artwork_permission_level =0)";
				$query .= " ORDER BY artwork_order_pos DESC";
				
		
				$artworkscontentset = mysql_query($query, $mysamconnec);
				testquery($artworkscontentset);
					
				while($artworkinfo = mysql_fetch_array($artworkscontentset)){
						
						
						echo "<div class=\"artwork";
						//this next line is taking the place of 'blue'.
						//this has to be a key that is the same as that in the other pane for each artwork.
						echo prep_string_for_filename($artworkinfo['artwork_id'] ); 
						echo " \">";
						
						
						echo "<p><em>" . $artworkinfo['artwork_title'] . "</em>";
						if($artworkinfo['artwork_date']) { echo ", " . $artworkinfo['artwork_date'] .  " "; }
						echo "</p>";
						echo "<p>" . $artworkinfo['artwork_medium'] . "</p>";
						echo "<p>" . $artworkinfo['artwork_dimensions'] . "</p>";
						
						echo "</div>";
				}
				
				?>

			</div>

			
			
			
			<div class="thumber" id="thumber-up"> &nbsp;</div>
			
			<div id="thumbspane">
			<div id="thumbsreel">
			
				
			<?php
				//$query = "SELECT * FROM ng_events WHERE event_start >= CURRENT_DATE() ORDER BY event_start ASC LIMIT 4";
				$query = "(SELECT * ";
				$query .= "FROM ng_artworks ";
				$query .= "WHERE artwork_artist_id =";
				$query .= $artist_id;
				$query .= " AND artwork_permission_level =3)";
				$query .= " UNION (SELECT * ";
				$query .= "FROM ng_artworks ";
				$query .= "WHERE artwork_artist_id =";
				$query .= $artist_id;
				$query .= " AND artwork_permission_level =0)";
				$query .= " ORDER BY artwork_order_pos DESC";
				
		
				$artworkscontentset = mysql_query($query, $mysamconnec);
				testquery($artworkscontentset);
					
				while($artwork = mysql_fetch_array($artworkscontentset)){
						
						
					if($artwork['artwork_video_embed']){
						echo "<div class=\"artwork";
						//this next line is taking the place of 'blue'.
						//this has to be a key that is the same as that in the other pane for each artwork.
						echo prep_string_for_filename($artwork['artwork_id'] ); 
						echo " thumb unselected preload activethumb\" style=\"background-image:url(";
						echo thumb_from_youtube($artwork['artwork_video_embed']);
						echo ")\">&nbsp;</div>";
					} else {
						echo "<div class=\"artwork";
						//this next line is taking the place of 'blue'.
						//this has to be a key that is the same as that in the other pane for each artwork.
						echo prep_string_for_filename($artwork['artwork_id'] ); 
						echo " thumb unselected preload activethumb\" style=\"background-image:url(";
						echo $artwork['artwork_image_url'];
						echo ")\">&nbsp;</div>";
					}
						
						
						
				}
				
			?>
			
			
				
				
				
				
				<p class="shelf"></p>
				</div>
			</div>
			
			<div class="thumber" id="thumber-down"> &nbsp;</div>

		</div>
		
		
		
		
		<!-- ++++++++++++++++++++++    RIGHT PANE; CONTEXTUAL, SWITCHES BETWEEN "pane" DIVS     +++++++++++++++++++++++++++ -->
		<div id="artistrx">
		
			<div class="artwork default artistpic preloadcontain" style="background-image:url(
				<?php
				echo $content['artist_pic'];
				?>
				)">&nbsp;</div>
		
			<div class="hidden images">
				
				<?php
				//$query = "SELECT * FROM ng_events WHERE event_start >= CURRENT_DATE() ORDER BY event_start ASC LIMIT 4";
				$query = "(SELECT * ";
				$query .= "FROM ng_artworks ";
				$query .= "WHERE artwork_artist_id =";
				$query .= $artist_id;
				$query .= " AND artwork_permission_level =3)";
				$query .= " UNION (SELECT * ";
				$query .= "FROM ng_artworks ";
				$query .= "WHERE artwork_artist_id =";
				$query .= $artist_id;
				$query .= " AND artwork_permission_level =0)";
				$query .= " ORDER BY artwork_order_pos DESC";
				
		
				$artworkscontentset = mysql_query($query, $mysamconnec);
				testquery($artworkscontentset);
					
				while($artwork2 = mysql_fetch_array($artworkscontentset)){
						
						
						
						
						echo "<div class=\"artwork artwork";
						//this next line is taking the place of 'blue'.
						//this has to be a key that is the same as that in the other pane for each artwork.
						echo prep_string_for_filename( $artwork2['artwork_id'] ); 
						echo " \" > ";
						
						
						if($artwork2['artwork_video_embed']){
						echo "<div class=\"vid\"> " . embed_from_youtube($artwork2['artwork_video_embed']) . " </div>";
					} else {
						echo "<div class=\"artworkimage preloadcontain\" style=\"background-image:url(";
						echo $artwork2['artwork_image_url'];
						echo ")\">   &nbsp;</div>";
					}
						
						
						echo " </div>";
				}
				

//<div class=\"artworkinfo\"><p>" . $artwork2['artwork_title'] . ", " . $artwork2['artwork_date'] .  " ";
						//echo "" . $artwork2['artwork_medium'] . " ";
						//echo "" . $artwork2['artwork_dimensions'] . "</p>";
						
						//echo "</div>


			?>			
				
			</div>
			
			<div class="hidden biography">
			<p id="biopane" class="formattable">
				<?php
						echo  nl2br( html_entity_decode( $content['artist_bio'] ));
				?>	
			</p>		
			</div>
			<div class="hidden exhibitions">
						
						
						
						<?php
							
							//15 is the number of fields that start with "event_artist" : "event_artist1", "event_artist2", etc.
							$query = "";
							for ($i = 1; $i <= 15; $i++) {
							$query .= "(SELECT * ";
							$query .= "FROM ng_events ";
							$query .= "WHERE event_artist" . $i . " =";
							$query .= $artist_id . ")";
								if ($i < 15) {
									$query .= " UNION ";
								}
								
							}
							$query .= " ORDER BY event_start DESC";
														
							$artistevents_set = mysql_query($query, $mysamconnec);
							testquery($artistevents_set);
								
							
							while($content = mysql_fetch_array($artistevents_set)){
								if($content['event_type'] < 4) {
										echo "<ul><a class=\"default\"href=\"event.php?id=" . $content['event_id'] . "\"><H3 class=\"eventtitle\">";
										echo strtoupper( $content['event_title'] );
										echo "</H3></a></ul>";
									
								}		
									
							}

						?>
								
						
			</div>
			<div class="hidden press">
						
				<?php
				//$query = "SELECT * FROM ng_events WHERE event_start >= CURRENT_DATE() ORDER BY event_start ASC LIMIT 4";
				$query = "SELECT * ";
				$query .= "FROM ng_pressitems ";
				$query .= "WHERE pressitem_artist_id =";
				$query .= $artist_id;
				$query .= " ORDER BY pressitem_date ASC";
		
				$presscontentset = mysql_query($query, $mysamconnec);
				testquery($presscontentset);
				
				while($content = mysql_fetch_array($presscontentset)) {
					echo "<p class=\"artist-press-item\">";
						echo "<a class=\"default\"href=\"" . ngconvert($content['pressitem_url']) . "\" target=\"_blank\">";
						echo "<span>" . strtoupper( $content['pressitem_outlet'] ) . " - " . nl_date( $content['pressitem_date'] ) . "</span><br>";
						echo "<span ><em>" . $content['pressitem_title'] . "</em></span>";
						echo "</a>";
				}	
						
					
		
				?>
		
			</div>
			
		</div>
		
	</div>
	
	
	<div class="push"></div>
</div>
	
	
	<?php build_footer(); ?>
	</body>
</html>