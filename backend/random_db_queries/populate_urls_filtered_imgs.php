<?php
	include('connect.php');
	include('../includes/functions.php');
		
	$query = "SELECT folder, tile
		FROM filtered_images
	";	
	$result = mysql_query($query);
	if (!$result) {
		echo 'Could not run query: ' . mysql_error();
		exit;
	}
	while ($row = mysql_fetch_assoc($result)) {
		$folder = $row['folder'];
		$tile = $row['tile'];
		
		echo "$folder&nbsp;$tile<br>";
		
		// insert new row into transgene table
		if ($tile < 10) {
		    $subquery = "UPDATE filtered_images
		        SET url_small = 'http://pleiades.bio.nyu.edu/GI_IMG/convertedImg/$folder/Tile00000$tile.jpg'
		        
		        WHERE folder = $folder AND tile = $tile
    		";
		} else if ($tile <= 96) {
		    $subquery = "UPDATE filtered_images
		        SET url_small = 'http://pleiades.bio.nyu.edu/GI_IMG/convertedImg/$folder/Tile0000$tile.jpg'
		        WHERE folder = $folder AND tile = $tile
    		";
		} else {
		    echo "error error error";
		}

		$subresult = mysql_query($subquery);
		if (!$subresult) {
			echo 'Could not run query: ' . mysql_error();
			exit;
		}
	}
?>