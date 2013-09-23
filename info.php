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


	<div class="contents" id="info">
		<?php
			$query = "SELECT * FROM ng_infopage WHERE info_id=0";
			$query .= " LIMIT 1";
			$contentset = mysql_query($query, $mysamconnec);
			testquery($contentset);
			$info = mysql_fetch_array($contentset);
		?>
		
		<div id="info-left">
		
			<?php echo nl2br( html_entity_decode( $info['info_field'] )) ; ?>	
			
			
			<br/><br/>
			
			<img src="img/yunheemin.jpg" alt="yunheemin" width="402" height="300" /><br/><br/>
			The new Night Gallery space. Windows by Yunhee Min.
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
		
		
			<div class="subscriptionform">
				<form method="post" action="http://ymlp.com/subscribe.php?id=gjeshmmgmgj" target="_blank">
					<input name="YMP0" size="20" type="text" placeholder="Email"/><br/>
					<input name="YMP1" size="20" type="text" placeholder="First Name"/><br/>
					<input name="YMP2" size="20" type="text" placeholder="Last Name"/><br/>
					<input value="Subscribe" type="submit" value='Open default action file' 
	onclick="this.form.target='_blank';return true;" />
				</form>
			</div>
		
		
		</div>
		
	</div>
	
	


		
	 <div class="push"></div>
</div>
	
	
	<?php build_footer(); ?>
	</body>
</html>