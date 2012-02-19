<?php
	function getAuthorName($name_id){
		$query = "SELECT name FROM authors WHERE id='$name_id'";
		$result = mysql_query($query);
		while ($row = mysql_fetch_row($result)) {
			return $row['name'];
		}
	}
	
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
	
	function generate_genotype($vector_id, $gene, $sequence){
		$query = "SELECT pattern FROM vector WHERE vector_id=$vector_id";
		$result = mysql_query($query);
		while ($row = mysql_fetch_assoc($result)) {
			$pattern = $row['pattern'];
		}
				
		if ($gene==NULL) {
			$geneEquivalent = $sequence;
		} else {
			$geneEquivalent = $gene;
		}
		
		$genotype = preg_replace('/geneEquivalent/', $geneEquivalent, $pattern);
		
		return $genotype;
	}
		
//I haven't yet implemented following function	
	function string_limit_words($string, $word_limit) {
        $words = explode(' ', $string);
        return implode(' ', array_slice($words, 0, $word_limit));
    }

?>