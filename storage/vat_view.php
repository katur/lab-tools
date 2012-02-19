<!-- Copyright (c) 2011 Katherine Erickson -->

<?php include ('../includes/global.php'); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Piano Lab Storage</title>
		<link rel="stylesheet" type="text/css" href="../stylesheets/style.css">
		<script type="text/javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" src="../js/javascript.js"></script>
	</head>
	<body>
		<div id='content'>
			<?php
				include ("../includes/top_bar.php");				
				echo "<div id='storage'>";
					echo "<div id='vat_view'>";
						echo '<h1>Piano Lab Storage</h1><h1 class="detail">click to see contents</h1>';
						$query = "SELECT storage_vat.vat_name, storage_vat.id AS vat_id, storage_vat.vat_image
							FROM storage_vat
						";
						$result = mysql_query($query);
						if (!$result) {
							echo 'Could not run query: ' . mysql_error();
							exit;
						}
						
						while ($row = mysql_fetch_assoc($result)) {
							$vat_name = $row['vat_name'];
							$vat_id = $row['vat_id'];
							$vat_image = $row['vat_image'];
							if ($vat_name != NULL) {
								echo "<div class='vatImage' style='float:left; padding:20px;'><a href='/storage/rack_view.php?vat_id=$vat_id'><img src='/images/$vat_image' alt='hi' height='160px'><br>$vat_name</a></div>";
							}
						}
					echo "</div>";
				echo "</div>";
			?>
		</div>
	</body>
</html>