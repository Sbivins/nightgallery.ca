<?php

	require_once("../includes/sessions.php");
	require_once("../includes/connection.php");
	require_once("../includes/functions.php");

	confirm_logged_in();
	confirm_top_level();

	$forward = $back = $artwork = $eventshot = false;
	
	$return = $_GET['return'];
	$returntoartist = $_GET['returntoartist'];
	
	if (isset($_GET['back_artwork'])) {
		$id = mysql_prep( $_GET['back_artwork'] );
		$back = true;
		$artwork = true;
	} else if (isset($_GET['forward_artwork'])) {
		$id = mysql_prep( $_GET['forward_artwork'] );
		$forward = true;
		$artwork = true;
	} else if (isset($_GET['back_eventshot'])) {
		$id = mysql_prep( $_GET['back_eventshot'] );
		$back = true;
		$eventshot = true;
	} else if (isset($_GET['forward_eventshot'])){
		$id = mysql_prep( $_GET['forward_eventshot'] );
		$forward = true;
		$eventshot = true;
	} else if (isset($_GET['back_ad'])) {
		$id = mysql_prep( $_GET['back_ad'] );
		$back = true;
		$ad = true;
	} else if (isset($_GET['forward_ad'])){
		$id = mysql_prep( $_GET['forward_ad'] );
		$forward = true;
		$ad = true;
	} else {
		header("Location: admin.php");
		exit;
	}
	
	$pos = time();
	
	if ($back) {
		$pos = $pos - ($pos*2);
	}
	
	$query = "";
	
	if ($artwork) {
		if($returntoartist > 0) { $redirect = "Location: edit_artist.php"; 
		$redirect .= "?id=" . $returntoartist;} 
		if($return > 0) { $redirect = "Location: add_artwork.php"; 
		$redirect .= "?artist_filter=" . $return; }
		$query = "UPDATE ng_artworks ";
		$query .= "SET ";
		$query .= "artwork_order_pos = '{$pos}' ";
		$query .= "WHERE artwork_id = {$id}";
	} else if ($eventshot) {
		$redirect = "Location: edit_event.php";
		if($return > 0) {$redirect .= "?id=" . $return; }
		$query = "UPDATE ng_event_shots ";
		$query .= "SET ";
		$query .= "event_shot_order_pos = '{$pos}' ";
		$query .= "WHERE event_shot_id = {$id}";
	} else {
		$redirect = "Location: manage_ads.php";
		$query = "UPDATE ng_ads ";
		$query .= "SET ";
		$query .= "ad_position = '{$pos}' ";
		$query .= "WHERE ad_id = {$id}";
	}
	
				
	$content = mysql_query($query, $mysamconnec);
	testquery($content);
	
	if(mysql_affected_rows()==1){
		header($redirect);
		exit;
	} else {
		header($redirect);
		exit;
	}
	
	mysql_close($mysamconnec);
	
?>