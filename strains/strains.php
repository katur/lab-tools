<!-- Copyright (c) 2010-2012 Katherine Erickson -->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<?php include("../includes/head.php"); ?>
	<body id='strains'>		
		<div id="contentStrains">
			<?php
				// include the top bar and the search form at top of page
				include("../includes/top_bar.php");
				include("../includes/strain_search_form.php");
			?>
			<table id="strains">
				<tr class="topRow">
						<td>Strain</td>
						<td>Species</td>
						<td>Genotype</td>
						<td>Made By</td>
						<td>Mutagen / Method</td>
				</tr>
				<?php
					// Get search term if there is one
					if (mysql_real_escape_string($_GET["search_term"])) {
						$search_term = mysql_real_escape_string($_GET["search_term"]);
					}
					
					// Query all rows in strains
					$query = "SELECT strains.strain, strains.genotype, strains.transgene_id,
							strains.remarks, strains.culture, 
							species.species, authors.author, mutagen.mutagen
						FROM strains
						LEFT JOIN authors
							ON authors.id = strains.author_id
						LEFT JOIN species
							ON species.id = strains.species_id
						LEFT JOIN mutagen
							ON mutagen.id = strains.mutagen_id
						ORDER BY strains.strain_sort
					";
                    
					// Run the query
					$result = mysql_query($query);
					if (!$result) {
						echo 'Could not run query: ' . mysql_error();
						exit;
					}
					
					// Set counter to count the total results
					$base_counter = 0;
					$search_counter = 0;
					
					// Retrieve results
					while ($row = mysql_fetch_assoc($result)) {
						$base_counter++;
						
						$strain = $row['strain'];
						$species = $row['species'];
						$genotype = $row['genotype'];
						$transgene_id = $row['transgene_id'];
						$remarks = $row['remarks'];
						$culture = $row['culture'];
						$author = $row['author'];
						$mutagen = $row['mutagen'];

						// If genotype template code provided
						if (strlen($genotype) <= 2 && strlen($genotype) >= 1) {
							// generate genotype using the template and any relevant pieces
							$genotype = generate_genotype($genotype, $transgene_id);
						}
						
						// If there is a search term, only display if matches search term
						if ($search_term && $strain) {
							if (preg_match('/'.$search_term.'/', $strain) ||
								preg_match('/'.$search_term.'/', $species) ||
								preg_match('/'.$search_term.'/', $genotype) ||
								preg_match('/'.$search_term.'/', $remarks) ||
								preg_match('/'.$search_term.'/', $culture) ||
								preg_match('/'.$search_term.'/', $author) ||
								preg_match('/'.$search_term.'/', $mutagen)
							){
								$search_counter++;
								echo "
									<tr>
										<td><a href='/strains/strain.php?strain=$strain'>$strain</a></td>
										<td><i>$species</i></td>
										<td>$genotype</td>
										<td>$author</td>
										<td>$mutagen</td>
									</tr>
								";
							}
								
						// If there isn't a search term
						} else {
							// if strain isn't null, print all fields
							if ($strain != NULL) {
								echo "
									<tr>
										<td><a href='/strains/strain.php?strain=$strain'>$strain</a></td>
										<td><i>$species</i></td>
										<td>$genotype</td>
										<td>$author</td>
										<td>$mutagen</td>
									</tr>
								";
							}
						}
					}
					if ($search_counter) {
						echo "$search_counter out of $base_counter strains match search term";
					} else {
						echo "$base_counter strains";
					}
					
				?>								
			</table>
		</div>
	</body>
</html>