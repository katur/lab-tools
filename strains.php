<?php include ('./includes/global.php'); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Gunsiano Strain Database</title>

		<link rel="stylesheet" type="text/css" href="./stylesheets/style.css">

		<script type="text/javascript" src="/js/jquery.js"></script>
		<script type="text/javascript" src="/js/javascript.js"></script>
	</head>
	<body id='strains'>		
		<div id="contentStrains">
			<?php 
				include ("./includes/top_bar.php");
				include ("./includes/search_form.php");
			?>
			<table id="strains">
				<tr class="topRow">
						<td>Strain</td>
						<td>Species</td>
						<td>Vector</td>
						<td>Genotype</td>
						<td>Made By</td>
						<td>Mutagen / Method</td>
						<td>Frozen?</td>
				</tr>
				<?php
					if ($_GET["search_term"]) {
						$search_term = $_GET["search_term"];
						$query = "SELECT distinct strains.strain, strains.genotype, species.species, authors.author, strains.received_from, mutagen.mutagen, strains.outcrossed, strains.reference, strains.vector_id, strains.gene, strains.sequence, vector.vector, strains.wormbase
							FROM strains
							LEFT JOIN authors
							ON authors.author_id = strains.author_id
							LEFT JOIN species
							ON species.species_id = strains.species_id
							LEFT JOIN mutagen
							ON mutagen.mutagen_id = strains.mutagen_id
							LEFT JOIN vector
							ON vector.vector_id = strains.vector_id
							WHERE strains.strain LIKE '%$search_term%'
								OR strains.genotype LIKE '%$search_term%'
								OR species.species LIKE '%$search_term%'
								OR authors.author LIKE '%$search_term%'
								OR strains.date_created LIKE '%$search_term%'
								OR strains.received_from LIKE '%$search_term%'
								OR strains.date_received LIKE '%$search_term%'
								OR mutagen.mutagen LIKE '%$search_term%'
								OR strains.outcrossed LIKE '%$search_term%'
								OR strains.outcrossed LIKE '%" . preg_replace('/x/', '', $search_term) . "%'
								OR strains.phenotype LIKE '%$search_term%'
								OR strains.culture LIKE '%$search_term%'
								OR strains.reference LIKE '%$search_term%'
								OR strains.remarks LIKE '%$search_term%'
								OR strains.wormbase LIKE '%$search_term%'
								OR strains.gene LIKE '%$search_term%'
								OR strains.sequence LIKE '%$search_term%'
								OR vector.vector LIKE '%$search_term%'
								
							ORDER BY strains.strain_sort";
					} else {
						// Default display all rows //	
						$query = "SELECT strains.strain, strains.genotype, species.species, authors.author, strains.received_from, mutagen.mutagen, strains.outcrossed, strains.reference, strains.vector_id, strains.gene, strains.sequence, vector.vector, strains.wormbase
							FROM strains
							LEFT JOIN authors
							ON authors.author_id = strains.author_id
							LEFT JOIN species
							ON species.species_id = strains.species_id
							LEFT JOIN mutagen
							ON mutagen.mutagen_id = strains.mutagen_id
							LEFT JOIN vector
							ON vector.vector_id = strains.vector_id
							ORDER BY strains.strain_sort";
					}

					$result = mysql_query($query);
					if (!$result) {
						echo 'Could not run query: ' . mysql_error();
						exit;
					}
					
					while ($row=mysql_fetch_assoc($result)) {
						$strain = $row['strain'];
						$gene = $row['gene'];
						$sequence = $row['sequence'];
						$vector_id = $row['vector_id'];
						$vector = $row['vector'];
						if ($row['genotype'] == NULL) {
							$genotype = generate_genotype($vector_id, $gene, $sequence);
						} else {
							$genotype = $row['genotype'];
						}
						$species = $row['species'];
						$author = $row['author'];
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
						//if ($remarks) {
						//	$remarks_trunc = substr($remarks, 0 , 18) . "...";
						//} else {
						//	$remarks_trunc = "";
						//}
						
						if ($outcrossed) {
							$outcrossed = $outcrossed . "x";
						}
						
						if ($strain != NULL) {
							if (preg_match('/^(PF|AG)/', $strain)){
								echo 
									"<tr>
										<td><a href='/strain.php?strain=$strain'>$strain</a></td>
										<td><i>$species</i></td>
										<td>$vector</td>
										<td>$genotype</td>
										<td>$author</td>
										<td>$mutagen</td>
										<td>$frozen</td>
									</tr>";
							} else {
								if ($wormbase != NULL){
									echo
										"<tr>
											<td><a href='/strain.php?strain=$strain'>$strain</a></td>
											<td><i>$species</i></td>
											<td>$vector</td>
											<td>See strain on <a href='$wormbase' target='_blank'>WormBase</a></td>
											<td>$author</td>
											<td>$mutagen</td>
											<td>$frozen</td>
										</tr>";
								} else {
									echo
										"<tr>
											<td><a href='/strain.php?strain=$strain'>$strain</a></td>
											<td><i>$species</i></td>
											<td>$vector</td>
											<td>$genotype</td>
											<td>$author</td>
											<td>$mutagen</td>
											<td>$frozen</td>
										</tr>";
								}

							}
						}	
					}
				?>								
			</table>
		</div>
	</body>
</html>
		