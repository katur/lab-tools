<!-- Copyright (c) 2010-2012 Katherine Erickson -->

<?php
	session_start();
	if (!$_SESSION["logged_in"]) {
		header("location: /");
	}
	include("functions.php");
	include($_SERVER["DOCUMENT_ROOT"] . "/backend/connect.php");
?>