<?php include($_SERVER["DOCUMENT_ROOT"] . "/includes/global.php"); ?>
<!-- Copyright (c) 2010-2012 Katherine Erickson -->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<?php include("includes/head.php"); ?>
	<body id='index'>
		<div id="content">
			<?php
				if ($_SESSION["logged_in"]) {
					include("includes/top_bar.php");
				}
			?>
			<div id="indexContainer">
			
				<h1>Katherine's Piano Lab Tools</h1>
				<br>
				<br>
				<?php
					// if session variable 'login error' is present, deliver login failure message and then unset the 'login error' session variable so they can try again //
					if ($_SESSION["login_error"]) {
						echo "Invalid Login Credentials";
						unset($_SESSION["login_error"]);
					}
				
					// if session variable 'logged in' is NOT present, allow user to try logging in //
					if (!$_SESSION["logged_in"]) {
				?>
						<form id="login" method="GET" action="/backend/login.php">
							<table>
								<tr>
									<td class="loginLabel">
										username:
									</td>
									<td>
										<input class="loginInput" type="text" name="username" value=""></input>
									</td>
								</tr>
								<tr>
									<td class="loginLabel">
										password:
									</td>
									<td>
										<input class="loginInput" type="password" name="password" value=""></input>
									</td>
							</table>
							<br><input type="submit" value="Login"></input>
						</form>
					
				<?php
					// if session variable 'logged in' IS present, allow user to 
					//search strain DB, view all strains, or search RNAi plates.  
					} else {
						echo "The strain and freezer databases are no longer stored here. Please go to the new lab website:";
						echo ' <a href="http://gunsiano.webfactional.com">gunsiano.webfactional.com</a>';
					
						echo "<br><br><br><h2>Ahringer RNAi Library Database (96-well format)</h2>";
						include("includes/clone_search_form.php");
						echo "<br>";
						include("includes/library_search_form.php");
						echo "<br>";
						include("includes/plate_search_form.php");
						echo "<br>";
						
						// if admin, allow to add new RNAi stamp data.
						if ($_SESSION["admin"] == 1) {
							include("includes/new_stamp_search_form.php");
						}
					}
				?>
			</div>
		</div>
	</body>
</html>
