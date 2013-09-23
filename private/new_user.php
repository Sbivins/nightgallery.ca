<?php
	require_once("../includes/sessions.php");
	require_once("../includes/connection.php");
	require_once("../includes/functions.php");
	require_once("../includes/private_layout_functions.php");
	
	confirm_logged_in();
	confirm_top_level();

	$user_filter = 0;
	$user_filter = $_GET['user_filter'];
	$selected = " selected = \"selected\" ";
	
	//sees if this has just been submitted to itself
	if (isset($_POST['submit'])){
		
		$required_fields = array('username','password');
	
		foreach($required_fields as $required) {
			if (!isset($_POST[$required]) || empty($_POST[$required]) ){
				$errors[] = $required;
			}
		}
		
		if(empty($errors)) {
		//DO THE QUERY
		
			$username = mysql_prep($_POST['username']);
			$password = mysql_prep($_POST['password']);
			$hashed_password = sha1($password);
			$user_level = mysql_prep($_POST['user_level']);
			
			
				$query = "INSERT INTO ng_users (username, hashed_password, user_level) ";
				$query .= " VALUES ('{$username}', '{$hashed_password}', '{$user_level}')";
			$result = mysql_query($query, $mysamconnec);
			
			if (mysql_affected_rows()==1){
				$message = "Update was successful: Added user <em>" . htmlentities($username) . "</em> at level " . htmlentities($user_level);
			} else {
				$message = "Update failed";
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
		<title>NIGHT GALLERY | Add Users</title>
		<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/reset/reset-min.css">
		      <link href="styles/style.css" rel="stylesheet" type="text/css">
		      
		      
	</head>
	<body class="crud1 admin">
	
	<div class="wid">
		
		<div class="header">
			<div id="userbar" ><p class="user<?php echo $level;?>"><?php echo $_SESSION['username']; ?>
				
					<?php require_once("../includes/userbar.php");?>
				
				</div>
			
			<?php build_admin_nav(); ?>
		</div>
	
	
	
	<div class="content">
		
		<? // TOP BAR OF THE PAGE ?>
		<div id="pagehead">
			<h3>MANAGE USERS / ADMINISTRATORS</h3>
			
			<form action="new_user.php" method="get">
				SHOWING:
				<select name='user_filter' onchange='this.form.submit()'>
				
					<option value="0" <?php if($user_filter == 0) {echo $selected;} ?>>All Users...
					<option value="1" <?php if($user_filter == 1) {echo $selected;} ?>>Private: Basic
					<option value="2" <?php if($user_filter == 2) {echo $selected;} ?>>Private: Basic (w/ $)
					<option value="3" <?php if($user_filter == 3) {echo $selected;} ?>>Private: Elite
					<option value="4" <?php if($user_filter == 4) {echo $selected;} ?>>Private: Elite (w/ $)
					<option value="5" <?php if($user_filter == 5) {echo $selected;} ?>>Admins
					
				</select>
			</form>
		</div>
		
		
		<? //BODY OF THE PAGE ?>
		<div class="shelf"></div>
		
		<div id="addartwork">
		<div id ="artworkform">
		
			<p>ADD USER / ADMIN</p>
		
			<?php if(!empty($errormessage)){ echo "<p>" . $errormessage . "</p>"; } ?>
			
			<form action="new_user.php" method="post">
		<input type="text" placeholder="Username" name="username" value="<?php echo htmlentities($username); ?>" id="title" size="60"/>
		<input type="password" placeholder="Password" name="password" value="<?php echo htmlentities($password); ?>" id="title" size="60"/>
		
		
		
		<SELECT NAME="user_level">
			<OPTION VALUE="1" >1: Basic, No Prices
			<OPTION VALUE="2" >2: Basic w/ Prices
			<OPTION VALUE="3" >3: Extended, No Prices
			<OPTION VALUE="4" >4: Extended w/ Prices
			<OPTION VALUE="5" >5: Administrator

		</SELECT>


		<p><input type="submit" value="Create User" name="submit" id="submit"></p>
	</form>
		
		</div>
		</div>
		
		
		<section class="existingworks" id="inventory">
		
		<h4>EXISTING USERS</h4>
			
			<ul>
			<?php
			
			/* THIS IS WHERE THE ARTWORKS ARE SHOWN ON THIS PAGE. */
				$query = "SELECT * FROM ng_users ";
				if($user_filter > 0) { $query .= "WHERE user_level=" . $user_filter ; }
				
				
				$contentset = mysql_query($query, $mysamconnec);
				testquery($contentset);
				
				while($content = mysql_fetch_array($contentset)){
				
					echo "<li class=\"";
					echo "userclass" . $content['user_level'];
					echo "\">";
					
					echo "<div class=\"pic\" style=\"background-image:url(" . $content['artist_pic_url'] . ")\"> &nbsp; </div>";
					echo "<p>" . $content['username'] . "<p>";
					echo "<p><em>";
					if ($content['user_level'] == 1) {
						echo "Basic, No Prices";
					} else if ($content['user_level'] == 2) {
						echo "Basic w/ Prices";
					} else if ($content['user_level'] == 3) {
						echo "Extended";
					} else if ($content['user_level'] == 4) {
						echo "Extended w/ Prices";
					 }else if ($content['user_level'] == 5) {
						echo "Administrator";
					}
					echo "</em></p>";
					
					echo "<p class=\"options\"><a href=\"edit_user.php?id=" . $content['user_id'] . "\">Edit</a> | <a href=\"delete_user.php?id=" . $content['user_id'] . "\">Delete</a></p>";
					
					
					echo "</li>";
				}
				
			?>
			</ul>
			
		</section>
		
		
		</div>
		
		
		<?php build_footer(); ?>
	 </div>
	 
	</body>
</html>
