<!-- Copyright (c) 2011 Katherine Erickson -->

<?php include ('../includes/global.php'); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Gunsiano Strain Database</title>

		<link rel="stylesheet" type="text/css" href="../stylesheets/style.css">

		<script type="text/javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" src="../js/javascript.js"></script>
	</head>
	<body id='strain'>
		<div id="content">
			<?php
				include ("../includes/top_bar.php");
				include ("../includes/strain_search_form.php");
			
				// get the strain name from the URL
				$strain = mysql_real_escape_string($_GET["strain"]);
				
				// query for strain fields of interest
				$query = "SELECT strains.id AS strain_id, 
						strains.strain, species.species, strains.genotype, strains.transgene_id,
						strains.date_created, authors.author, labs.lab, mutagen.mutagen, strains.outcrossed,
						strains.culture, strains.remarks, strains.wormbase, 
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
					WHERE strains.strain = '$strain'
				";
				
				// run the query
				$result = mysql_query($query);
				if (!$result) {
					echo 'Could not run query: ' . mysql_error();
					exit;
				}
				
				// retrieve results
				while ($row = mysql_fetch_assoc($result)) {
					$strain_id = $row['strain_id'];
					$strain = $row['strain'];
					$species = $row['species'];
					$genotype = $row['genotype'];
					$transgene_id = $row['transgene_id'];
					
					$date_created = reconfigure_date($row['date_created']);
					$author = $row['author'];
					$lab = $row['lab'];
					$mutagen = $row['mutagen'];
					$outcrossed = $row['outcrossed'];
					
					$culture = $row['culture'];
					$remarks = $row['remarks'];
					$wormbase = $row['wormbase'];
					$received_from = $row['received_from'];
					$date_received = reconfigure_date($row['date_received']);

					// If genotype template code provided
					if (strlen($genotype) <= 2 && strlen($genotype) >= 1) {
						// generate genotype using the template and any relevant pieces
						$genotype = generate_genotype($genotype, $transgene_id);
					}
				}
			?>
			
			<!-- Display the results of the query -->
			<div id="strainContainer">
				<div id="strainName">
					<?php echo $strain;?>
				</div>
				
				
				<!-- STRAIN OVERVIEW SECTION-->
				
				<div class="strainSection">
					<?php
						if ($strain != NULL) {
							echo "<div class='strainData'><b>Strain:</b>&nbsp;$strain</div>";
						}
						
						if ($species != NULL) {
							echo "<div class='strainData'><b>Species:</b>&nbsp;<i>$species</i></div>";
						}
						
						if ($wormbase == 1) {
							// create link to wormbase using generate_wormbase()
							echo "<div class='strainData'>
								<a href='" . generate_wormbase($strain) . "' target='_blank'>
									See strain on WormBase
								</a>
							</div>";
						}
						
						if ($genotype != NULL) {
							echo "<div class='strainData'><b>Genotype:</b>&nbsp;$genotype</div>";
						}
						
						if ($culture != NULL) {
							echo "<div class='strainData'><b>Culture:</b>&nbsp;$culture</div>";
						}
					?>
				</div>
				
				
				<!-- ORIGIN SECTION-->
				
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
							// Add an "x" to the number of times outcrossed
							echo "<div class='strainData'><b>Outcrossed:</b>&nbsp;" . $outcrossed . "x</div>";
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
					
					
					// STORAGE SECTION
			
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
					
					if ($received_from != NULL || $date_received != NULL || $numrows > 0) {
						echo " 
							<div class='line'></div>
							<div class='strainSection'>
								<h2>Stock</h2>
						";
						
						if ($received_from != NULL || $date_received != NULL) {
							echo "<div class='strainSection'>";
							
							if ($received_from != NULL) {
								echo "<div class='strainData'><b>Received From:</b>&nbsp;$received_from</div>";
							}

							if ($date_received != NULL) {
								echo "<div class='strainData'><b>Date Received:</b>&nbsp;$date_received</div>";
							}
							
							echo "</div>";
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
								<div class='freezeData'>
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