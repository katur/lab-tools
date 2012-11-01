<?php
	// // // Connect to database // // //
	if ($_SERVER['SERVER_NAME'] == 'localhost') {
		$host = "localhost";
		$username = "root";
		$password = "root";
		$database = "PianoLab";
	} else {
	  	$host = "mysql.lab.katherineerickson.com";
		$username = "labtoolssqluser";
		$password = "Scdb&MQ@NTycLUEIxg19";
		$database = "labtools";
	}

	$conn = mysql_connect($host,$username,$password);
	mysql_select_db($database, $conn);

	if (!$conn) {
		echo "Could Not Connect to the Database";
	}
?>