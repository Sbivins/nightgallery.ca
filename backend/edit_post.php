<?php
	require_once("sessions.php");
	require_once("connection.php");
	require_once("functions.php");
	confirm_logged_in();

if (!empty($_GET)) {
	$id = mysql_prep( $_GET['id'] );

	$query = "SELECT * FROM posts WHERE id = '{$id}'";
	
	$contentset = mysql_query($query, $mysamconnec);
	$content = mysql_fetch_array($contentset);
	//success
	
	} else {
	//failed
}

?>

<html>
	<head>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans+Mono' rel='stylesheet' type='text/css'>
		<title>HELLO</title>
		<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/reset/reset-min.css">
		      <link href="styles/styles.css" rel="stylesheet" type="text/css">
		      
	</head>
	<body>
	
	<article>
	<section id="userbar">Hello, <?php echo $_SESSION['username']; ?>  <a href="logout.php">Logout</a> <a href="new_user.php">Add User</a> </section>
	<form action="update_post.php?id=<?php echo $content['id']; ?>" method="post">
		<p><input type="text" placeholder="Enter a Title" name="title" value="<?php echo $content['title']; ?>" id="title" size="60"/></p>
		<p>
			<textarea name="body" id="text" placeholder="Start writing your post..." value="<?php echo $content['body']; ?>" id="body" rows="4" cols="100" ></textarea></p>
		<p> Color:
		<select name="color">
			<option value="blank" <?php if($content['color'] == 'blank') { echo " selected=\"selected\""; } ?>>None</option>
			<option value="color1" <?php if($content['color'] == 'color1') { echo " selected=\"selected\""; } ?>>Seafoam</option>
			<option value="color2" <?php if($content['color'] == 'color2') { echo " selected=\"selected\""; } ?>>Cerulean</option>
			<option value="color3" <?php if($content['color'] == 'color3') { echo " selected=\"selected\""; } ?>>Sunshine</option>
			<option value="color4" <?php if($content['color'] == 'color4') { echo " selected=\"selected\""; } ?>>Mauve</option>
			<option value="color5" <?php if($content['color'] == 'color5') { echo " selected=\"selected\""; } ?>>Lavender</option>
  
		</select>
		</p>
		<p>
			<input type="submit" value="Submit" id="submit"> &nbsp; <a href="staff.php">Cancel</a> </p>
	</form>
	
	 <footer>Copyright 2012 No Parlay</footer>
	 <article>
	 
	</body>
</html>

<?php mysql_close($mysamconnec); ?>