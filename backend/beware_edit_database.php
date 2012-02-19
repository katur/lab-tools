<?php
	include('connect.php');
	include('../includes/functions.php');
	
	$currentTransgeneNumber = '132';
	
	$query = "SELECT vector_id, strain, id
		FROM strains 
		WHERE author_id = '6'
		ORDER BY strain_sort
	";	
	$result = mysql_query($query);
	if (!$result) {
		echo 'Could not run query: ' . mysql_error();
		exit;
	}
	while ($row = mysql_fetch_assoc($result)) {
		$vector_id = $row['vector_id'];
		$strain_id = $row['id'];
		$strain = $row['strain'];
		
		echo "$strain<br>";
		
		// insert new row into transgene table
		$subquery = "INSERT INTO transgene (name, vector_id) 
			VALUES ('nnIs$currentTransgeneNumber', '$vector_id')
		";
		$subresult = mysql_query($subquery);
		if (!$subresult) {
			echo 'Could not run query: ' . mysql_error();
			exit;
		}
		
		$subquery = "UPDATE strains
			SET transgene_id = (SELECT max(id) FROM transgene)
			WHERE id = '$strain_id'
		";
		$subresult = mysql_query($subquery);
		if (!$subresult) {
			echo 'Could not run query: ' . mysql_error();
			exit;
		}
		
		$currentTransgeneNumber++;
	}
?>