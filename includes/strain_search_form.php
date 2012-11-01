<!-- Copyright (c) 2010-2012 Katherine Erickson -->

<form method="GET" action="/strains/strains.php">
	search strain database:&nbsp;
	<input type="text" name="search_term" value="<?php echo mysql_real_escape_string($_GET['search_term']); ?>"></input>
	<input type="submit" value="Search"></input>
</form>