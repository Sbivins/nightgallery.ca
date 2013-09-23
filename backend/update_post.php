<?php
	require_once("connection.php");
	require_once("functions.php");
if (!empty($_GET)) {
	$id = mysql_prep( $_GET['id'] );

	$query = "SELECT * FROM posts WHERE id = '{$id}'";
	
	$contentset = mysql_query($query, $mysamconnec);
	$content = mysql_fetch_array($contentset);
	//success
	
	} else {
	//failed
}
$title = mysql_prep( $_POST['title'] );
$body = mysql_prep( $_POST['body'] );
$color = mysql_prep( $_POST['color'] );
$query = "UPDATE posts SET title = '{$title}', body = '{$body}', color = '{$color}' WHERE id={$id}";
			$result = mysql_query($query, $mysamconnec);
			if (mysql_affected_rows()==1){
				header("Location: staff.php");
				exit;
			} else {
				$message = "Update failed";
			}
mysql_close($mysamconnec);
?>