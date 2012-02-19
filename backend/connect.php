<?php
	// // // Connect to database // // //
	if ($_SERVER['SERVER_NAME'] == 'localhost') {
		$host = "localhost";
		$username = "root";
		$password = "root";
		$database = "PianoLab";
	} else {
		$host = "sql5c40a.carrierzone.com";
		$username = "lauraerick614155";
		$password = "shellwax36";
		$database = "nyu_lauraerickson1_site_aplus_net";
	}

	$conn = mysql_connect($host,$username,$password);
	mysql_select_db($database, $conn);

	if (!$conn) {
		echo "Could Not Connect to the Database";
	}
?>