<?php include ('./includes/global.php'); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>RNAi Library Records</title>

		<link rel="stylesheet" type="text/css" href="./stylesheets/style.css">

		<script type="text/javascript" src="./js/jquery.js"></script>
		<script type="text/javascript" src="./js/javascript.js"></script>
	</head>
	<body id='library_view'>
		<div id='content'>
			<?php
				include ("./includes/top_bar.php");
				include ("./includes/library_search_form.php");
				
				if ($_GET["search_term"]) {
					$search_term = $_GET["search_term"];
					$query = "SELECT library.plate_id
						FROM library
						WHERE library.plate_id = '$search_term'
						LIMIT 1";

					$result = mysql_query($query);
					
					if (!$result) {
						echo 'Could not run query: ' . mysql_error();
						exit;
					}
					
					if (mysql_num_rows($result) != 0) {
						while ($row=mysql_fetch_assoc($result)) {
							$plate = $row['plate_id'];
							if (preg_match("/-/", $plate)) {
								echo "<h1>$plate</h1>";
							} else {
								echo "<h1>Vidal $plate</h1>";
							}
						}
						
						echo "<div class='plate'>";
						
						$query = "SELECT library.well_position, library.clone, library.gene
							FROM library
							WHERE library.plate_id = '$search_term'
							ORDER BY library.well_position";

						$result = mysql_query($query);
						
						if (!$result) {
							echo 'Could not run query: ' . mysql_error();
							exit;
						}

						while ($row=mysql_fetch_assoc($result)) {
							$plate = $row['plate_id'];
							$well_position = $row['well_position'];
							$clone = $row['clone'];
							$gene = $row['gene'];
							if ($clone == NULL) {
								$well_class = 'status0';
							} else {
								$well_class = 'status1';
							}
							//create a div per well to visualize each as empty or colony.  Then create an invisible div per well with the clone and gene, which will appear in the pop-up on hover
							echo "
								<div class='well $well_class' id='$well_position'>&nbsp;$well_position</div>
								<div class='invisible'>$clone<br>$gene</div>
							";
						}
						
						//create the pop-up window that will show the clone and gene name on hover
						echo "</div>
						
						<div id='hoverClone'></div>
						";
					} else {
						echo "<h1>Sorry; no plate matched your query!</h1><br><img src='./images/sad-puppy.jpg' style='margin-left:280px'>";
					}
				}
			?>
		</div>
	</body>
</html>