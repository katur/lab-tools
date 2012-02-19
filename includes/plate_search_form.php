<form method="GET" action="./plate_records.php">
	Search to view growth history of an RNAi plate:&nbsp;
	<input type="text" name="search_term" value="<?php echo $_GET['search_term']; ?>"></input>
	<input type="submit" value="Search"></input>
	<br><span style="color: #666; font-size: 13px;">("V-4-B1" or "5" for Vidal 5)</span>
</form>