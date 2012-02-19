<!-- Copyright (c) 2010-2012 Katherine Erickson -->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<?php include("../includes/head.php"); ?>
	<body id='plate_records'>
		<div id='content'>
			<?php
				include("../includes/top_bar.php");
				include("../includes/plate_search_form.php");
				
				if (mysql_real_escape_string($_GET["search_term"])) {
					$search_term = mysql_real_escape_string($_GET["search_term"]);
					$query = "SELECT library.plate_id
						FROM library
						WHERE library.plate_id = '$search_term'
						LIMIT 1
					";

					$result = mysql_query($query);
					
					if (!$result) {
						echo 'Could not run query: ' . mysql_error();
						exit;
					}
					
					//use numrows to see if plate exists at all
					if (mysql_num_rows($result) != 0) {
						
						//create title as plate_id name
						while ($row = mysql_fetch_assoc($result)) {
							$plate_id = $row['plate_id'];
							if (preg_match("/-/", $plate_id)) {
								echo "<h1>$plate_id</h1>";
							} else {
								echo "<h1>Vidal $plate_id</h1>";
							}
						}
						
						echo "<div class='plateSmall' style='margin: 0 auto;'>";
						
						$query = "SELECT library.well_position, library.clone, library.gene
							FROM library
							WHERE library.plate_id = '$search_term'
							ORDER BY library.well_position
						";

						$result = mysql_query($query);
						
						if (!$result) {
							echo 'Could not run query: ' . mysql_error();
							exit;
						}
						
						//create an array in which to list the 96 well statuses.  Not specifying a key in the parenthesis creates default integer keys (starting with 0)
						$referenceArray = array();
						
						while ($row=mysql_fetch_assoc($result)) {
							$plate_id = $row['plate_id'];
							$well_position = $row['well_position'];
							$clone = $row['clone'];
							$gene = $row['gene'];
							if ($clone == NULL) {
								$well_class = 'status0';
								//add 0 to the array if the well is empty
								array_push($referenceArray,"0");
							} else {
								$well_class = 'status1';
								//add 1 to the array if the well is full
								array_push($referenceArray,"1");
							}
							
							//create a div per well to visualize each as empty or colony.  Then create an invisible div per well with the clone and gene, which will appear in the pop-up on hover
							echo "
								<div class='wellSmall $well_class' id='$well_position'>$well_position</div>
								<div class='invisible'>$clone<br>$gene</div>
							";
						}
						
						//create the pop-up window that will show the clone and gene name on hover
						
						echo "</div>
						
						
						<div id='hoverClone'></div>
						";
						
						//select dates that the plate has been stamped
						//"select distinct" in order to select only one per unique date
						$query = "SELECT DISTINCT stamps.date, stamps.plate_id, stamps.source_id, stamp_source.source
							FROM stamps
							LEFT JOIN stamp_source
							ON stamp_source.id = stamps.source_id
							WHERE stamps.plate_id = '$search_term'
							ORDER BY stamps.date
						";

						$result = mysql_query($query);

						if (!$result) {
							echo 'Could not run query: ' . mysql_error();
							exit;
						}
						
						//start a counter that will alternate between 0 and 1 so that every two plates are in a div (for style purposes)
						$counter = 0;
						
						//get result, and for each date, create a plate view of what grew (from stamps table)
						while ($row=mysql_fetch_assoc($result)) {
							$date = $row['date'];
							$dateDisplay = reconfigure_date($row['date']);
							$plate_id = $row['plate_id'];
							$source = $row['source'];
							$source_id = $row['source_id'];
							
							if ($counter == 0) {
								echo "<div class='plateRow'>";
							}
							
							//open div to wrap the title and plate
							echo "<div class='plateWrap'>";
							
								//set the title as the date
								echo "<h2>$dateDisplay<span style='font-size: 12px;'>&nbsp;&nbsp;($source)</span></h2>";
							
								//create div for plate image
								echo "<div class='plateSmall'>";
							
									//query data for each well
									$innerQuery = "SELECT stamps.well_position, stamps.status_id, library.clone, library.gene
										FROM stamps
										LEFT JOIN library
										ON library.well_position = stamps.well_position 
											AND library.plate_id = stamps.plate_id
										WHERE stamps.date = '$date' 
											AND stamps.plate_id = '$plate_id' 
											AND stamps.source_id = '$source_id'
										ORDER BY stamps.well_position
									";

									$innerResult = mysql_query($innerQuery);

									if (!$innerResult) {
										echo 'Could not run query: ' . mysql_error();
										exit;
									}
									
									//start a counter to be able to compare each well to the referenceArray
									$arrayCounter = 0;
									
									//start a counter of discrepancies from the reference plate
									$discrepancyCounter = 0;
									
									//for each well, assign a status to the "colony"
									while ($innerRow = mysql_fetch_assoc($innerResult)) {
										$well_position = $innerRow['well_position'];
										$clone = $innerRow['clone'];
										$gene = $innerRow['gene'];
										$status = $innerRow['status_id'];
										if ($referenceArray[$arrayCounter] == $status) {
											$wellBorder = '';
										} else {
											$wellBorder = 'badMatch';
											$discrepancyCounter = $discrepancyCounter + 1;
										}

										//create a div per well to visualize each as 0,1,or 2 status.  Then create an invisible div per well with the clone and gene, which will appear in the pop-up on hover
										echo "
											<div class='wellSmall status$status $wellBorder' id='$well_position-$date'>$well_position</div>
											<div class='invisible'>$clone<br>$gene</div>
										";
										
										//'$arrayCounter++;' is equivalent to '$arrayCounter = $arrayCounter + 1;'
										$arrayCounter++;
									}
									
									echo "</div>";
									
									//total discrepancies
									echo "total discrepancies = $discrepancyCounter";
									
									// comments
									
									echo "<div class='comments'><b>Comments:</b></div>";
									$innerQuery = "SELECT stamps.comments, stamps.well_position
										FROM stamps
										WHERE stamps.date = '$date' 
											AND stamps.plate_id = '$plate_id'
											AND stamps.source_id = '$source_id'
										ORDER BY stamps.well_position
									";

									$innerResult = mysql_query($innerQuery);

									if (!$innerResult) {
										echo 'Could not run query: ' . mysql_error();
										exit;
									}
									
									while ($innerRow=mysql_fetch_assoc($innerResult)) {
										$comments = $innerRow['comments'];
										$well_position = $innerRow['well_position'];
										
										if ($comments != NULL) {
											echo "<div class='comments'>$well_position: $comments</div>";
										}
									}
									

							echo "</div>";
							
							if ($counter == 1) {
								echo "</div>";
								$counter = 0;
							} else {
								$counter = 1;
							}
						}
					} else {
						echo "<h1>Sorry; no plate matched your query!</h1>
							<br><img src='./images/sad-puppy.jpg' style='margin-left:280px'>
						";
					}
				}
			?>
		</div>
	</body>
</html>