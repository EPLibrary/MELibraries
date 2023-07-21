<?php
session_start();
$pageTitle="ME Libraries | Sign up";
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
<h1 class="pageTitle greenbg">Terms &amp; Privacy</h1>

<div class="subContent">
<h2>Terms of Use</h2>

<p>By registering with ME Libraries, you agree to abide by the policies of each library you register with. Information regarding the policies of each library may be found below.</p>
<ul>
<?php
	//Query for library Terms of Use pages
    include '../melibraries-db-config.php';
	$query="SELECT * FROM library WHERE disabled !=1 ORDER BY library_name";

	$result = mysqli_query($con, $query);
	while($row = mysqli_fetch_assoc($result)) {
		echo '<li><a href="'.$row['library_policy_url'].'">'.$row['library_name'].'</a></li>';
	}

?>
</ul>
<p>Please report a lost card to your home library as soon as possible. Once you have a replacement home library card, contact the other libraries you�re registered with by phone or in person to update your account(s).</p>

<h2>Privacy Policy</h2>

<p>The information you submit to the Alberta public libraries is collected under the authority of Section 33(c) of the Freedom of Information and Protection of Privacy Act (Alberta). It will not be used for any other purpose. Each Metro library collects customer information under the authority of the Alberta Libraries Act and the Freedom of Information and Protection of Privacy Act.</p>


<a href="http://melibraries.ca/" style="display:block;margin-top:20px;">Return to MELibraries.ca</a>	

	
</div><!--subContent-->
<div id="spacer"></div>
</div><!--mainContent-->
<?php
include 'footer.php';
?>