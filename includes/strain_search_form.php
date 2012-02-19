<!-- Copyright (c) 2011 Katherine Erickson -->

<form method="GET" action="/strains/strains.php">
	Search Strain Database:&nbsp;
	<input type="text" name="search_term" value="<?php echo mysql_real_escape_string($_GET['search_term']); ?>"></input>
	<input type="submit" value="Search"></input>
</form>