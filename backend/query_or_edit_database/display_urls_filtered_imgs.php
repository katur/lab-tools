<html>
    <body>
        <?php
        	include('connect.php');
        	include('../includes/functions.php');
		
        	$query = "SELECT url FROM filtered_images";	
        	$result = mysql_query($query);
        	if (!$result) {
        		echo 'Could not run query: ' . mysql_error();
        		exit;
        	}
        	while ($row = mysql_fetch_assoc($result)) {
        		$url = $row['url'];
        		echo "<a href='$url'  target='_blank'>$url</a> <br>";
        	}
        ?>
    </body>
</html>