<?php
  // Copyright (c) 2011 Katherine Erickson

	function reconfigure_date($date){
		if ($date) {
			$times = explode('-', $date);
			return $times[1] . "/" . $times[2] . "/" . $times[0];
		} else {
			return "";
		}
	}
	
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
	
	// generate genotype from a template using pieces
	function generate_genotype($genotype, $transgene_id, $vector_template_id,
		$gene, $sequence, $promotor, $threePrimeUTR) {
		if (strlen($genotype) <= 2 && strlen($genotype) >= 1) {
			// retrieve and define the genotype template
			$query = "SELECT genotype.genotype 
				FROM genotype 
				WHERE genotype.id = $genotype
			";
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
				$query = "SELECT transgene.name, vector.gene, vector_template.genotype
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
					$transgene_name = $row['name'];
					$vector_template_genotype = $row['genotype'];
					$gene = $row['gene'];
				}
				
				$vector = preg_replace('/gene/', $gene, $vector_template_genotype);
				$genotype = preg_replace('/vector_genotype/', $vector, $genotype);
				$genotype = preg_replace('/transgene_name/', $transgene_name, $genotype);
				
			// if there is a vector template provided
			} else if ($vector_template_id != NULL) {
				// retrieve and define the vector template
				$query = "SELECT vector_template.genotype 
					FROM vector_template 
					WHERE vector_template.id = $vector_template_id
				";
				$result = mysql_query($query);
				if (!$result) {
					echo 'Could not run query: ' . mysql_error();
					exit;
				}
				while ($row = mysql_fetch_assoc($result)) {
					$vector_genotype = $row['genotype'];
				}

				// If a gene or a sequence is provided
				if ($gene != NULL || $sequence != NULL) {
					// If the gene is null, use the sequence
					if ($gene == NULL) {
						$gene = $sequence;
					}

					// Define genotype, replacing the geneEquivalent in the template with the gene or sequence
					$vector = preg_replace('/gene/', $gene, $vector_genotype);

				// If no gene or sequence, but author is Sabbi...
				} else if ($promotor != NULL && $threePrimeUTR != NULL) {
					// Define genotype, replacing the promotor and threePrime equivalents
					$vector = preg_replace('/promotor/', $promotor, $vector_genotype);
					$vector = preg_replace('/threePrimeUTR/', $threePrimeUTR, $vector);

				// If no gene or sequence, or no promotor and 3'UTR
				} else {
					$vector = null;
				}
				
				// define genotype as the genotype template with the vector filled in
				$genotype = preg_replace('/vector_genotype/', $vector, $genotype);
			}
		}
		
		// return the genotype
		return $genotype;
	}
		
// I haven't yet implemented following function	
	function string_limit_words($string, $word_limit) {
        $words = explode(' ', $string);
        return implode(' ', array_slice($words, 0, $word_limit));
    }

?>