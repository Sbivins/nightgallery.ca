<?php
	session_start();
	require_once("../includes/connection.php");
	require_once("../includes/functions.php");
	
	//RUNS IF THERE IS CURRENTLY A SESSION
	if(isset($_SESSION['user_id'])) {
		header("Location: index.php");
		exit;
	}
	
	//RUNS IF THE SUBMIT BUTTON WAS PRESSED
	if (isset($_POST['submit'])){
		
		$required_fields = array('username','password');
	
		foreach($required_fields as $required) {
			if (!isset($_POST[$required]) || empty($_POST[$required]) ){
				$errors[] = $required;
			}
		}
		
		if(empty($errors)) {
			//GRAB THE USERNAME AND PASSWORD AND AUTHENTICATE.
			$username = trim(mysql_prep($_POST['username']));
			$password = trim(mysql_prep($_POST['password']));
			$hashed_password = sha1($password);
		
			$query = "SELECT id, username, user_level FROM ng_users WHERE username = '{$username}' AND hashed_password = '{$hashed_password}'";
			$results = mysql_query($query, $mysamconnec);
			if (mysql_num_rows($results) == 1) {
				$found_user = mysql_fetch_array($results);
				$_SESSION['user_id'] = $found_user['id'];
				$_SESSION['username'] = $found_user['username'];
				$_SESSION['user_level'] = $found_user['user_level'];
				if ($found_user['user_level'] == 5 ) {
					header("Location: admin.php");
					exit;
				} else {
					header("Location: ../private/");
					exit;
				}
				
			} else {
				$message .= "The entered username and password did not match.";
			}
			
		} else {
			foreach($errors as $error){
				$message .= "Error: ";
				$message .= "You did not enter a " . $error . "</br>";
			}
		}
		
	} else {
		$username = "";
		$password = "";
	}
	
?>

<html>
	<head>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans+Mono' rel='stylesheet' type='text/css'>
		<title>NIGHT GALLERY | Login</title>
		<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/reset/reset-min.css">
		      <link href="styles/style.css" rel="stylesheet" type="text/css">
		      
	</head>
	<body id="login">
	
	<div class="wid">
	
	<section id="userbar"><p>Please Login</p>
		</section>
	
	
	<article>
	<p><?php if(isset($message)){ echo $message; } ?></p>
	<form action="login.php" method="post">
		<p><input type="text" placeholder="Username" name="username" value="" id="title" size="60"/></p>
		<p><input type="password" placeholder="Password" name="password" value="" id="title" size="60"/></p>
		<p><input type="submit" value=">" id="submit" name="submit"></p>
	</form>
	
	 <article>
	<?php build_footer(); ?>
	 </div>
	 
	</body>
</html>
