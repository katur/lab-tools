<?php include ('./includes/global.php'); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Gunsiano Strain Database</title>

		<link rel="stylesheet" type="text/css" href="./stylesheets/style.css">

		<script type="text/javascript" src="/js/jquery.js"></script>
		<script type="text/javascript" src="/js/javascript.js"></script>
	</head>
	<body id='strain'>		
		<div id="content">
			<?php 
				include ("./includes/top_bar.php");
				include ("./includes/search_form.php");
			
				// GET the strain name from the URL //
				$strain = $_GET["strain"];
				
				$query = "SELECT strains.strain_id, strains.strain, strains.genotype, species.species, authors.author, labs.lab, strains.date_created, strains.received_from, strains.date_received, mutagen.mutagen, strains.outcrossed, strains.phenotype, strains.culture, strains.reference, strains.remarks, strains.wormbase
					FROM strains
					LEFT JOIN authors
					ON authors.author_id = strains.author_id
					LEFT JOIN species
					ON species.species_id = strains.species_id
					LEFT JOIN mutagen
					ON mutagen.mutagen_id = strains.mutagen_id
					LEFT JOIN labs
					ON labs.lab_id = strains.lab_id
					WHERE strains.strain = '$strain'";

				$result = mysql_query($query);
				if (!$result) {
					echo 'Could not run query: ' . mysql_error();
					exit;
				}
				
				while ($row=mysql_fetch_assoc($result)) {
					// Assign variables //
					$strain_id = $row['strain_id'];
					$strain = $row['strain'];
					$genotype = $row['genotype'];
					$species = $row['species'];
					$author = $row['author'];
					$lab = $row['lab'];
					$date_created = reconfigure_date($row['date_created']);
					$received_from = $row['received_from'];
					$date_received = reconfigure_date($row['date_received']);
					$mutagen = $row['mutagen'];
					$outcrossed = $row['outcrossed'];
					$phenotype = $row['phenotype'];
					$culture = $row['culture'];
					$reference = $row['reference'];
					$remarks = $row['remarks'];
					$wormbase = $row['wormbase'];
					
					// Shorten what appears for "remarks" and add "...".  If no remarks, do not print "..." //
					if ($remarks) {
						$remarks_trunc = substr($remarks, 0 , 18) . "...";
					} else {
						$remarks_trunc = "";
					}
					
					// Add an "x" to times outcrossed if it exists //
					if ($outcrossed) {
						$outcrossed = $outcrossed . "x";
					}
					
					// Change the wormbase link to an active link //
					if ($wormbase) {
						$wormbase = "<a href='$wormbase' target='_blank'>See strain on WormBase</a>";
					}
				}
			?>
			
			<!-- Display the results of the query -->
			<div id="strainContainer">
				<div id="strainName">
					<?php echo $strain; ?>
				</div>
				<div class="strainSection">
					<?php
						if ($strain != NULL) {
							echo "<div class='strainData'><b>Strain:</b>&nbsp;$strain</div>";
						}
						if ($species != NULL) {
							echo "<div class='strainData'><b>Species:</b>&nbsp;<i>$species</i></div>";
						}
						if ($wormbase != NULL) {
							echo "<div class='strainData'>$wormbase</div>";
						}
						if ($genotype != NULL) {
							echo "<div class='strainData'><b>Genotype:</b>&nbsp;$genotype</div>";
						}
						
					 	//Query the Elements + Categories Tables and display results
					
						$query = "SELECT elements.element, categories.category
						FROM elements
						LEFT JOIN categories
						ON categories.category_id = elements.category_id
						WHERE elements.strain_id = '$strain_id'";
		
						$result = mysql_query($query);
		
						if (!$result) {
							echo 'Could not run query: ' . mysql_error();
							exit;
						}
		
						while ($row=mysql_fetch_assoc($result)) {
							$category = $row['category'];
							$element = $row['element'];
							echo "<div class='strainData'>$category:&nbsp;$element</div>";
						}
						
						if ($phenotype != NULL) {
							echo "<div class='strainData'><b>Phenotype:</b>&nbsp;$phenotype</div>";
						}
						if ($culture != NULL) {
							echo "<div class='strainData'><b>Culture:</b>&nbsp;$culture</div>";
						}
					?>
				</div>
				<?php
					if ($author != NULL || $lab != NULL || $date_created != NULL || $mutagen != NULL || $outcrossed != NULL) {
						echo "
							<div class='line'></div>
							<div class='strainSection'>
								<h2>Origin</h2>
							";
						if ($author != NULL) {
							echo "<div class='strainData'><b>Made By:</b>&nbsp;$author</div>";
						}
						if ($lab != NULL) {
							echo "<div class='strainData'><b>Lab:</b>&nbsp;$lab</div>";
						}
						if ($date_created != NULL) {
							echo "<div class='strainData'><b>Date Created:</b>&nbsp;$date_created</div>";
						}
						if ($mutagen != NULL) {
							echo "<div class='strainData'><b>Mutagen or Method Used:</b>&nbsp;$mutagen</div>";
						}
						if ($outcrossed != NULL) {
							echo "<div class='strainData'><b>Outcrossed:</b>&nbsp;$outcrossed</div>";
						}
						echo "</div>";
					}
					
					if ($remarks != NULL) {
						echo "
							<div class='line'></div>
							<div class='strainSection'>
								<h2>Remarks</h2>
								<div class='strainData'>$remarks</div>
							</div>
						";
					}
					
					if ($reference != NULL) {
						echo "
							<div class='line'></div>
							<div class='strainSection'>
								<h2>Reference</h2>
								<div class='strainData'>$reference</div>
							</div>
						";
					}
					
					// Query the storage_tube table
					
					$query = "SELECT storage_vat.vat_name, storage_rack.rack_name, storage_box.box_name, storage_tube.tube_position, storage_tube.freeze_date, authors.author
					FROM storage_tube
					LEFT JOIN storage_box
					ON storage_box.box_id = storage_tube.box_id
					LEFT JOIN storage_rack
					ON storage_rack.rack_id = storage_box.rack_id
					LEFT JOIN storage_vat
					ON storage_vat.vat_id = storage_rack.vat_id
					LEFT JOIN authors
					ON authors.author_id = storage_tube.frozen_by
					WHERE storage_tube.strain_id = '$strain_id'";
		
					$result = mysql_query($query);
	
					if (!$result) {
						echo 'Could not run query: ' . mysql_error();
						exit;
					}
					
					$numrows = mysql_num_rows($result);
					
					if ($received_from != NULL || $date_received != NULL || $numrows>0) {
						echo "
							<div class='line'></div>
							<div class='strainSection'>
								<h2>Stock</h2>
						";
						
						if ($received_from != NULL) {
							echo "<div class='strainData'><b>Received From:</b>&nbsp;$received_from</div>";
						}
					
						if ($date_received != NULL) {
							echo "<div class='strainData'><b>Date Received:</b>&nbsp;$date_received</div>";
						}
						
					
						while ($row=mysql_fetch_assoc($result)) {
							// Assign variables //
							$vat_name = $row['vat_name'];
							$rack_name = $row['rack_name'];
							$box_name = $row['box_name'];
							$tube_position = $row['tube_position'];
							$freeze_date = $row['freeze_date'];
							$frozen_by = $row['author'];
			
							if ($freeze_date) {
								$freeze_date = reconfigure_date($freeze_date);
							}
			
							echo "
								<div class='strainData'>
									<b>$vat_name</b>&nbsp;&nbsp;$rack_name-$box_name-$tube_position
									<br>Frozen by $frozen_by on $freeze_date	
								</div>
							";	
						}
						echo "</div>";	
					}
				?>	
			</div>
		</div>
	</body>
</html>