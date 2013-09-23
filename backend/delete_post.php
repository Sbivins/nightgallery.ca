<?php
	require_once("connection.php");
	require_once("functions.php");

$id = mysql_prep( $_GET['id'] );

$query = "DELETE FROM posts WHERE id = '$id'";

mysql_query($query, $mysamconnec);

if(mysql_affected_rows()==1){
	header("Location: staff.php");
	exit;
}


mysql_close($mysamconnec);
?>