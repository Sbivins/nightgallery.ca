<?php
require_once("connection.php");


function mysql_prep( $value ){
	$magic_quotes_on = get_magic_quotes_gpc();
	$new_enough_php = function_exists( "mysql_real_escape_string" );
	if($new_enough_php) { //PHP v. 4.3.0 or higher
		//undo any magic quote effect so that mysql_real_escape_string can work
		if($magic_quotes_on) { $value = stripslashes( $value ); }
		$value = mysql_real_escape_string($value);
	} else { //PHP versions older than v. 4.3.0
		//if magic quotes aren't already on then add slashes manually
		if(!$magic_quotes_on) { $value = addslashes( $value ); }
		//if magic quotes are active, then slashes already exist
	}
	return $value;
}


function testquery($result) {
if(!$result){
		die("Database Query Failed " . mysql_error());
	}
}

function getcolor($colorid) {
$colorname = "";
	if($colorid == '1') {
		$color = "seafoam";
	} else if($colorid == '2') {
		$color = "cerulean";
	} else if($colorid == '3') {
		$color = "sunshine";
	} else if($colorid == '4') {
		$color = "mauve";
	} else if($colorid == '5') {
		$color = "lavender";
	} else {
		$colorname = NULL;
	}
	
	return $colorname;
	
}

function open_sign($sql_connection) {
	$query = "SELECT * FROM ng_opencl ORDER BY id DESC";

	$contentset = mysql_query($query, $sql_connection);
	testquery($contentset);
	
	while($content = mysql_fetch_array($contentset)){

		if ($content['open'] == 1) {
			echo "<h2><img src=\"img/openbig.gif\"></h2>";
		}
		else {
			echo "<h2><img src=\"img/closedbig.gif\"></h2>";
		}
	}
	return NULL;
}

/*__________________________________________
------------ NEXT LEVEL DATE ---------------
__________________________________________*/

function nl_date( $sql_date ) {
	
	$timestamp = strtotime($sql_date); //UNIX TIMESTAMP OF SQL DATE
	$now = time(); //UNIX TIMESTAMP OF NOW
	
	
	//sees if the thing is on TODAY's date
	$format_today_compare = strftime("%b %e", $now);
	$format_date_compare = strftime("%b %e", $timestamp);
	
	$tomorrow = mktime(0,0,0,date("m"),date("d")+1,date("Y"));
	
	if ($format_today_compare ==  $format_date_compare) { //IS IT TODAY
		$formatteddate = "TONIGHT";
	}
	else if (strftime ("%b %e", $tomorrow) == strftime ("%b %e", $timestamp)) { //IS IT TOMORROW
		$formatteddate = "TOMORROW";
	} else if ( $timestamp - $now < 432000 && $timestamp - $now > 0) { //IS IT THIS WEEK?
		//then it's within a week
		$format = "%A";
		$formatteddate = strftime ($format, $timestamp);
	} else {
		//then it's over a week
		$format = "%a, %b %e";
		$formatteddate = strftime ($format, $timestamp);
	}
	
	return strtoupper($formatteddate);
	
}

function mysql_ready() {
	strftime("%Y-%M-%D %T", $timestamp); 
}

/*__________________________________________
-------------- NL DATE STYLE ---------------
__________________________________________*/



function nl_date_style( $sql_date ) {
	
	$timestamp = strtotime($sql_date); //UNIX TIMESTAMP OF SQL DATE
	$now = time(); //UNIX TIMESTAMP OF NOW
	
	
	//sees if the thing is on TODAY's date
	$format_today_compare = strftime("%b %e", $now);
	$format_date_compare = strftime("%b %e", $timestamp);
	
	$tomorrow = mktime(0,0,0,date("m"),date("d")+1,date("Y"));
	
	$formatteddate = "soon";
	
	if ($format_today_compare ==  $format_date_compare) { //IS IT TODAY
		$formatteddate = "tonight";
	}
	else if (strftime ("%b %e", $tomorrow) == strftime ("%b %e", $timestamp)) { //IS IT TOMORROW

	} else if ( $timestamp - $now < 432000 && $timestamp - $now > 0) { //IS IT THIS WEEK?
		//then it's within a week
	} else {
		//then it's over a week
	}
	
	return $formatteddate;
	
}



/*__________________________________________
------------ BASIC EVENT DATE --------------
__________________________________________*/


