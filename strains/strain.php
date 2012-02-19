<!-- Copyright (c) 2011 Katherine Erickson -->

<?php include ('../includes/global.php'); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Piano Strain Database</title>

		<link rel="stylesheet" type="text/css" href="../stylesheets/style.css">

		<script type="text/javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" src="../js/javascript.js"></script>
	</head>
	<body id='strain'>
		<div id="content">
			<?php
				include ("../includes/top_bar.php");
				include ("../includes/strain_search_form.php");
			
				// GET the strain name from the URL
				$strain = $_GET["strain"];
				
				$query = "SELECT strains.id AS strain_id, strains.strain, species.species, strains.genotype,
						strains.vector_template_id, 
						strains.gene, strains.sequence, 
						strains.promotor, strains.threePrimeUTR,
						strains.date_created, authors.author, labs.lab, mutagen.mutagen, strains.outcrossed,
						strains.phenotype, strains.culture,
						strains.reference, strains.remarks, strains.wormbase, 
						strains.received_from, strains.date_received
					FROM strains
					LEFT JOIN authors
						ON authors.id = strains.author_id
					LEFT JOIN species
						ON species.id = strains.species_id
					LEFT JOIN mutagen
						ON mutagen.id = strains.mutagen_id
					LEFT JOIN labs
						ON labs.id = strains.lab_id
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
					$species = $row['species'];
					$genotype = $row['genotype'];
					
					$vector_template_id = $row['vector_template_id'];
					$vector = $row['vector'];
		
					$gene = $row['gene'];
					$sequence = $row['sequence'];
					$promotor = $row['promotor'];
					$threePrimeUTR = $row['threePrimeUTR'];
					
					$date_created = reconfigure_date($row['date_created']);
					$author = $row['author'];
					$lab = $row['lab'];
					$mutagen = $row['mutagen'];
					$outcrossed = $row['outcrossed'];
					
					$phenotype = $row['phenotype'];
					$culture = $row['culture'];
					
					$reference = $row['reference'];
					$remarks = $row['remarks'];
					$wormbase = $row['wormbase'];
					
					$received_from = $row['received_from'];
					$date_received = reconfigure_date($row['date_received']);

					// If no genotype but a genotype template...
				
					$genotype = generate_genotype($genotype, $vector_template_id,
						$gene, $sequence, $promotor, $threePrimeUTR);
					
					// Add an "x" to times outcrossed if it exists
					if ($outcrossed) {
						$outcrossed = $outcrossed . "x";
					}
					
					// Change the wormbase link to an active link
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
						// $query = "SELECT elements.element, categories.category
						// 	FROM elements
						// 	LEFT JOIN categories
						// 		ON categories.id = elements.category_id
						// 	WHERE elements.strain_id = '$strain_id'
						// ";
						// 	
						// $result = mysql_query($query);
						// 	
						// if (!$result) {
						// 	echo 'Could not run query: ' . mysql_error();
						// 	exit;
						// }
						// 	
						// while ($row=mysql_fetch_assoc($result)) {
						// 	$category = $row['category'];
						// 	$element = $row['element'];
						// 	echo "<div class='strainData'>$category:&nbsp;$element</div>";
						// }
						
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
					
					$query = "SELECT storage_vat.vat_name, storage_rack.rack_name, storage_box.box_name, 
							storage_tube.horizontal_position, storage_tube.vertical_position, 
							storage_tube_ref.freeze_date, authors.author
						FROM storage_tube
						LEFT JOIN storage_tube_ref
							ON storage_tube_ref.id = storage_tube.storage_tube_ref_id
						LEFT JOIN storage_box
							ON storage_box.id = storage_tube.box_id
						LEFT JOIN storage_rack
							ON storage_rack.id = storage_box.rack_id
						LEFT JOIN storage_vat
							ON storage_vat.id = storage_rack.vat_id
						LEFT JOIN authors
							ON authors.id = storage_tube_ref.frozen_by
						WHERE storage_tube_ref.strain_id = '$strain_id'
					";
		
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
						
						$letter_array=array('A','B','C','D','E','F','G','H','I');
					
						while ($row=mysql_fetch_assoc($result)) {
							// Assign variables //
							$vat_name = $row['vat_name'];
							$rack_name = $row['rack_name'];
							$box_name = $row['box_name'];
							$horizontal_position = $row['horizontal_position'];
							$vertical_position = $letter_array[$row['vertical_position']-1];						
							$freeze_date = $row['freeze_date'];
							$frozen_by = $row['author'];
			
							if ($freeze_date) {
								$freeze_date = reconfigure_date($freeze_date);
							}
						
			
							echo "
								<div class='strainData'>
									<b>$vat_name</b>&nbsp;&nbsp;Rack $rack_name - Box $box_name - $vertical_position$horizontal_position
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