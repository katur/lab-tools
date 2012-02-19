<?php
	session_start();
	unset($_SESSION["login_error"]);
	unset($_SESSION["logged_in"]);
	unset($_SESSION["admin"]);
	header('location: /');
?>