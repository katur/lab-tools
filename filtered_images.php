<html>
    <body>
        <?php
            include('backend/connect.php');
    	    include('./includes/functions.php');
    	    
    	    $query = 'SELECT DISTINCT plate, well FROM filtered_images ORDER BY plate';
    	    $result = mysql_query($query);
        	if (!$result) {
        		echo 'Could not run query: ' . mysql_error();
        		exit;
        	}
        	$gene = 0;
        	while ($row = mysql_fetch_assoc($result)) {
        		$plate = $row['plate'];
        		$well = $row['well'];
        		echo "$gene: $plate, $well<br>";

                $subquery = "SELECT url_small, url FROM filtered_images WHERE plate = '$plate' AND well = '$well'";
                $subresult = mysql_query($subquery);
                if (!$subresult) {
        			echo 'Could not run query: ' . mysql_error();
        			exit;
        		}
        		while ($subrow = mysql_fetch_assoc($subresult)) {
        		    $url_small = $subrow['url_small'];
        		    $url = $subrow['url'];
        		    echo "<a href='$url' target='_blank'><img src='$url_small'></a> ";
        		}
        		$gene++;

                echo "<br><br>";
            }
        ?>

    </body>
</html>