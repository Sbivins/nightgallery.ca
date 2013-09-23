<?php

	//database constants
	/*
define("DB_SERVER", "noparlay1.db.6604408.hostedresource.com");
	define("DB_USER", "noparlay1");
	define("DB_PASS", "Never1234get");
	define("DB_NAME", "noparlay1");
*/
	
	
	
	
	define("DB_SERVER", "nightgallery20.db.3494391.hostedresource.com");
	define("DB_USER", "nightgallery20");
	define("DB_PASS", "m1raDancy");
	define("DB_NAME", "nightgallery20");
	
	
	
	
	//1. set the connection
	$mysamconnec = mysql_connect(DB_SERVER, DB_USER, DB_PASS);
	if(!$mysamconnec){
		die("Database Connection Failed " . mysql_error());
	}
	
	//2. select the database
	$mysamselec = mysql_select_db(DB_NAME, $mysamconnec);
	if(!$mysamselec){
		die("Database Selection Failed " . mysql_error());
	}
	
?>