<?php

	require_once("includes/connection.php");
	require_once("includes/functions.php");
	
?>

<?php build_head("NIGHT GALLERY | INFO"); ?>

<link href="/nightgallery/styles/artists.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body class="publicsite">
<div class="wid">


	<div class="header">
		
		<?php require_once("includes/open_sign.php") ?>
		
		<?php build_nav("info") ?>
		
	</div>


	<div class="header">
		<?php
			$query = "SELECT * FROM ng_infopage WHERE info_id=0";
			$query .= " LIMIT 1";
			$contentset = mysql_query($query, $mysamconnec);
			testquery($contentset);
			$info = mysql_fetch_array($contentset);
		?>
		
		<div id="info-left">
		
			<?php echo stripslashes( nl2br( $info['info_field'] )) ; ?>	
		</div>
		
		
		<div id="info-right">
			<p><a href="press.php">PRESS</a></p>
			<p><a href="nightpapers.php">NIGHT PAPERS</a></p>
			<br>
		
			<?php 
			
			for($i=1; $i<5; $i++) {
				
				$info_url = "info_url_" . $i;
				$info_link = "info_link_". $i;
				
				if($info[$info_link]) {
					
					echo "<p>";
					echo "<a href=\"" . $info[$info_url] . "\">";
					echo $info[$info_link];
					echo "</a>";
					echo "</p>";
					
					
				}
				
								
			}
			
			
			
			?>
		
		</div>
		
	</div>
	
	


		<?php build_footer(); ?>
	 </div>
	 
	</body>
</html>