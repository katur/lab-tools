<!-- Copyright (c) 2010-2012 Katherine Erickson -->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<?php include("../includes/head.php"); ?>
	<body id='strain'>
		<div id="content">
			<?php
				include("../includes/top_bar.php");
				
				// if there is data from a POST, insert the post into the database
				if ($_POST) {
					$strain = mysql_real_escape_string($_POST['strain']);
					$renamed_strain = rename_strain($strain);
					$species_id = mysql_real_escape_string($_POST['species_id']);
					$mutagen_id = mysql_real_escape_string($_POST['mutagen_id']);
					$genotype = mysql_real_escape_string($_POST['genotype']);
					$transgene_id = mysql_real_escape_string($_POST['transgene_id']);
					$wormbase = mysql_real_escape_string($_POST['wormbase']);
					$author_id = mysql_real_escape_string($_POST['author_id']);
					$lab_id = mysql_real_escape_string($_POST['lab_id']);
					$date_created = mysql_real_escape_string($_POST['date_created']);
					$received_from = mysql_real_escape_string($_POST['received_from']);
					$date_received = mysql_real_escape_string($_POST['date_received']);
					$outcrossed = mysql_real_escape_string($_POST['outcrossed']);
					$culture = mysql_real_escape_string($_POST['culture']);
					$remarks = mysql_real_escape_string($_POST['remarks']);
					
					$query = "INSERT INTO strains (species_id, mutagen_id, strain, strain_sort, genotype, transgene_id, wormbase, author_id, lab_id, date_created, received_from, date_received, outcrossed, culture, remarks) VALUES ($species_id, $mutagen_id, '$strain', '$renamed_strain', '$genotype', $transgene_id, $wormbase, $author_id, $lab_id, '$date_created', '$received_from', '$date_received', $outcrossed, '$culture', '$remarks')";

					// run query
					mysql_query($query) or die(mysql_error());
					
					echo "strain submitted";
					
				// if no data from a POST, collect data for a new strain
				} else {
					?>
					<h1>Add a new strain</h1>
					<form method='post' action='./new_strain.php'>
						<span>Strain Name</span>
						<input name='strain' type='text' size='20'><br>
						
						<span>Species</span>
						<select name='species_id'>
							<option value = '1' selected='selected'>Caenorhabditis elegans</option>
						</select><br>
						
						<span>Mutagen</span>
						<select name='mutagen_id'>
							<option value = '1'>Microparticle Bombardment</option>
							<option value = '2'>Injection</option>
							<option value = '3'>EMS</option>
							<option value = '4'>ENU</option>
							<option value = '5'>Gamma Rays</option>
							<option value = '6'>Cross</option>
						</select><br>
						
						<span>Genotype</span>
						<span style='color: #666; font-size: 13px;'>(if pattern, enter pattern_id)</span>
						<input name='genotype' type='text' size='50'><br>
						
						<!-- selections only ??? if not there, add new ???-->
						<span>Transgene</span>
						<span style='color: #666; font-size: 13px;'>(only if 'pattern' genotype)</span>
						<input name='transgene_id' type='text' size='2'><br>
						<br>
						
						<span>In Wormbase?</span>
						<select name='wormbase'>
							<option value = '1'>Yes</option>
							<option value = '0' selected='selected'>No</option>
						</select><br>
						
						<!-- selections only ??? if not there, add new ???-->
						<span>Author ID</span>
						<input name='author_id' type='text' size='2'><br>
						<br>
						
						<!-- selections only ??? if not there, add new ???-->
						<span>Lab ID</span>
						<input name='lab_id' type='text' size='2'><br>
						<br>
						
						<span>Date Created</span>
						<span style='color: #666; font-size: 13px;'>(in format YYYYMMDD)</span>
						<input name='date_created' type='text' size='10'><br>
						
						<span>Received From</span>
						<span style='color: #666; font-size: 13px;'>(e.g., CGC)</span>
						<input name='received_from' type='text' size='50'><br>
						
						<span>Date Received</span>
						<span style='color: #666; font-size: 13px;'>(in format YYYYMMDD)</span>
						<input name='date_received' type='text' size='10'><br>
						
						<!-- int only ??? -->
						<span>Number of times outcrossed</span>
						<span style='color: #666; font-size: 13px;'>(e.g., 5)</span>
						<input name='outcrossed' type='text' size='2'><br>
						
						<span>Culture</span><br>
						<textarea name='culture' rows='5' cols='50'></textarea><br>
						
						<span>Remarks</span><br>
						<textarea name='remarks' rows='5' cols='50'></textarea><br>
						
						<input type='submit' value='Submit to Database'></input>
					</form>
				<?php } 
			?>
		</div>
	</body>
</html>