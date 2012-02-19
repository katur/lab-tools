<!-- Copyright (c) 2011 Katherine Erickson -->

<?php 
	include ('../includes/global.php');
	function rack_contents ($rack_id, $slots_horizontal_count, $slots_vertical_count) {
		
		// get box information for all boxes in the rack //
		$query = "SELECT storage_box.box_name, storage_box.id AS box_id, 
				storage_box.old_location, authors.author
			FROM storage_box
			LEFT JOIN authors
				ON authors.id = storage_box.author_id
			WHERE storage_box.rack_id = $rack_id
				AND storage_box.horizontal_order = $slots_horizontal_count
				AND storage_box.vertical_order = $slots_vertical_count
		";
		$result = mysql_query($query);
		if (!$result) {
			echo 'Could not run query: ' . mysql_error();
			exit;
		}
		if (mysql_num_rows($result) == 0) {
			echo "<td>No Record</td>";
		} else {
			while ($row = mysql_fetch_assoc($result)) {
				// Assign variables //
				$box_name = $row['box_name'];
				$box_id = $row['box_id'];
				$old_location = $row['old_location'];
				$author = $row['author'];
				if ($old_location != NULL) {
					if ($box_name != NULL && $author != NULL) {
						echo "<td class='wholeLink'><a href='/storage/tube_view.php?box_id=$box_id'>$box_name
							<br>$author
							<br>previously: $old_location
						</a></td>";
					} else {
						if ($box_name != NULL) {
							echo "<td class='wholeLink'><a href='/storage/tube_view.php?box_id=$box_id'>$box_name
								<br>previously: $old_location
							</a></td>";
						} else {
							if ($author != NULL) {
								echo "<td class='wholeLink'><a href='/storage/tube_view.php?box_id=$box_id'>$author
									<br>previously: $old_location
								</a></td>";
							} else {
								echo "<td>Empty Space</td>";
							}
						}
					}
				} else {
					if ($box_name != NULL && $author != NULL) {
						echo "<td class='wholeLink'><a href='/storage/tube_view.php?box_id=$box_id'>$box_name
							<br>$author
						</a></td>";
					} else {
						if ($box_name != NULL) {
							echo "<td class='wholeLink'><a href='/storage/tube_view.php?box_id=$box_id'>$box_name</a></td>";
						} else {
							if ($author != NULL) {
								echo "<td class='wholeLink'><a href='/storage/tube_view.php?box_id=$box_id'>$author</a></td>";
							} else {
								echo "<td>Empty Space</td>";
							}
						}
					}
				}
			}
		}

	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Piano Lab Storage</title>
		<link rel="stylesheet" type="text/css" href="../stylesheets/style.css">
		<script type="text/javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" src="../js/javascript.js"></script>
	</head>
	<body>
		<div id='content'>
			<?php
				include ("../includes/top_bar.php");				
				echo "<div id='storage'>";				
					echo "<div id='box_view'>";				
						$rack_id = mysql_real_escape_string($_GET["rack_id"]);
								
						// get vat and rack names for page header //
						$query = "SELECT storage_vat.vat_name, storage_vat.id AS vat_id, 
								storage_rack.rack_name, storage_rack_type.slots_horizontal, 
								storage_rack_type.slots_vertical, storage_rack_type.slot_type
							FROM storage_rack
							LEFT JOIN storage_vat
								ON storage_vat.id = storage_rack.vat_id
							LEFT JOIN storage_rack_type
								ON storage_rack_type.id = storage_rack.rack_type_id
							WHERE storage_rack.id = $rack_id
						";
						$result = mysql_query($query);
						if (!$result) {
							echo 'Could not run query: ' . mysql_error();
							exit;
						}
								
						while ($row=mysql_fetch_assoc($result)) {
							// Assign variables //
							$vat_name = $row['vat_name'];
							$rack_name = $row['rack_name'];
							$slots_horizontal = $row['slots_horizontal'];
							$slots_vertical = $row['slots_vertical'];
							$slot_type = $row['slot_type'];
							$vat_id = $row['vat_id'];
						}
						
						echo "<h1>$vat_name, Rack $rack_name:&nbsp;";
						
						if ($slot_type == 'box') {
							echo "Box View";
						} else if ($slot_type == 'plate'){
							echo "Plate View";
						} else {
							echo "Contents";
						}
							
 						echo "</h1>";	
						echo "<table>";
							// If dewar, fill rack contents from bottom to top
							if ($vat_id == 1 || $vat_id == 2) {					
								$slots_vertical_count = $slots_vertical;
								while ($slots_vertical_count >= 1) {
									echo "<tr>";
										$slots_horizontal_count = 1;
										while ($slots_horizontal_count <= $slots_horizontal) {
											rack_contents($rack_id, $slots_horizontal_count, $slots_vertical_count);
											$slots_horizontal_count++;
										}
									echo "</tr>";	
									$slots_vertical_count--;
								}
							
							// If not a dewar, fill rack contents from top to bottom
							} else {
								$slots_vertical_count = 1;
								while ($slots_vertical_count <= $slots_vertical) {
									echo "<tr>";
										$slots_horizontal_count = 1;
										while ($slots_horizontal_count <= $slots_horizontal) {
											rack_contents($rack_id, $slots_horizontal_count, $slots_vertical_count);
											$slots_horizontal_count++;
										}
									echo "</tr>";
									$slots_vertical_count++;
								}
							}
						echo "</table>";
					echo "</div>";
				echo "</div>";
			?>
		</div>
	</body>
</html>