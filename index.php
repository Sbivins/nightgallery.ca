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
		
		<?php build_nav("") ?>
		
	</div>
	
	
	
	<div class="section" id="iphone-info">
			<a href="http://maps.google.com/maps?q=204+S+Ave+19+Los+Angeles&ll=34.071929,-118.221152&spn=0.010131,0.015321&client=safari&oe=UTF-8&hnear=204+S+Ave+19,+Los+Angeles,+California+90031&gl=us&t=m&z=16">204 S. AVE 19<br>LOS ANGELES</a><br><br>
			<a href="tel:+1-650-384-5448">(650) 384-5448</a><br><br>
		
			TUES - THURS<br>10pm - 2am
	</div>
	
	<div class="article">
	
	
		<div id="mainpane">
		<?php
			
			$query = "SELECT * FROM ng_frontpage ORDER BY frontpage_position DESC LIMIT 1";
	
				$frontpageresult = mysql_query($query, $mysamconnec);
				testquery($frontpageresult);
				
				$frontpage = mysql_fetch_array($frontpageresult);
				
				if($frontpage['frontpage_event']){
					$eventexists = TRUE;
					$query = "SELECT * ";
					$query .= "FROM ng_events ";
					$query .= "WHERE event_id =";
					$query .= $frontpage['frontpage_event'];
					$query .= " LIMIT 1";
			
					$eventresult = mysql_query($query, $mysamconnec);
					testquery($eventresult);
					
					$thisevent = mysql_fetch_array($eventresult);
				}
				
				if(!$frontpage['frontpage_image_url']) {
				// DO THIS STUFF IF THERES NO IMAGE URL
					
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
				
					
					echo "<a href=\"event.php?id=" . $thisevent['event_id'] . "\">";
			echo "<div class=\"section\" id=\"lx\">";
				echo "<div>";
					echo "<div class=\"fronteventimage preloadcover\"  style=\"background-image:url(" . $thisevent['event_pic_url'] . ")\"> &nbsp; </div>";
				echo "</div>";
			echo "</div>";
			
			echo "<div class=\"section\" id=\"rx\">";
		
				echo "<div class=\"rightcontent\">";
					echo "<div class=\"poster\">";
					
						echo "<H6 id=\"leftheader\" class=\"eventtitle pagetitle\">";
						echo strtoupper( $thisevent['event_title'] );
						echo "</H6>";
						
						echo "<ul>";
						
							while($event_artist = mysql_fetch_array($event_artists_set)){
							
								echo "<li class=\"artist\">";
								if ($event_artist['artist_type'] == 1){ echo "<a class=\"default\" href=\"artist.php?id=" . $event_artist['artist_id'] . "\">"; }
								echo strtoupper( $event_artist['artist_first_name'] . " " . $event_artist['artist_last_name'] );
								if ($event_artist['artist_type'] == 1) { echo "</a>"; }
								echo "</li>";
									
							}
						
						echo "</ul>";
						
						echo "<p>";
						echo format_dates($thisevent['event_start'] , $thisevent['event_end']);
						echo "</p>";
						
					echo "</div>";
				echo "</div>";
			echo "</div>";
			echo "</a>";
					
					
					
				} else {
					
					
					if($eventexists) {
						echo "<a href=\"event.php?id=" . $thisevent['event_id'] . "\">";
						
					}
					
					echo "<div class=\"fullwidth preloadcover\" style=\"background-image:url(" . $frontpage['frontpage_image_url'] . ")\">&nbsp;</div>";	
					if($eventexists){
						echo "</a>";
					}
					
					
				}
					
				/*
echo "<div style=\"background-image:url(" . $event_artist['artist_image'] . ")\" ";
				echo " alt=\"";
				echo $content['artist_last_name'];
				echo "\" class=\"artistimage ";
				echo remove_spaces( $content['artist_last_name'] );
				echo "\" />&nbsp;</div>";
*/


