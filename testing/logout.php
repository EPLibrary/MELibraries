<?php
session_start();
session_destroy();


$pageTitle="ME Libraries | Sign in";
include 'header.php';

?>
<div class="mainContent" id="mainContent" style="min-width:695px;">
	<a href="index.php" style="border:none;"><img id="meLogoTop" src="images/ME_Libraries_Logo_black.png"></a>
	<h1 class="pageTitle">Log Out</h1>
	<div class="subContent" style="text-align:center;">
	<h2>Your session has been logged out.</h2>
	<p><a href="index.php">Login to MELibraries.ca</a>.</p>
	</div>
</div>
<?php	
	include 'footer.php';
	exit();
?>
