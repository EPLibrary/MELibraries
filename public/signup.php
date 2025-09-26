<?php
session_start();
$_SESSION['agree'] = isset($_POST['agree']) && $_POST['agree'] == 'on';

$pageTitle="ME Libraries | Sign up";
include 'header.php';

if ($_SESSION['originating_ip']=='10.3.0.79'){?>
	<pre class="debug">
		<?php #Diagnostics crap
			print_r($_SESSION);	
		?>
	</pre>
<?php }
/*
	Here we have to determine if the user is registered to any libraries and if his/her information has changed with those libraries.
*/



?>

<script>
function submitTheForm(libIdx) {
    // e.preventDefault();
    const loadSpinner = document.querySelector('.loadSpinner')
    if (loadSpinner) loadSpinner.style.display = 'none'

    const buttons = document.querySelectorAll('.button')
    buttons.forEach(btn => btn.style.display = 'block')

    document.getElementById('spinner' + libIdx).style.display = 'block'
    document.getElementById('form' + libIdx).submit()
    document.getElementById('joinlink' + libIdx).style.display = 'none'
}
</script>


<div class="mainContent" id="mainContent" style="min-width:695px;">

<a href="index.php" style="border:none;" title="Return to Login"><img id="meLogoTop" src="images/ME_Libraries_Logo_black.png"/></a>
<h1 class="pageTitle">Sign Up</h1>

<div class="subContent">


<?php


/* Connect to the DB and get the list of libraries that the user is not already registered to. */
include '../melibraries-db-config.php';

