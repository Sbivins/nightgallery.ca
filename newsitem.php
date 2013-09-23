<?php

	require_once("includes/connection.php");
	require_once("includes/functions.php");
	
	$id = $_GET['id'];
	if (!$id) {
		header("Location: index.php");
	}
	
	$query = "SELECT * FROM ng_newsitem WHERE newsitem_id = ";
	$query .= "{$id}";
	$query .= " ORDER BY newsitem_date DESC LIMIT 1";
	$contentset = mysql_query($query, $mysamconnec);
	testquery($contentset);
	$content = mysql_fetch_array($contentset);
	
?>

<?php build_head( "NIGHT GALLERY NEWS | " . $content['newsitem_title'] ); ?>

</head>
<body class="publicsite">
<div class="wid">


	<div class="header">
		
		<?php require_once("includes/open_sign.php") ?>
		
		
		<?php build_nav("") ?>
		
	</div>
	
	
	
	
	<div class="contents" id="newspage">
		<!--<h3 class="sang">ARTISTS</h3>-->
		
		<ul>
			<?php
			
			/* THIS IS WHERE THE ARTWORKS ARE SHOWN ON THIS PAGE. */
				$query = "SELECT * FROM ng_newsitem WHERE newsitem_id = ";
				$query .= "{$id}";
				$query .= " ORDER BY newsitem_date DESC LIMIT 1";
				
				$contentset = mysql_query($query, $mysamconnec);
				testquery($contentset);
				
				while($content = mysql_fetch_array($contentset)){
				
					echo "<li>";
					echo "<a href=\"newsitem.php?id=" . $content['newsitem_id'] . "\">";
					echo "<p class=\"newsitemdate\">" . format_date( $content['newsitem_date'] ) . "</p>";
					
					echo "<div class=\"newsitemcontent\">";
					
					if($content['newsitem_url']){
						echo "</a><a href=\"" . $content['newsitem_url'] . "\" target=\"_blank\">";
					}
					
					if($content['newsitem_title']){
						echo "<h3>" . strtoupper($content['newsitem_title']) . "</h3>";
						
					}
					
					echo "</a>";
					
						if($content['newsitem_url']){
							echo "<a href=\"" . $content['newsitem_url'] . "\" target=\"_blank\">";
						}
					
					if($content['newsitem_photo']) {
						echo "<img class=\"pic\" src=\"" . $content['newsitem_photo'] . "\"> &nbsp; ";
					}
					
						if($content['newsitem_url']){
							echo "</a>";
						}
					
					//echo "<p class=\"artistbio\">" . stripslashes( nl2br( $content['newsitem_body'] )) . "<p>";
					echo "<p class=\"newsitembody\">" . nl2br( html_entity_decode(  $content['newsitem_body'] )) . "<p>";
					echo "</div>";
					echo "</li>";
				}
				
			?>
			</ul>
			
	</div>
	
	
	<div class="push"></div>
</div>
	
	
	<?php build_footer(); ?>
	</body>
</html>