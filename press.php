<?php

	require_once("includes/connection.php");
	require_once("includes/functions.php");
	
?>

<?php build_head("NIGHT GALLERY | PRESS"); ?>

<link href="/nightgallery/styles/artists.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body class="publicsite">
<div class="wid">


	<div class="header">
		
		<?php require_once("includes/open_sign.php") ?>
		
		<?php build_nav("") ?>
		
	</div>


	<div class="contents" id="press">
		<?php
			$query = "SELECT * FROM ng_gpress ORDER BY gpress_date DESC";
			$contentset = mysql_query($query, $mysamconnec);
			testquery($contentset);
		?>
		
		<div class="simple_list">
			<ul>
			
<?
				while($content = mysql_fetch_array($contentset)) {
					
					echo "<li>";
					echo "<a href=\"" . ngconvert( $content['gpress_url'] ) . "\" target=\"blank\">";
					echo $content['gpress_title'] . " / " . $content['gpress_outlet'] . " / " . $content['gpress_writer'];
					echo "</a>";
					echo "</li>";
					
					
				}
			?>
			<ul>
		</div>
		
		
	</div>
	
		
<div class="push"></div>
</div>
	
	
	<?php build_footer(); ?>
	</body>
</html>