// Check connection
if (mysqli_connect_errno())  {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

//Query for libraries this user has already joined that need to be updated
// OR libraries where the hash matches but the userID does not

/* // This original version only shows libraries that need an update

$query="SELECT * FROM membership m
	INNER JOIN user u ON m.user_record_index=u.record_index
	INNER JOIN library l on m.library_record_index=l.record_index
	WHERE (u.userid='".$_SESSION["customer"]["ID"]."' AND user_info_hash!='".$_SESSION["customerHash"]."')
	OR ((m.userid!='".$_SESSION["customer"]["ID"]."' OR m.userid IS NULL) AND user_info_hash='".$_SESSION["customerHash"]."')";
*/

// New version shows all joined libraries even if the hash hasn't changed 
$query="SELECT * FROM membership m
	INNER JOIN user u ON m.user_record_index=u.record_index
	INNER JOIN library l on m.library_record_index=l.record_index
	WHERE (u.userid='".$_SESSION["customer"]["ID"]."' )
	OR ((m.userid!='".$_SESSION["customer"]["ID"]."' OR m.userid IS NULL) AND user_info_hash='".$_SESSION["customerHash"]."')";



$result = mysqli_query($con, $query);
$numUpdates = mysqli_num_rows($result);
// out of service messaging
$outOfServiceLibraryNamesArray = [
	"Strathcona County Library & Fort Saskatchewan Public Library",
	"St. Albert Public Library"
];
if (mysqli_num_rows($result)>0) {
	echo '<h2 class="blue" style="clear:both;">Update your information at these libraries:</h2>';
	echo '<table class="libTable">';
	while($row = mysqli_fetch_assoc($result)) {
		if ($row['disabled'] !=1) {
	?>
	
	<tr>
	<td>
		<a href="<?=$row['library_url']?>/" style="border:none;"><img src="libraries/<?=$row['record_index']?>.png" style="width:160px;vertical-align:middle;" alt="<?=$row['library_name']?>" title="<?=$row['library_name']?>"></a>
	</td>
	<td><form name="form<?=$row['library_record_index']?>" id="form<?=$row['library_record_index']?>" action="join.php" method="post">
			<input type="hidden" name="joinLibrary" id="joinLibrary" value="<?=$row['record_index']?>" />
			<h3><?=$row['library_name']?></h3>
			<a href="javascript:void(0);" id="joinlink<?=$row['library_record_index']?>" class="button join" onClick="submitTheForm('<?=$row['library_record_index']?>')">Update&nbsp;&#9658;</a><span class="loadSpinner buttonlike" id="spinner<?=$row['library_record_index']?>"><img src="images/ajax-loader.gif"/></span>
			<a class="terms" href="<?=$row['library_policy_url']?>">Terms & Conditions</a>
		</form>
	</td>
	<!-- if library name is in $outOfServiceLibraryNamesArray, show a message -->
	<?php if (in_array($row['library_name'], $outOfServiceLibraryNamesArray)) { ?>
		<td style="border:1px solid #FF0000; padding: 10px;">
			<p>This Library is not currently accepting ME registrations due to a technical issue. We apologize for the inconvenience.</p>
		</td>
	<?php } ?>
	</tr>	


	<?php
		}//end hide if not disabled
	}
	echo '</table><!--updatable libraries table-->';
}




//Query for libraries that we are not a member of and aren't natively from
//Later I will need to adjust this to show libraries needing an update (the hash differs from our own).
//This should now also avoid showing libraries where the hash is the same but the user is different. (these require an update, not a join).
$query="SELECT * FROM library l 
JOIN librarycom lc ON l.record_index=lc.library_record_index
WHERE l.record_index != ".$_SESSION["libraryData"]["libraryRecordIndex"]." AND l.record_index NOT IN (
SELECT m.library_record_index from user u INNER JOIN membership m ON u.record_index = m.user_record_index 
WHERE u.userid='".$_SESSION["customer"]["ID"]."' OR m.user_info_hash='".$_SESSION["customerHash"]."')";

$result = mysqli_query($con, $query);

$numToJoin = mysqli_num_rows($result);
if (mysqli_num_rows($result)>0) {
	echo '<h2 class="blue" style="clear:both;">Choose new libraries to join.</h2>';
	echo '<table class="libTable">';
	while($row = mysqli_fetch_assoc($result)) {
		if ($row['disabled'] !=1) {
?>
		<tr>
		<td>
			<a href="<?=$row['library_url']?>/" style="border:none;"><img src="libraries/<?=$row['record_index']?>.png" style="width:160px;vertical-align:middle;" alt="<?=$row['library_name']?>" title="<?=$row['library_name']?>"></a>
		</td>
		<td><form name="form<?=$row['library_record_index']?>" id="form<?=$row['library_record_index']?>" action="join.php" method="post">
				<input type="hidden" name="joinLibrary" id="joinLibrary" value="<?=$row['record_index']?>" />
				<h3><?=$row['library_name']?></h3>
				<a href="javascript:void(0);" id="joinlink<?=$row['library_record_index']?>" class="button join" onClick="submitTheForm('<?=$row['library_record_index']?>');">Join&nbsp;&#9658;</a><span class="loadSpinner buttonlike" id="spinner<?=$row['library_record_index']?>"><img src="images/ajax-loader.gif" /></span>
				<a class="terms" href="<?=$row['library_policy_url']?>">Terms & Conditions</a>
			</form>
		</td>
		<!-- if library name is in $outOfServiceLibraryNamesArray, show a message -->
		<?php if (in_array($row['library_name'], $outOfServiceLibraryNamesArray)) { ?>
			<td style="border:1px solid #FF0000; padding: 10px;">
				<p>This Library is not currently accepting ME registrations due to a technical issue. We apologize for the inconvenience.</p>
			</td>
		<?php } ?>
		</tr>
<?php
		}//end hide if not disabled
	}
echo '</table>';
}	

/* Do something nice for the people who have nothing to do here */
if (($numUpdates + $numToJoin) == 0) {
	echo '<p style="text-align:center;">You have joined all available libraries and your records are up to date!</p>';
}
?>




	
</div><!--subContent-->
<div id="spacer"></div>
</div><!--mainContent-->



<?php
include 'footer.php';
?>