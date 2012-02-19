<?php 
	include ('./includes/global.php');
	
	function rack_contents ($rack_id, $slots_horizontal_count, $slots_vertical_count) {
		echo "<td>";
		
			// get box information for all boxes in the rack //

			$query = "SELECT storage_box.box_name, storage_box.box_id
				FROM storage_box
				WHERE storage_box.rack_id=$rack_id
				AND storage_box.horizontal_order=$slots_horizontal_count
				AND storage_box.vertical_order=$slots_vertical_count
				";

			$result = mysql_query($query);
			if (!$result) {
				echo 'Could not run query: ' . mysql_error();
				exit;
			}

			if (mysql_num_rows($result) == 0) {
				echo "NO BOX/PLATE RECORD";
			} else {
				while ($row=mysql_fetch_assoc($result)) {
					// Assign variables //
					$box_name = $row['box_name'];
					$box_id = $row['box_id'];
				
					if ($box_name != NULL) {
						echo "<h1><a href='/tube_view.php?box_id=$box_id'>$box_name</a></h1>";
					} else {
						echo "EMPTY BOX SPACE";
					}
				}
			}

		echo "</td>";
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Piano Lab Storage</title>

		<link rel="stylesheet" type="text/css" href="./stylesheets/style.css">

		<script type="text/javascript" src="./js/jquery.js"></script>
		<script type="text/javascript" src="./js/javascript.js"></script>
	</head>
	<body id='storage'>
		<div id='content'>
			<?php
				include ("./includes/top_bar.php");
				
				echo "<div id ='storage'>";
				
					echo "<div id='box_view'>";
				
						$rack_id = $_GET["rack_id"];
				
				
						// get vat name and rack name for page header //
						$query = "SELECT storage_vat.vat_name, storage_vat.vat_id, storage_rack.rack_name, storage_rack_type.slots_horizontal, storage_rack_type.slots_vertical, storage_rack_type.slot_type
							FROM storage_rack
							LEFT JOIN storage_vat
							ON storage_vat.vat_id=storage_rack.vat_id
							LEFT JOIN storage_rack_type
							ON storage_rack_type.rack_type_id=storage_rack.rack_type_id
							WHERE storage_rack.rack_id=$rack_id
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
				
						echo "<h1>Boxes/Plates in Rack $rack_name of $vat_name</h1>";
	
						echo "<table>";
				
						if ($vat_id==1 || $vat_id==2) {
					
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