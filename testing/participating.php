<?php
session_start();
$pageTitle="ME Libraries | Participating Libraries";
include 'header.php';

/*
	On this page we will insert the member into the user table if they haven't been added yet.
	After this, we will insert the membership for the library that they have just joined into the membership table
	
	There will be a component here that requires talking to the server to do.
	Send request to create/update user, 
	$message=array(
		"code" => "CREATE_CUSTOMER",
		"authorityToken" => $authorityToken,
		"customer" => "null"
	);	
*/


?>
<div class="mainContent" id="mainContent">

<a href="index.php" style="border:none;" title="Return to Login"><img id="meLogoTop" src="images/ME_Libraries_Logo_black.png"/></a>
<h1 class="pageTitle greenbg">Libraries</h1>

<div class="subContent">
<h2>Participating Libraries</h2>
<p>The below libraries are currently all participating in the ME Libraries service. Check back regularly as more Alberta libraries will be added through 2014!</p>

<ul>
	<li>Calgary Public Library</li>
	<li>Chinook Arch Regional Library System</li>
	<li>Edmonton Public Library</li>
	<li>Fort Saskatchewan Public Library</li>
	<li>Parkland Regional Library</li>
	<ul class="columns">
		<li>Alix Public Library</li>
		<li>Alliance Public Library</li>
		<li>Amisk Municipal Library</li>
		<li>Bashaw Municipal Library</li>
		<li>Bawlf - David Knipe Memorial Library</li>
		<li>Bentley Municipal Library</li>
		<li>Big Valley Municipal Library</li>
		<li>Blackfalds Public Library</li>
		<li>Bodo Public Library</li>
		<li>Bowden Public Library</li>
		<li>Brownfield Community Library</li>
		<li>Cadogan Public Library</li>
		<li>Camrose Public Library</li>
		<li>Caroline Municipal Library</li>
		<li>Carstairs Public Library</li>
		<li>Castor Municipal Library</li>
		<li>Clive Public Library</li>
		<li>Coronation Memorial Library</li>
		<li>Cremona Municipal Library</li>
		<li>Czar Public Library</li>
		<li>David Knipe Memorial Library (Bawlf)</li>
		<li>Daysland Public Library</li>
		<li>Delburne Municipal Library</li>
		<li>Didsbury Municipal Library</li>
		<li>Donalda Public Library</li>
		<li>Eckville Municipal Library</li>
			
	</ul><!--columns-->
	<ul class="columns">
		<li>Edberg Public Library</li>
		<li>Elnora Public Library</li>
		<li>Forestburg Public Library</li>
		<li>Galahad Public Library</li>
		<li>Hardisty Public Library</li>
		<li>Hay Lakes Municipal Library</li>
		<li>Heisler Municipal Library</li>
		<li>Hughenden Public Library</li>
		<li>Innisfail Public Library</li>
		<li>Killam Public Library</li>
		<li>Lacombe - Mary C. Moore Public Library</li>
		<li>Lougheed Public Library</li>
		<li>Mary C. Moore Public Library (Lacombe)</li>
		<li>Nordegg Public Library</li>
		<li>Olds Municipal Library</li>
		<li>Penhold and District Public Library</li>
		<li>Ponoka Jubilee Library</li>
		<li>Provost Municipal Library</li>
		<li>Rimbey Municipal Library</li>
		<li>Rocky Mountain House Public Library</li>
		<li>Sedgewick & District Municipal Library</li>
		<li>Spruce View Community Library</li>
		<li>Stettler Public Library</li>
		<li>Sundre Municipal Library</li>
		<li>Sylvan Lake Municipal Library</li>
		<li>Water Valley Public Library</li>
	</ul><!--columns-->
	<li style="clear:left;">St. Albert Public Library</li>
	<li>Strathcona County Library</li>
	<li>Shortgrass Library System</li>
	<ul class="columns">
		<li>Alcoma Community Library</li>
		<li>Bassano Memorial Library</li>
		<li>Bow Island Municipal Library</li>
		<li>Brooks Public Library</li>
		<li>Duchess &amp; District Public Library</li>
		<li>Foremost Municipal Library</li>
	</ul><!--columns-->
	<ul class="columns">
		<li>Graham Community Library</li>
		<li>Medicine Hat Public Library</li>
		<li>Redcliff Public Library</li>
		<li>Rolling Hills Public Library</li>
		<li>Rosemary Community Library</li>
		<li>Tilley &amp; District Public Library</li>
	</ul><!--columns-->
</ul>
<div style="clear:left;"></div>



<a href="http://melibraries.ca/">Return to MELibraries.ca</a>	

	
</div><!--subContent-->
<div id="spacer"></div>
</div><!--mainContent-->
<?php
include 'footer.php';
?>