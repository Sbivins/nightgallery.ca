<?php


function build_private_head($string) {
	
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
	<link href=\"/nightgallery/styles/style.css\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\" />
	<link href=\"/nightgallery/styles/artist.css\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\" />
	<link href=\"/nightgallery/styles/previous.css\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\" />
	<link rel=\"stylesheet\" type=\"text/css\" href=\"http://www.kelvinluck.com/assets/jquery/jScrollPane/styles/jScrollPane.css\" media=\"screen\" />
	<!--<![endif]-->
	
	
	<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js\"></script>
	<script type=\"text/javascript\" src=\"http://cherne.net/brian/resources/jquery.hoverIntent.js\"></script>
	<script type=\"text/javascript\" src=\"http://www.kelvinluck.com/assets/jquery/jScrollPane/scripts/jScrollPane.js\"></script>
	<script type=\"text/javascript\" src=\"/nightgallery/js/artists_drop_down.js\"> </script>
	<script type=\"text/javascript\" src=\"/nightgallery/js/ads_pop_out.js\"> </script>
	<script type=\"text/javascript\" src=\"/nightgallery/js/artist_page.js\"> </script>
	<script type=\"text/javascript\" src=\"/nightgallery/js/global.js\"> </script>";
	
}



function build_private_nav( $current_page_name ){
	
	echo "<a href=\"/private\">
				<h1 class=\"sang\" alt=\"NIGHT GALLERY\">
				Private Gallery
				</h1>
			</a>";
	
	
	echo "<div class=\"nav\" id=\"nav\">
			<ul class=\"left\">
				<a href=\"/\" class=\"" . insertClassIfPage("nightgallery", $current_page_name) . "\"><li class=\"edge\">NIGHT GALLERY</li></a>
				<a href=\"newwork.php\" class=\"" . insertClassIfPage("newwork", $current_page_name) . "\"><li>NEW WORK</li></a>
			</ul>
			
			<ul class=\"right\">
				<a href=\"/private/\" class=\"" . insertClassIfPage("inventory", $current_page_name) . "\"><li class=\"artistslink\">INVENTORY</li></a>
				<a href=\"contact.php\" class=\"" . insertClassIfPage("contact", $current_page_name) . "\"><li>CONTACT</li></a>
				<a href=\"logout.php\" class=\"" . insertClassIfPage("logout", $current_page_name) . "\"><li class=\"edge\">LOGOUT</li></a>
			</ul>
		</div>";
}


function build_admin_toolbar($current_page_name) {

echo 	
"<a href=\"add_newsitem.php\" class=\"" . insertClassIfPage("addnewsitem", $current_page_name) . "\">Post News</a>
<a href=\"add_artwork.php\" class=\"" . insertClassIfPage("addartwork", $current_page_name) . "\">Add Artwork</a>
		<a href=\"add_artist.php\" class=\"" . insertClassIfPage("addartist", $current_page_name) . "\">Add Artist</a>
		<a href=\"new_user.php\" class=\"" . insertClassIfPage("newuser", $current_page_name) . "\">Add Private User</a>
		<a href=\"\" class=\"" . insertClassIfPage("newevent", $current_page_name) . "\">New Event</a>
		<a href=\"\" class=\"" . insertClassIfPage("newartfair", $current_page_name) . "\">New Art Fair</a>
		<a href=\"\" class=\"" . insertClassIfPage("newad", $current_page_name) . "\">New Ad</a>
		<a href=\"\" class=\"" . insertClassIfPage("upload", $current_page_name) . "\">Upload Photos</a>";

	
}



?>