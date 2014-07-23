<?php
  	session_start();
	if (!$_SESSION["logged_in"] && !($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/index.php')) {
		header("location: /");
	}
	include("functions.php");
	include($_SERVER["DOCUMENT_ROOT"] . "/backend/connect.php");
?>
<!-- Copyright (c) 2010-2012 Katherine Erickson -->
