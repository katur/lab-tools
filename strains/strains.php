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
	<body id='strains'>		
		<div id="contentStrains">
			<?php
				// include the top bar and the search form at top of page
				include ("../includes/top_bar.php");
				include ("../includes/strain_search_form.php");
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
					// If there is a search term //
					if ($_GET["search_term"]) {
						$search_term = $_GET["search_term"];
						$query = "SELECT distinct strains.id AS strain_id, strains.strain, strains.genotype, 
								strains.transgene_id, strains.vector_template_id,
								strains.gene, strains.sequence, 
								strains.promotor, strains.threePrimeUTR,
								species.species, authors.author, mutagen.mutagen
							FROM strains
							LEFT JOIN authors
								ON authors.id = strains.author_id
							LEFT JOIN species
								ON species.id = strains.species_id
							LEFT JOIN mutagen
								ON mutagen.id = strains.mutagen_id
							LEFT JOIN vector_template
								ON vector_template.id = strains.vector_template_id
							WHERE strains.strain LIKE '%$search_term%'
								OR strains.genotype LIKE '%$search_term%'
								OR species.species LIKE '%$search_term%'
								OR strains.remarks LIKE '%$search_term%'
								OR strains.gene LIKE '%$search_term%'
								OR strains.sequence LIKE '%$search_term%'
								OR vector_template.name LIKE '%$search_term%'
								OR authors.author LIKE '%$search_term%'
								OR strains.date_created LIKE '%$search_term%'
								OR mutagen.mutagen LIKE '%$search_term%'
								OR strains.outcrossed LIKE '%$search_term%'
								OR strains.outcrossed LIKE '%" . preg_replace('/x/', '', $search_term) . "%'
							ORDER BY strains.strain_sort
						";
					// If no search term, default display all rows //
					} else {
						$query = "SELECT strains.id AS strain_id, strains.strain, strains.genotype,
							strains.transgene_id, strains.vector_template_id,
							strains.gene, strains.sequence,
							strains.promotor, strains.threePrimeUTR,
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
					}
                    
					// Run the query //
					$result = mysql_query($query);
					if (!$result) {
						echo 'Could not run query: ' . mysql_error();
						exit;
					}
					
					// Retrieve results //
					while ($row = mysql_fetch_assoc($result)) {
						$strain_id = $row['strain_id'];
						$strain = $row['strain'];
						$genotype = $row['genotype'];
						$transgene_id = $row['transgene_id'];
						$vector_template_id = $row['vector_template_id'];
						$gene = $row['gene'];
						$sequence = $row['sequence'];
						$promotor = $row['promotor'];
						$threePrimeUTR = $row['threePrimeUTR'];				
						$species = $row['species'];
						$author = $row['author'];
						$mutagen = $row['mutagen'];
						

						// Generate the genotype using the template and any relevant pieces
						$genotype = generate_genotype($genotype, $transgene_id, $vector_template_id,
							$gene, $sequence, $promotor, $threePrimeUTR);
						
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
		