function format_date( $sql_date ) {
	
	$timestamp = strtotime($sql_date); //UNIX TIMESTAMP OF SQL DATE

		//then it's over a week
		$format = "%B %e";
		$formatteddate = strftime ($format, $timestamp);
		
	return strtoupper($formatteddate);
}


function get_year( $sql_date ) {
	$timestamp = strtotime($sql_date); //UNIX TIMESTAMP OF SQL DATE
		//then it's over a week
		$format = "%Y";
		$formatteddate = strftime ($format, $timestamp);
	return strtoupper($formatteddate);
}



function format_dates( $sql_date1 , $sql_date2 ) {

	$year = ", %Y";
	$month = "%b ";
	$day = " %e";
	$hr24 = "%H";
	$hr12 = " %l";
	$ampm = "%P ";
	$comma = ",";
	
	$timecomma = "";
	$month1 = "";
	$month2 = "";
	$day1 ="";
	$day2 ="";
	$hr1 = "";
	$hr2 = "";
	$ampm1 = "";
	$ampm2 = "";
	$year1 = "";
	$year2 = "";
	
	$timestamp1 = strtotime($sql_date1);
	$timestamp2 = strtotime($sql_date2); //THIS MUCH IS WORKING
	
	
	
	if (strftime ($year, $timestamp1) == strftime ($year, $timestamp2)) {
	//echo "the years are the same";
	} else {
	//echo "the years are different!!!!";
		$year1 = $year;
		$year2 = $year; //this just means they are both getting assigned
		//to be spit out as separate things in the format,
		//not that they are the same.
	}
	if (strftime ($month, $timestamp1) == strftime ($month, $timestamp2)) {
		$month1 = $month;
		//echo "the months are the same";
		
		if (strftime ($day, $timestamp1) == strftime ($day, $timestamp2)) {
		$day1 = $day;
		//echo "so get this: LITERALLY SAME DATE";
						if (strftime ($hr24, $timestamp1) == strftime ($hr24, $timestamp2)) {
							//echo "the hours are the same!";
							$timecomma = $comma;
							$hr1 = $hr12;
						} else {
							$timecomma = $comma;
							//echo "the hours are different!";
							$hr1 = $hr12;
							$hr2 = $hr12;
							if (strftime ($ampm, $timestamp1) == strftime ($ampm, $timestamp2)) {
							//echo "same half of the day!";
								$ampm2 = $ampm;
							} else {
							//echo "different half of the day!";
								$ampm1 = $ampm2 = $ampm;
							}	
						}
		} else {
		//echo "the months are the same, but days are different"; //will be this way a lot;
			$day1 = $day;
			$day2 = $day;
		}
		
	} else {
	//echo "the months are different, thus, the days are different!";
		$month1 = $month;
		$month2 = $month;
		$day1 = $day;
		$day2 = $day;
		
	}

	
	$format1 = $month1 . $day1 . $timecomma . $hr1 . $ampm1 . $year1;
	$format2 = $month2 . $day2 . $hr2 . $ampm2 . $year2;
	//echo $format1 . $format2;
	$formatteddate1 = strftime ($format1, $timestamp1);
	$formatteddate2 = strftime ($format2, $timestamp2);
	$formatteddate = $formatteddate1 . " - " . $formatteddate2;
	return strtoupper($formatteddate);
	
}



function add_dashes ($string) {
	$string = str_ireplace(" ", "_", $string);
	$string = str_ireplace("_-_", "-", $string);
	$string = str_ireplace(":_", ":", $string);
	return $string;
}

function remove_spaces ($string) {
	$string = str_ireplace(" ", "", $string);
	$string = str_ireplace("", "", $string);
	$string = str_ireplace("", "", $string);
	return $string;
}


