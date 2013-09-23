<?php
	require_once("../includes/sessions.php");
	require_once("../includes/connection.php");
	require_once("../includes/functions.php");

	confirm_logged_in();
	confirm_top_level();

if (isset($_GET['id'])) {
		$id = $_GET['id'];
	} else {
		header("Location: add_events.php");
		exit;
	}	
	
$id = mysql_prep( $_GET['id'] );


$query = "DELETE FROM ng_events WHERE event_id = '$id'";
$content = mysql_query($query, $mysamconnec);
testquery($content);

if(mysql_affected_rows()==1){
	header("Location: add_events.php");
	exit;
} else {
	header("Location: add_events.php");
	exit;
}

mysql_close($mysamconnec);
?>