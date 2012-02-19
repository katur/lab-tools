<?php
	include ('api/connect.php');
	include ('includes/functions.php');
	session_start();
	if (!$_SESSION["logged_in"] || $_SESSION["admin"] != 1) {
		header("location: /");
	}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Add A New Strain</title>

		<link rel="stylesheet" type="text/css" href="./stylesheets/style.css">

		<script type="text/javascript" src="/js/jquery.js"></script>
		<script type="text/javascript" src="/js/javascript.js"></script>
	</head>
	<body id='new_strain'>		
		<div id="content">
			<br><br>
			<?php include ("includes/top_bar.php");?>
			<h1>Add A New Strain</h1>
			<form>
				<table class="basic" id="strain">
					<tr>
						<td class="header">Strain</td>
						<td><input type="text" name="strain" value=""></input></td>
					</tr>
					<tr>
						<td class="header">Genotype</td>
						<td><input type="text" name="genotype" value=""></input></td>
					</tr>
					<tr>
						<td class="header">Contains</td>
						<td>
							<table class="invisible">
								<?php
									$counter = 1;
									while ($counter < 6) {
								?>
										<tr>
											<td>
												<?php
													$query = "SELECT * FROM categories";
													$result = mysql_query($query);
											
													if (!$result) {
														echo 'Could not run query: ' . mysql_error();
														exit;
													}
											
													echo "<select name='contains" . $counter . "_category'>";
														while ($row=mysql_fetch_assoc($result)) {
															$category_id = $row['category_id'];
															$category = $row['category'];
										
															echo "<option value='$category_id'>$category</option>";
														}
													echo "</select>";
												?>
											</td>
											<td><input type="text" name="contains<?php echo $counter; ?>" value=""></input></td>
										</tr>
								<?php
									$counter = $counter + 1;
									}
								?>
							</table>
						</td>
					</tr>
					<tr>
						<td class="header">Species</td>
						<td><i><input type="text" name="species" value=""></input></i></td>
					</tr>
					<tr>
						<td class="header">Made By</td>
						<td><input type="text" name="author" value=""></input></td>
					</tr>
					<tr>
						<td class="header">Date Created</td>
						<td><input type="text" name="date_created" value=""></input></td>
					</tr>
					<tr>
						<td class="header">Received From</td>
						<td><input type="text" name="received_from" value=""></input></td>
					</tr>
					<tr>
						<td class="header">Date Received</td>
						<td><input type="text" name="date_received" value=""></input></td>
					</tr>
					<tr>
						<td class="header">Mutagen Or Method Used</td>
						<td><input type="text" name="mutagen" value=""></input></td>
					</tr>
					<tr>
						<td class="header">Outcrossed</td>
						<td><input type="text" name="outcrossed" value=""></input></td>
					</tr>
					<tr>
						<td class="header">Phenotype</td>
						<td><input type="text" name="phenotype" value=""></input></td>
					</tr>
					<tr>
						<td class="header">Culture Conditions</td>
						<td><input type="text" name="culture" value=""></input></td>
					</tr>
					<tr>
						<td class="header">Reference</td>
						<td><input type="text" name="reference" value=""></input></td>
					</tr>
					<tr>
						<td class="header">Remarks</td>
						<td><input type="text" name="remarks" value=""></input></td>
					</tr>
					<tr>
						<td class="header">Link To WormBase</td>
						<td><input type="text" name="wormbase" value=""></input></td>
					</tr>
					<tr>
						<td class="header">Stock</td>
						<td>
							<table class="invisible">
							<!-- Query the stock table and display results -->
								<?php
									$query = "SELECT vat.vat, stock.rack, stock.box, stock.tube_position, stock.freeze_date, authors.author
									FROM stock
									LEFT JOIN vat
									ON vat.vat_id = stock.vat_id
									LEFT JOIN authors
									ON authors.author_id = stock.frozen_by
									WHERE stock.strain_id = '$strain_id'";

									$result = mysql_query($query);

									if (!$result) {
										echo 'Could not run query: ' . mysql_error();
										exit;
									}

									while ($row=mysql_fetch_assoc($result)) {
										// Assign variables //
										$vat = $row['vat'];
										$rack = $row['rack'];
										$box = $row['box'];
										$tube_position = $row['tube_position'];
										$freeze_date = $row['freeze_date'];
										$frozen_by = $row['author'];

										if ($freeze_date) {
											$freeze_date = reconfigure_date($freeze_date);
										}

										echo "
											<tr>
												<td><b>" . $vat . ":</b></td>
												<td>" . $rack . "-" . $box . "-" . $tube_position . "&nbsp;&nbsp;&nbsp;</td>
												<td><b>Freeze Date:</b></td>
												<td>" . $freeze_date . "</td>
												<td><b>Frozen By:</b></td>
												<td>" . $frozen_by . "</td>
											</tr>
										";	
									}
								?>
							</table>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>