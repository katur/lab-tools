<!-- Copyright (c) 2010-2012 Katherine Erickson -->

<?php
	// reconfigures yearmonthdate to month/date/year
	function reconfigure_date($date){
		if ($date) {
			$times = explode('-', $date);
			return $times[1] . "/" . $times[2] . "/" . $times[0];
		} else {
			return "";
		}
	}
	
	// renames strain in sortable order (by adding zeros after lab designation)
	function rename_strain($strain){
		$letters = substr($strain, 0, 2);
		$numbers = substr($strain, 2);
		$number_of_numbers = strlen($numbers);
		if ($number_of_numbers < 5) {
			$tally = 0;
			while ($tally < (5 - $number_of_numbers)) {
				$numbers = '0' . $numbers;
				$tally = $tally + 1;
			}
		}
		return $letters . $numbers;
	}
	
	// generates wormbase link from strain name
	function generate_wormbase($strain) {
		return preg_replace('/strain_fill/', $strain, 'http://wormbase.org/db/gene/strain?query=strain_fill;class=Strain');
	}
	
	// generates genotype from a template using pieces
	function generate_genotype($genotype, $transgene_id) {
		// retrieve and define the genotype template
		$query = "SELECT genotype FROM genotype WHERE id = $genotype";
		$result = mysql_query($query);
		if (!$result) {
			echo 'Could not run query: ' . mysql_error();
			exit;
		}
		while ($row = mysql_fetch_assoc($result)) {
			$genotype = $row['genotype'];
		}
		
		// if there is a transgene id provided
		if ($transgene_id != NULL) {
			// retrieve and define the transgene template
			$query = "SELECT transgene.name AS transgene_name, 
					vector.gene, vector.sequence,
					vector.promotor, vector.threePrimeUTR,
					vector_template.genotype AS vector_template_genotype
				FROM transgene
				LEFT JOIN vector
					ON transgene.vector_id = vector.id
				LEFT JOIN vector_template
					ON vector.vector_template_id = vector_template.id
				WHERE transgene.id = $transgene_id
			";
			$result = mysql_query($query);
			if (!$result) {
				echo 'Could not run query: ' . mysql_error();
				exit;
			}
			while ($row = mysql_fetch_assoc($result)) {
				$transgene_name = $row['transgene_name'];
				$vector_genotype = $row['vector_template_genotype'];
				$gene = $row['gene'];
				$sequence = $row['sequence'];
				$promotor = $row['promotor'];
				$threePrimeUTR = $row['threePrimeUTR'];
			}
			
			// if the gene is null, use the sequence
			if ($gene == NULL) {
				$gene = $sequence;
			}
			
			// replace holes in vector (gene, promotor, and/or threePrimeUTR)
			$vector_genotype = str_replace('gene', $gene, $vector_genotype);
			$vector_genotype = str_replace('promotor', $promotor, $vector_genotype);
			$vector_genotype = str_replace('threePrimeUTR', $threePrimeUTR, $vector_genotype);
			
			// replace holes in genotype
			$genotype = str_replace('vector_genotype', $vector_genotype, $genotype);
			$genotype = str_replace('transgene_name', $transgene_name, $genotype);			
		}
		
		// return the genotype
		return $genotype;
	}
	
	// displays contents of a freezer rack
	function rack_contents($rack_id, $slots_horizontal_count, $slots_vertical_count) {
		// get box information for all boxes in the rack //
		$query = "SELECT storage_box.box_name, storage_box.id AS box_id, 
				storage_box.old_location, authors.author
			FROM storage_box
			LEFT JOIN authors
				ON authors.id = storage_box.author_id
			WHERE storage_box.rack_id = $rack_id
				AND storage_box.horizontal_order = $slots_horizontal_count
				AND storage_box.vertical_order = $slots_vertical_count
		";
		$result = mysql_query($query);
		if (!$result) {
			echo 'Could not run query: ' . mysql_error();
			exit;
		}
		
		if (mysql_num_rows($result) == 0) {
			echo "<td>No Record</td>";
		} else {
			while ($row = mysql_fetch_assoc($result)) {
				// Assign variables //
				$box_name = $row['box_name'];
				$box_id = $row['box_id'];
				$old_location = $row['old_location'];
				$author = $row['author'];
				
				if ($old_location != NULL) {
					if ($box_name != NULL && $author != NULL) {
						echo "<td class='wholeLink'><a href='/storage/tube_view.php?box_id=$box_id'>$box_name
							<br>$author
							<br>previously: $old_location
						</a></td>";
					} else {
						if ($box_name != NULL) {
							echo "<td class='wholeLink'><a href='/storage/tube_view.php?box_id=$box_id'>$box_name
								<br>previously: $old_location
							</a></td>";
						} else {
							if ($author != NULL) {
								echo "<td class='wholeLink'><a href='/storage/tube_view.php?box_id=$box_id'>$author
									<br>previously: $old_location
								</a></td>";
							} else {
								echo "<td>Empty Space</td>";
							}
						}
					}
				} else {
					if ($box_name != NULL && $author != NULL) {
						echo "<td class='wholeLink'><a href='/storage/tube_view.php?box_id=$box_id'>$box_name
							<br>$author
						</a></td>";
					} else {
						if ($box_name != NULL) {
							echo "<td class='wholeLink'><a href='/storage/tube_view.php?box_id=$box_id'>$box_name</a></td>";
						} else {
							if ($author != NULL) {
								echo "<td class='wholeLink'><a href='/storage/tube_view.php?box_id=$box_id'>$author</a></td>";
							} else {
								echo "<td>Empty Space</td>";
							}
						}
					}
				}
			}
		}
	}
	
	// HAVEN'T IMPLEMENTED THE FOLLOWING FUNCTIONS //
	// sets a word limit on a string (for display purposes)
	function string_limit_words($string, $word_limit) {
        $words = explode(' ', $string);
        return implode(' ', array_slice($words, 0, $word_limit));
    }
?>