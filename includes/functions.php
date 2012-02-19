<?php
  // Copyright (c) 2011 Katherine Erickson
	
	// reconfigure the date to a month/date/year format
	function reconfigure_date($date){
		if ($date) {
			$times = explode('-', $date);
			return $times[1] . "/" . $times[2] . "/" . $times[0];
		} else {
			return "";
		}
	}
	
	// rename strain in sortable order (by adding zeros after lab designation)
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
	
	// generate wormbase link from strain name
	function generate_wormbase($strain) {
		return preg_replace('/strain_fill/', $strain, 'http://wormbase.org/db/gene/strain?query=strain_fill;class=Strain');
	}
	
	// generate genotype from a template using pieces
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
			
			// If the gene is null, use the sequence
			if ($gene == NULL) {
				$gene = $sequence;
			}
			
			// Replace holes in vector (gene, promotor, and/or threePrimeUTR)
			$vector_genotype = str_replace('gene', $gene, $vector_genotype);
			$vector_genotype = str_replace('promotor', $promotor, $vector_genotype);
			$vector_genotype = str_replace('threePrimeUTR', $threePrimeUTR, $vector_genotype);
			
			// Replace holes in genotype
			$genotype = str_replace('vector_genotype', $vector_genotype, $genotype);
			$genotype = str_replace('transgene_name', $transgene_name, $genotype);			
		}
		
		// return the genotype
		return $genotype;
	}
		
	
	
	// HAVEN'T IMPLEMENTED THE FOLLOWING FUNCTIONS //
	function string_limit_words($string, $word_limit) {
        $words = explode(' ', $string);
        return implode(' ', array_slice($words, 0, $word_limit));
    }
?>