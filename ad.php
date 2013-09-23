<?php

	require_once("includes/connection.php");
	require_once("includes/functions.php");
	
	$ad_id = $_GET['id'];
	if (!$ad_id) {
		header("Location: index.php");
	}
?>

<?php build_head("NIGHT GALLERY"); ?>


</head>
<body class="publicsite">
<div class="wid">


			<div class="header">
				<?php require_once("includes/open_sign.php") ?>
				
				<?php build_nav("") ?>
			</div>


	<div class="contents ad" id="ad">
		
		
		<!-- ++++++++++++++++++++++    LEFT PANE; PERSISTANT     +++++++++++++++++++++++++++ -->
		<div id="eventlx">
		


<?php
							//$query = "SELECT * FROM ng_events WHERE event_start >= CURRENT_DATE() ORDER BY event_start ASC LIMIT 4";
							$query = "SELECT * ";
							$query .= "FROM ng_ads ";
							$query .= "WHERE ad_id =";
							$query .= $ad_id;
							$query .= " LIMIT 1";
					
							$contentset = mysql_query($query, $mysamconnec);
							testquery($contentset);
								
								
							$thisad = mysql_fetch_array($contentset);
								
							?>
							
							
							<?php
				echo "<H6 id=\"leftheader\" class=\"eventtitle pagetitle\">";
				echo strtoupper( $thisad['ad_primary'] );
				echo "</H6>";
			?>
							
						<ul id="artists">
							
						</ul>
						
						<h4>
							<?php
								echo $thisad['ad_secondary'] ;
							?>
						</h4>
						
						
						
			<div id="presspane">
				<div id="pressrelease">
				<?php echo "<span class=\"pressrelease\">" . nl2br( html_entity_decode( $thisad['ad_pr'] )) . "</span>"; ?>
				</div>
			</div>
			
			<a id="externallink" href="<?php echo $thisad['ad_hyperlink']; ?>" target="_blank">
			MORE INFO
			</a>
			

		</div>
		
		
		<!-- ++++++++++++++++++++++    RIGHT PANE; CONTEXTUAL, SWITCHES BETWEEN "pane" DIVS     +++++++++++++++++++++++++++ -->
		<div id="eventright">
		
		
			<div id="eventrx">
		
				
				
				
				<div class="default images">
					
					<span><div class="artwork default" style="background-image:url(<?php echo $thisad['ad_image_url'] ?>)">
					&nbsp;
					</div></span>
								   
					
				</div>
				
				
				
			</div>
		</div>
		
	</div>



	<div class="push"></div>
</div>
	
	
	<?php build_footer(); ?>
	</body>
</html>