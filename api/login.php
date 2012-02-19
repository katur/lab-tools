<?php
	// conect to the db //
	include('connect.php');
	
	// start a session //
	session_start();
	
	// GET the login credentials from the URL //
	$username = mysql_real_escape_string($_GET["username"]);
	$password = mysql_real_escape_string($_GET["password"]);
	
	// Define the query as selecting the entire (*) row from the users table when the user and password match; limit to one result //	
	$query = "SELECT *
		FROM users
		WHERE username = '$username' AND password = SHA1('$password')
		LIMIT 1
	";

	// Run the query //	
	$result = mysql_query($query);

	if (!$result) {
		echo 'Could not run query: ' . mysql_error();
		exit;
	}
	
	// Count the number of rows in the result //	
	$numrows = mysql_num_rows($result);
	
	// If the result has zero rows, define them as not logged in //	
	if ($numrows == 0) {
		// Assign session variable for 'not logged in' //	
		$_SESSION["login_error"] = true;

	// If the result has a row //
	} else {
		// Fetch the contents of the row from the result //
		$row = mysql_fetch_assoc($result);

		// Assign session variable for 'logged in' //	
		$_SESSION["logged_in"] = $username;

		// Determine whether or not the user/pw is defined as admin=1 //	
		$admin = $row['admin'];
		if ($admin == 1) {
			// Assign session variable for 'admin' //	
			$_SESSION["admin"] = true;
		}
	}
	
	// Redirect to index/home page //
	header('location: /');
?>