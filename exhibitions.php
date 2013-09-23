<?php

	require_once("includes/connection.php");
	require_once("includes/functions.php");
	
?>

<?php build_head("NIGHT GALLERY | CALENDAR"); ?>

<link href="/styles/upcoming.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body class="publicsite">
<div class="wid">


	<div class="header">
		
		<?php require_once("includes/open_sign.php") ?>
		
		<?php build_nav("exhibitions") ?>
		
	</div>


	<div class="contents" id="exhibitions">
		<!--<h3 class="sang">UPCOMING</h3>-->
		
		<div>
		
		
		
			<ul>
			
				<li><a href="current.php">CURRENT</a><ul>
			
				<?php
				$thereisshowonnow = false;
				//CURRENT MAIN SHOW
				$query = "SELECT * ";
				$query .= "FROM ng_events ";
				$query .= " WHERE event_end >= CURRENT_DATE() AND event_start <=CURRENT_DATE() AND event_type < 4 ORDER BY event_start DESC LIMIT 1";
				$current = mysql_query($query, $mysamconnec);
				testquery($current);
				while( $content = mysql_fetch_array($current) ){
				$thereisshowonnow = true;
				
				
				
				echo "<li id=\"mainevent\">";
				echo "<a href=\"event.php?id=" . $content['event_id'] . "\">";
				echo "<p class=\"title\">" . buildEventTitle( $content['event_title'] ) . "</p>";
				echo "<p class=\"date " . nl_date_style($content['event_start']) . "\">";
				echo format_dates($content['event_start'] , $content['event_end']) . " </p>";
				echo "</a>";
				echo "</li>";
				}
				
				?>
				
				<?php
				
				//CURRENT SMALLER SHOWS
				$query = "SELECT * ";
				$query .= "FROM ng_events ";
				$query .= " WHERE event_end >= CURRENT_DATE() AND event_start <= CURRENT_DATE() AND event_type = 2 ORDER BY event_start";
				
				$current = mysql_query($query, $mysamconnec);
				testquery($current);
				while( $content = mysql_fetch_array($current) ){
					$thereisshowonnow = true;
					echo "<a href=\"event.php?id=" . $content['event_id'] . "\" >";
				echo "<p class=\"title\">" . buildEventTitle( $content['event_title'] )  . "</p>";
				echo "<p class=\"date " . nl_date_style($content['event_start']) . "\">";
				echo format_dates($content['event_start'] , $content['event_end']) . " </p>";
				echo "</a>";
				echo "</li>";
				}
				
				
				if(!$thereisshowonnow) {
					echo "<li id=\"thereisnoshowonnow\">There is no show currently on</li>";
				}
				
				?>
				
				
				
				
				</ul>
				
			
			</li>
			<li><a href="upcoming.php">UPCOMING</a>
			
				<ul>
			
				<?php
				
				
				//UPCOMING
				//$query = "SELECT * FROM ng_events WHERE event_start >= CURRENT_DATE() ORDER BY event_start ASCENDING";
				$query = "SELECT * FROM ng_events ";
				$query .= "WHERE event_start > CURRENT_DATE() AND event_type < 4  ORDER BY event_start ASC LIMIT 3";
				
				//TESTTEST
				$allevents2 = mysql_query($query, $mysamconnec);
				testquery($allevents2);
				
				$counter = 0;
				
				while( $content = mysql_fetch_array($allevents2) ){
				
					$counter++;
					
					if( $counter == 1 && $thereisshowonnow == false) {
						echo "<li id=\"mainevent\">";
					} else {
						echo "<li>";
					}
					
					//the following runs each time:
						//spit out opening anchor tag
					echo "<a href=\"event.php?id=" . $content['event_id'] . "\">";
					echo "<p class=\"title\">" . buildEventTitle( $content['event_title'] )  . "</p>";
					echo "<p class=\"date " . nl_date_style($content['event_start']) . "\">";
					
					echo "OPENS " . nl_date($content['event_start']) . "<br>";
					echo format_dates($content['event_start'] , $content['event_end']) . " </p>";
					echo "</a>";
					echo "</li>";
				
				
					}
				
				if( $counter==0 ){
					echo "<li>There are no future shows scheduled.</li>";
					
				}
			?>
			
			
			
				</ul>
			
			</li>
			<li class="desktoplink"><a href="previous.php">PREVIOUS</a>
					
			</ul>
		
		
		
		
		
		

		
		
		</div>
		<div id="shelf"></div>
	</div>
	
	


	 <div class="push"></div>
</div>
	
	
	<?php build_footer(); ?>
	</body>
</html>