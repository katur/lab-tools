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
					// Define common beginning of query for strain fields to display
					$query = "SELECT strains.strain, strains.genotype, strains.transgene_id,
							species.species, authors.author, mutagen.mutagen
						FROM strains
						LEFT JOIN authors
							ON authors.id = strains.author_id
						LEFT JOIN species
							ON species.id = strains.species_id
						LEFT JOIN mutagen
							ON mutagen.id = strains.mutagen_id
					";
					
					// If there is a search term
					if (mysql_real_escape_string($_GET["search_term"])) {
						$search_term = mysql_real_escape_string($_GET["search_term"]);
						$query = $query . "
							WHERE strains.strain LIKE '%$search_term%'
								OR strains.genotype LIKE '%$search_term%'
								OR species.species LIKE '%$search_term%'
								OR strains.remarks LIKE '%$search_term%'
								OR strains.culture LIKE '%$search_term%'
								OR authors.author LIKE '%$search_term%'
								OR mutagen.mutagen LIKE '%$search_term%'
								OR strains.outcrossed LIKE '%$search_term%'
								OR strains.outcrossed LIKE '%" . preg_replace('/x/', '', $search_term) . "%'
							ORDER BY strains.strain_sort
						";
					
					// If no search term, default display all rows
					} else {
						$query = $query . "
							ORDER BY strains.strain_sort
						";
					}
                    
					// Run the query
					$result = mysql_query($query);
					if (!$result) {
						echo 'Could not run query: ' . mysql_error();
						exit;
					}
					
					// Retrieve results
					while ($row = mysql_fetch_assoc($result)) {
						$strain = $row['strain'];
						$species = $row['species'];
						$genotype = $row['genotype'];
						$transgene_id = $row['transgene_id'];			
						$author = $row['author'];
						$mutagen = $row['mutagen'];

						// If genotype template code provided
						if (strlen($genotype) <= 2 && strlen($genotype) >= 1) {
							// generate genotype using the template and any relevant pieces
							$genotype = generate_genotype($genotype, $transgene_id);
						}
			
						// If strain isn't null, print the fields
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
				?>								
			</table>
		</div>
	</body>
</html>