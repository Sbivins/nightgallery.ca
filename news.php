<?php

	require_once("includes/connection.php");
	require_once("includes/functions.php");
	
	if (isset($_GET['page'])) {
		$page = $_GET['page'];
	} else {
		$page = 1;
	}
	
	$query = "SELECT * FROM ng_newsitem ";
	$query .= " ORDER BY newsitem_date DESC";
				
	$counter = mysql_query($query, $mysamconnec);
	testquery($counter);
				
	$postsperpage = 4;
	$startpost = ($page - 1) * $postsperpage + 1;
	$endpost = $startpost + $postsperpage;
	
	//quickly count how many posts there are in total. if there are 27, 7 pages would work but 8 wouldnt;	
	$totalcount = 0;			
	while( $counter2 = mysql_fetch_array($counter) ){
		$totalcount++;
	}
	
	
	
	
	if ( $totalcount <= ($postsperpage * $page) && $totalcount >= ($postsperpage * $page - 3) ) {
		$lastpage = true;
	}
	
	
	if ( ($postsperpage * $page) - 3 > $totalcount ) {
	header("Location: /news.php");
		exit;
	}
	
	
?>

<?php build_head("NIGHT GALLERY | NEWS"); ?>

<link href="/styles/artists.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body class="publicsite">
<div class="wid">


	<div class="header">
		
		<?php require_once("includes/open_sign.php") ?>
		
		<?php build_nav("news") ?>
		
	</div>



	<div class="contents" id="newspage">
		<!--<h3 class="sang">ARTISTS</h3>-->
		
		<ul>
			<?php
				
				
			/* THIS IS WHERE THE ARTWORKS ARE SHOWN ON THIS PAGE. */
				$query = "SELECT * FROM ng_newsitem ";
				$query .= " ORDER BY newsitem_date DESC";
				
				$contentset = mysql_query($query, $mysamconnec);
				testquery($contentset);
				
				$count = 0;
				
				while($content = mysql_fetch_array($contentset)){
					
					$count++;
					
					if($count >= $startpost && $count < $endpost){
						
						
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
					echo "<p class=\"newsitembody\">" . nl2br( html_entity_decode( $content['newsitem_body'] )) . "<p>";
					echo "</div>";
					echo "</li>";
						
					} //end of if statement that checks for post number
					
					
				}
				
				echo "<li id=\"pageswitcher\">";
				
				if(!$lastpage) {
					echo "<a href=\"/news.php?page=" . ($page + 1) . "\">";
					echo "OLDER POSTS";
					echo "</a>";
				} else {
					echo "<span class=\"grey\">OLDER POSTS</span>";
				}
				
				if($page > 1){
					echo " | ";
					echo "<a href=\"/news.php?page=" . ($page - 1) . "\">";
					echo "NEWER POSTS";
					echo "</a>";
				}
				
				
					
				echo "</li>";
				
			?>
			
			</ul>
			
	</div>
	
	

	<div class="push"></div>
</div>
	
	
	<?php build_footer(); ?>
	</body>
</html>