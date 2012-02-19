<?php
	include('connect.php');
	include('../includes/functions.php');
	
	$query = "SELECT strain FROM strains";
	
	$result = mysql_query($query);
	
	if (!$result) {
		echo 'Could not run query: ' . mysql_error();
		exit;
	}
	
	while ($row = mysql_fetch_assoc($result)) {
		$strain = $row['strain'];
		$renamed_strain = rename_strain($strain);
		$query = "UPDATE strains SET strain_sort = '$renamed_strain' WHERE strain = '$strain'";
		mysql_query($query);
	}	
?>