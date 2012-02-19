<!-- Copyright (c) 2011 Katherine Erickson -->

<form method="GET" action="/GIlibrary/library_view_clone.php">
	Search to view location of a clone (96-well format):&nbsp;
	<input type="text" name="search_term" value="<?php echo mysql_real_escape_string($_GET['search_term']); ?>"></input>
	<input type="submit" value="Search"></input>
	<br><span style="color: #666; font-size: 13px;">("gsp-2" or "F56C9.1")</span>
</form>