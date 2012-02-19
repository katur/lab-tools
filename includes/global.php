<?php
	include ("api/connect.php");
	include ("functions.php");
	session_start();
	if (!$_SESSION["logged_in"]) {
		header("location: /");
	}
?>