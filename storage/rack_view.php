<!-- Copyright (c) 2010-2012 Katherine Erickson -->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<?php include("../includes/head.php"); ?>
	<body>
		<div id='content'>	
			<?php
				include ("../includes/top_bar.php");				
				echo "<div id='storage'>";				
					echo "<div id='rack_view'>";				
						$vat_id = mysql_real_escape_string($_GET["vat_id"]);			
						$query = "SELECT storage_vat.vat_name, storage_vat.shelves, storage_vat.shelf_rack_total
							FROM storage_vat
							WHERE storage_vat.id = $vat_id
						";			
						$result = mysql_query($query);
						if (!$result) {
							echo 'Could not run query: ' . mysql_error();
							exit;
						}			
						if (mysql_num_rows($result) == 0) {
							echo '<center>There are no racks recorded in this storage vat.</center>';
						}
						while ($row = mysql_fetch_assoc($result)) {
							// Assign variables //
							$vat_name = $row['vat_name'];
							$shelves = $row['shelves'];
							$shelf_rack_total = $row['shelf_rack_total'];
						}			
						echo "<h1>$vat_name: Rack View</h1>";			
						echo "<table>";
							$shelf_count = 1;
							while ($shelf_count <= $shelves) {
								echo "<tr>";
									$rack_count = 1;
									while ($rack_count <= $shelf_rack_total) {
										$query = "SELECT storage_rack.id AS rack_id, storage_rack.rack_name, storage_rack.rack_contents
											FROM storage_rack 
											WHERE storage_rack.order_on_shelf = $rack_count
												AND storage_rack.vat_shelf = $shelf_count
												AND storage_rack.vat_id = $vat_id
										";
										$result = mysql_query($query);
										if (!$result) {
											echo 'Could not run query: ' . mysql_error();
											exit;
										}
										if (mysql_num_rows($result) == 0) {
											echo "<td>NO RACK RECORDED</td>";
										} else {
											while ($row = mysql_fetch_assoc($result)) {
												// Assign variables //
												$rack_name = $row['rack_name'];
												$rack_id = $row['rack_id'];
												$rack_contents = $row['rack_contents'];
												if ($rack_name != NULL && $rack_contents != NULL) {
													echo "<td class='wholeLink'>
														<a href='/storage/box_view.php?rack_id=$rack_id'><b>$rack_name</b>
														<br>$rack_contents</a>
													</td>";
												} else if ($rack_name != NULL && $rack_contents == NULL){
													echo "<td class='wholeLink'>
														<a href='/storage/box_view.php?rack_id=$rack_id'><b>$rack_name</b></a>
													</td>";
												} else {
													echo "<td>EMPTY RACK SPACE</td>";
												}						
											}
										}
										$rack_count++;
									}
								echo "</tr>";
								$shelf_count++;
							}			
						echo "</table>";					
					echo "</div>";					
				echo "</div>";
			?>
		</div>
	</body>
</html>