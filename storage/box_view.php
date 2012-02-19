<!-- Copyright (c) 2010-2012 Katherine Erickson -->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<?php include("../includes/head.php"); ?>
	<body>
		<div id='content'>
			<?php
				include("../includes/top_bar.php");				
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