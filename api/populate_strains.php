<?php
	include ('connect.php');
	include ('../includes/functions.php');
	
	$query = "SELECT * FROM elements";	
	$result = mysql_query($query);
	if (!$result) {
		echo 'Could not run query: ' . mysql_error();
		exit;
	}
	
	while ($row = mysql_fetch_assoc($result)) {
		$element = $row['element'];
		$strain_id = $row['strain_id'];
		$category_id = $row['category_id'];
		echo "$element, $strain_id, $category_id<br>";
		if ($category_id == '3') {
			echo "$element, $strain_id, $category_id<br>";
			$subquery = "UPDATE strains SET threePrimeUTR = '$element' WHERE id = '$strain_id'";
		} else if ($category_id == '5') {
			echo "$element, $strain_id, $category_id<br>";
			$subquery = "UPDATE strains SET promotor = '$element' WHERE id = '$strain_id'";
		}
		mysql_query($subquery);
		$subresult = mysql_query($subquery);
		if (!$subresult) {
			echo 'Could not run query: ' . mysql_error();
			exit;
		}
	}	
?>