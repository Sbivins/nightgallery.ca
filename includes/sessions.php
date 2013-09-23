<?php
session_start();

function confirm_logged_in() {
	if(!isset($_SESSION['user_id'])) {
		header("Location: login.php");
		exit;
	}
}

//confirm top level should go after confirm_logged_in() on pages that should only be accessible by top level users
function confirm_top_level() {
	if($_SESSION['user_level'] < 5) {
		header("Location: ../private/");
		exit;
	}
}

function get_user_level() {
	return $_SESSION['user_level'];
}




function logged_in (){
	return isset($_SESSION['user_id']);
}

function admin_is_logged_in (){
	if ($_SESSION['user_level'] == 5) {
		return true;
	} else {
		return false;
	}
}


?>