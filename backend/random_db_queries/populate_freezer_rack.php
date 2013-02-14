<?php
	include('connect.php');
	include('../includes/functions.php');
	$query = "SELECT "
	

	function populate_freezer_rack($rack_id, $box_type) {
	    $query = "SELECT slots_horizontal, slots_vertical 
	        FROM storage_rack_type 
	        LEFT JOIN storage_rack
	        ON storage_rack_type.id = storage_rack.rack_type_id
	        WHERE storage_rack.id = $rack_id";
	    $result = mysql_query($query);
	    if (!$result) {
    		echo 'Could not run query: ' . mysql_error();
    		exit;
    	}
    	while ($row = mysql_fetch_assoc($result)) {
    		$slots_horizonal = $row['slots_horizontal'];
    		$slots_vertical = $row['slots_vertical'];
    	}
    	echo "$rack_id: $slots_horizontal x $slots_vertical";
    	
    	$query2 = "INSERT INTO storage_box(box_type_id, rack_id, horizontal_order, vertical_order) VALUES
    	";
    	$result2 = mysql_query($query2);
    	if (!$result2) {
    		echo 'Could not run query: ' . mysql_error();
    		exit;
    	}
	}
?>