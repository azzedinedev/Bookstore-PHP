<?php
	$user = "root";
	$pass="";
	$host="localhost";
	$db = "bookstore";

	$database = new db();
	$database->connectDB($user,$pass,$host,$db);
?>