<!-- Copyright (c) 2011 Katherine Erickson -->

<div id="topBar">
	<?php
		if ($_SERVER["PHP_SELF"] != "/index.php") {
			echo '<div id="topBarLeft"><a href="/index.php">Home</a></div>';
		} 
	?>
	<div id="topBarRight">
		Logged in as
		<?php
			echo $_SESSION["logged_in"];
			?>
		&nbsp;&nbsp;&nbsp;<a href="/api/logout.php">Logout</a>
	</div>
</div>