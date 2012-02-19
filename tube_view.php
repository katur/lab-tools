<?php 
	include ('./includes/global.php');
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
				
				echo "<div id='storage'>";
				
					echo "<div id='tube_view'>";
					
						$box_id = $_GET["box_id"];

						// get vat name and rack name and box name for page header //
						$query = "SELECT storage_vat.vat_name, storage_rack.rack_name, storage_box.box_name, storage_box_type.tubes_horizontal, storage_box_type.tubes_vertical
							FROM storage_box
							LEFT JOIN storage_rack
							ON storage_rack.rack_id=storage_box.rack_id
							LEFT JOIN storage_vat
							ON storage_vat.vat_id=storage_rack.vat_id
							LEFT JOIN storage_box_type
							ON storage_box_type.box_type_id=storage_box.box_type_id
							WHERE storage_box.box_id=$box_id
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
							$box_name = $row['box_name'];
							$tubes_horizontal = $row['tubes_horizontal'];
							$tubes_vertical = $row['tubes_vertical'];
						}
						
						$letter_array=array('A','B','C','D','E','F','G','H','I');
						
						echo "<h1>Tubes/Wells in Box/Plate $box_name of Rack $rack_name of $vat_name</h1>";
				
						echo "<table>";

						$vertical_count = 1;
	
						while ($vertical_count <= $tubes_vertical) {
		
							echo "<tr>";
		
								$horizontal_count = 1;
		
								while ($horizontal_count <= $tubes_horizontal) {
									echo "<td>";
											
										$query = "SELECT storage_tube.tube_id, storage_tube.tube_contents, storage_tube.freeze_date, storage_tube.vertical_position, storage_tube.horizontal_position, authors.author, strains.strain
											FROM storage_tube
											LEFT JOIN authors
											ON authors.author_id=storage_tube.frozen_by
											LEFT JOIN strains
											ON strains.strain_id=storage_tube.strain_id
											WHERE storage_tube.box_id=$box_id
											AND storage_tube.horizontal_position=$horizontal_count
											AND storage_tube.vertical_position=$vertical_count";

										$result = mysql_query($query);
										if (!$result) {
											echo 'Could not run query: ' . mysql_error();
											exit;
										}
					
										if (mysql_num_rows($result)==0) {
											echo "NO TUBE RECORDED";
										} else {
											while ($row=mysql_fetch_assoc($result)) {
												// Assign variables //
												$tube_id = $row['tube_id'];
												$tube_contents = $row['tube_contents'];
												$freeze_date = $row['freeze_date'];
												$author = $row['author'];
												$strain = $row['strain'];
												$horizontal_position = $row['horizontal_position'];
												$vertical_position = $letter_array[$row['vertical_position']-1];	

												if ($horizontal_position !=NULL && $vertical_position !=NULL) {
													if ($strain != NULL) {
														echo "$vertical_position$horizontal_position: <a href='/strain.php?strain=$strain'>$strain</a><br>frozen $freeze_date by $author";
													} else {
														if ($tube_id != NULL) {
															echo "$vertical_position$horizontal_position: $tube_contents, $freeze_date, $author";
														} else {
															echo "NO TUBE RECORD";
														}
													}						
												} else {
													if ($strain != NULL) {
														echo "<a href='/strain.php?strain=$strain'>tube position not specified: $strain, frozen $freeze_date by $author</a>";
													} else {
														if ($tube_id != NULL) {
															echo "tube position not specified: $tube_contents, $freeze_date, $author";
														} else {
															echo "NO TUBE RECORD";
														}
													}	
												}				
											}
										}
									echo "</td>";
							
									$horizontal_count++;
								}
		
							echo "</tr>";
	
							$vertical_count++;
						}
	
						echo "</table>";
					echo "</div>";
				echo "</div>";				
			?>
		</div>
	</body>
</html>