function build_head($string) {
	
echo "<html>
<head>
<title>";
echo $string;
echo "</title>
    <meta charset=\"UTF-8\">
	<link rel=\"icon\" type=\"image/png\" href=\"/nightgallery/img/favicon.png\">
	<link rel=\"stylesheet\" type=\"text/css\" href=\"http://yui.yahooapis.com/2.9.0/build/reset/reset-min.css\" media=\"screen\">
	
	
	
	
	<!--[if IE]>
	<link rel=\"stylesheet\" type=\"text/css\" href=\"/nightgallery/styles/ie.css\" media=\"screen\" />
	<![endif]-->
	
	<!--[if !IE]><!-->
	<link href=\"styles/style.css\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\" />
	<link href=\"styles/artist.css\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\" />
	<link href=\"styles/event.css\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\" />
	<link href=\"styles/previous.css\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\" />
	<link rel=\"stylesheet\" type=\"text/css\" href=\"http://www.kelvinluck.com/assets/jquery/jScrollPane/styles/jScrollPane.css\" media=\"screen\" />
	<!--<![endif]-->
	
	
	<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js\"></script>
	<script type=\"text/javascript\" src=\"http://cherne.net/brian/resources/jquery.hoverIntent.js\"></script>
	<script type=\"text/javascript\" src=\"http://www.kelvinluck.com/assets/jquery/jScrollPane/scripts/jScrollPane.js\"></script>
	<script type=\"text/javascript\" src=\"js/artists_drop_down.js\"> </script>
	<script type=\"text/javascript\" src=\"js/ads_pop_out.js\"> </script>
	<script type=\"text/javascript\" src=\"js/artist_page.js\"> </script>
	<script type=\"text/javascript\" src=\"js/event_page.js\"> </script>
	<script type=\"text/javascript\" src=\"js/thumber.js\"> </script>
	<script type=\"text/javascript\" src=\"js/global.js\"> </script>
	<script type=\"text/javascript\" src=\"js/preload.js\"> </script>";
	
}


function insertClassIfPage($pagename, $current_page_name) {
	$classname = "thispage";
	
	if($pagename == $current_page_name) {
		return "thispage";
	} else {
		return "";
	}
	
}


function build_nav( $current_page_name ){


	echo "
	<h1 class=\"logo\" alt=\"NIGHT GALLERY\">
			<a href=\"/\">Night Gallery</a>
			</h1>
	
	";
	
	echo "<div class=\"nav\" id=\"nav\">
			<ul class=\"left\">
				<a href=\"\" class=\"" . insertClassIfPage("current", $current_page_name) . "\"><li  class=\"exhiblink edge\">EXHIBITIONS</li></a>
				<ul id=\"exhibdrop\">
					<div class=\"blacklinebox\">
					<a href=\"current.php\" class=\"" . insertClassIfPage("current", $current_page_name) . "\"><li>CURRENT</li></a>
					<a href=\"upcoming.php\" class=\"" . insertClassIfPage("upcoming", $current_page_name) . "\"><li>UPCOMING</li></a>
					<a href=\"previous.php\" class=\"" . insertClassIfPage("previous", $current_page_name) . "\"><li>PREVIOUS</li></a>
					</div>
				</ul>
				<a href=\"artists.php\" class=\"" . insertClassIfPage("artists", $current_page_name) . "\"><li>ARTISTS</li></a>
				<a href=\"news.php\" class=\"" . insertClassIfPage("news", $current_page_name) . "\"><li>NEWS</li></a>
				
			</ul>
			
			<ul class=\"right\">
				<a href=\"fairs.php\" class=\"" . insertClassIfPage("fairs", $current_page_name) . "\"><li>FAIRS</li></a>
				<a href=\"info.php\" class=\"" . insertClassIfPage("info", $current_page_name) . "\"><li>INFO</li></a>
				<a href=\"/private\" class=\"" . insertClassIfPage("private", $current_page_name) . "\"><li class=\"edge\">PRIVATE</li></a>
			</ul>
		</div>";
}

function get_word_time_of_day() {
	
}


function prep_string_for_filename($string) {
$new_string = ereg_replace("[^A-Za-z0-9]", "", $string );
return $new_string;
}


function truncate($string) {
	$new = substr($string, 0, 20);
	return $new . "...";
}


function build_admin_nav() {
	echo "<a href=\"admin.php\">
					<h1 class=\"sang\" alt=\"NIGHT GALLERY\">
					
					</h1>
				</a>
				
				<div class=\"nav\" id=\"nav\">
			<ul class=\"left\">
				<a href=\"../\" ><li class=\"edge\">NIGHT GALLERY</li></a>
				<a href=\"../private\" ><li>PRIVATE</li></a>
				
			</ul>
			
			<ul class=\"right\">
				
				<a href=\"logout.php\" ><li class=\"edge\">LOGOUT</li></a>
			</ul>
			</div>";
}

function build_footer() {
echo "<div class=\"footer\" id=\"fine\">
		<p>COPYRIGHT &#169; 2012 NIGHT GALLERY All Rights Reserved. <a>Terms & Conditions</a></p>
		</div>";
}

function br2nl($string){ 
  $return=eregi_replace('<br[[:space:]]*/?'. 
    '[[:space:]]*>',chr(13).chr(10),$string); 
  return $return; 
}


?>