//
			
		?>
		
		
			
		
		</div class="mainpane">
	
		<div id="artistimagepane">
			<?php
			
			//++++++++++++++     ARTIST IMAGES       ++++++++++++++++
			
			//$query = "SELECT * FROM ng_events WHERE event_start >= CURRENT_DATE() ORDER BY event_start ASC LIMIT 4";
			$query = "SELECT * FROM ng_artists WHERE artist_type>0 ORDER BY artist_last_name ASC";
	
				$artistset = mysql_query($query, $mysamconnec);
				testquery($contentset);
				
				while($content = mysql_fetch_array($artistset)){
					
					echo "<div style=\"background-image:url(" . $content['artist_image'] . ")\" ";
					echo " alt=\"";
					echo $content['artist_last_name'];
					echo "\" class=\"artistimage ";
					echo remove_spaces( $content['artist_last_name'] );
					echo "\" />&nbsp;</div>";
				}
			?>
			
		</div>
		
			<!--<a href="#" class="rightcontent"><img src="img/groupshower.png" alt="groupshower"/></a>-->
			
				<div class="dropdown">
					&nbsp;
					<ul class="artists">
					
					<?php
			
			
					//++++++++++++++     ARTIST MENU       ++++++++++++++++
			
					//$query = "SELECT * FROM ng_events WHERE event_start >= CURRENT_DATE() ORDER BY event_start ASC LIMIT 4";
					$query = "SELECT * FROM ng_artists WHERE artist_type>0 ORDER BY artist_last_name ASC";
			
						$artistset = mysql_query($query, $mysamconnec);
						testquery($contentset);
						
						while($content = mysql_fetch_array($artistset)){
						
						
						echo"<a href=\"artist.php?id=";
						echo $content['artist_id'] . "&name=" . add_dashes($content['artist_first_name'] . "-" . $content['artist_last_name']);
						echo "\" class=\"";
						echo remove_spaces( $content['artist_last_name'] );
						echo " artistname \"><li>";
						echo strtoupper( $content['artist_first_name'] . " " . $content['artist_last_name'] );
						echo "</li></a>";
						}
					?>
						<!== put id="last" on the li of the last name eventually ==>
					<ul>
				</div>
			
		
			
			
			<div id="shelf">&nbsp;</div>
	
	</div class="article">

	<!--<div class="footer">
	
		<div id="tonight">
			<a href="#">
				<span id="tonightsevent">TONIGHT'S EVENT:</span> &nbsp; &nbsp; MARK VERABIOFF - CRACK ATTACK
			</a>
		</div>

	</div>-->

	<div class="footer" id="calendar">

		<?php
			
			//++++++++++++++     QUICKVIEW CALENDAR      ++++++++++++++++
			
			//$query = "SELECT * FROM ng_events WHERE event_start >= CURRENT_DATE() ORDER BY event_start ASC LIMIT 4";
			$query = "SELECT * FROM ng_events WHERE event_start >= CURRENT_DATE() ORDER BY event_start ASC LIMIT 4";
	
				$contentset = mysql_query($query, $mysamconnec);
				testquery($contentset);
				
				while($content = mysql_fetch_array($contentset)){
						
					echo "<div class=\"calitem\"><a href=\"/event.php?id=" . $content['event_id'] . "&name=" . add_dashes($content['event_title']) . "\">";
					echo "<span class=\"date " . nl_date_style($content['event_start']) . "\">" . nl_date($content['event_start']) . "</span>";
					echo "<span class=\"title\">";
					echo buildEventTitle( $content['event_title'] );
					echo "</span></a></div>";

			
				}

		?>
		
	</div>
	
	
	<div class="footer" id="ads">
	
	
	<?php 
		$query = "SELECT * FROM ng_ads ORDER BY ad_position DESC LIMIT 4";
	
				$contentset = mysql_query($query, $mysamconnec);
				testquery($contentset);
				$counter = 0;
				
				while($ad = mysql_fetch_array($contentset)){
				$counter++;
					echo "<div ";
					if ($counter > 3) {echo "class=\"last\"" ;}
					echo ">";
				
					if(!$ad['ad_event_id']) {
						
						if ($ad['ad_direct']==0) {
							echo "<a href=\"ad.php?id=";
							echo $ad['ad_id'];
							echo "\" class=\"advert\">";
						} else {
							echo "<a href=\"";
							echo $ad['ad_hyperlink'];
							echo "\" class=\"advert\" target=\"_blank\">";
						}
					
					echo "<p class=\"adimage\" style=\"background-image:url(" . $ad['ad_image_url'] . ")\">
									&nbsp;
									</p>
								<div class=\"adinfo over\">
								<p class=\"title\">" . $ad['ad_primary'] . "</p>
								<p class=\"date\">" . $ad['ad_secondary'] . "</p>
								</div>";
						
					} else {
						
						$query = "SELECT * FROM ng_events WHERE event_id=";
						$query .= $ad['ad_event_id'];
						$query .= " LIMIT 1";
							
						$eventresults = mysql_query($query, $mysamconnec);
						testquery($eventresults);
							
						$event = mysql_fetch_array($eventresults);
						
						
						echo "<a href=\"event.php?id=";
						echo $event['event_id'];
						echo "\" class=\"advert\">";
						echo "<p class=\"adimage\" style=\"background-image:url(" . $event['event_pic_url'] . ")\">
									&nbsp;
									</p>
								<div class=\"adinfo over\">
								<p class=\"title\">" . $event['event_title'] . "</p>
								<p class=\"date\">" . format_dates($event['event_start'] , $event['event_end']) . "</p>
								</div>";
						
					}
			
							
					echo "</a>
					</div>";
				
				}
	
		
		?>
		
		<p id="shelf"> &nbsp; </p>
	</div>
	
	
	 <div class="push"></div>
</div>
	
	
	<?php build_footer(); ?>
	</body>
</html>