<?php
	require_once("connection.php");
	require_once("functions.php");

$title = mysql_prep( $_POST['title'] );
$body = mysql_prep( $_POST['body'] );
$color = mysql_prep( $_POST['color'] );

$query = "INSERT INTO posts (title, body, color) VALUES ('$title', '$body' , '$color')";

if (mysql_query($query, $mysamconnec)) {
//success
	if(isset($_GET['target'])) {
		header("Location: staff.php");
		exit;
	} else {
		header("Location: index.php");
		exit;
	}
} else {
	//failed
	echo "<p>subject creation failed</p>";
	echo "<p>" . mysql_error() . "</p>";
}

mysql_close($mysamconnec);
?>