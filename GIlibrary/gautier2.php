<?php
  // Copyright (c) 2011 Katherine Erickson
  include ('../includes/global.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>RNAi Library Records</title>

		<link rel="stylesheet" type="text/css" href="../stylesheets/style.css">

		<script type="text/javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" src="../js/javascript.js"></script>
	</head>
	<body id='plate_records'>
		<div id='content'>
			<?php
				include ("../includes/top_bar.php");
				echo "<h1>Stamps from 06/17/2011</h1>";
				$query = "SELECT DISTINCT stamps.plate_id
					FROM stamps
					WHERE stamps.date = '2011-06-17'
					ORDER BY stamps.plate_id
				";
				$result = mysql_query($query);
				if (!$result) {
					echo 'Could not run query: ' . mysql_error();
					exit;
				}
				
				while ($row = mysql_fetch_assoc($result)) {
					$plate_id = $row['plate_id'];
					if (preg_match("/-/", $plate_id)) {
						echo "<h1>$plate_id</h1>";
					} else {
						echo "<h1>Vidal $plate_id</h1>";
					}
					echo "<div class='plateSmall' style='margin: 0 auto;'>";
					$innerQuery = "SELECT stamps.well_position, stamps.status_id
						FROM stamps
						WHERE stamps.date = '2011-06-17' AND stamps.plate_id = '$plate_id'
						ORDER BY stamps.well_position
					";
					$innerResult = mysql_query($innerQuery);
					if (!$innerResult) {
						echo 'Could not run query: ' . mysql_error();
						exit;
					}
					
					while ($innerRow = mysql_fetch_assoc($innerResult)) {
						$well_position = $innerRow['well_position'];
						$status = $innerRow['status_id'];
						echo "
							<div class='wellSmall status$status'>$well_position</div>
						";
					}
					echo "</div>";
				}
			?>
		</div>
	</body>
</html>