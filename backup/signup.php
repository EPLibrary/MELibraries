<?php
session_start();
if ($_POST['agree']=='on') {
	$_SESSION['agree']=true;
}

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

<script language="javascript">

function submitTheForm(libIdx) {
	//e.preventDefault();
	$('.loadSpinner').hide();
	$('.button').show();
	$('#spinner'+libIdx).show();
	$('#form'+libIdx).submit();
	$('#joinlink'+libIdx).hide();
	
}

</script>


<div class="mainContent" id="mainContent" style="min-width:695px;">

<a href="index.php" style="border:none;" title="Return to Login"><img id="meLogoTop" src="images/ME_Libraries_Logo_black.png"/></a>
<h1 class="pageTitle">Sign Up</h1>

<div class="subContent">


<?php


/* Connect to the DB and get the list of libraries that the user is not already registered to. */
include '/home/its/mysql_config.php';

// Check connection
if (mysqli_connect_errno($con))  {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

//Query for libraries this user has already joined that need to be updated

$query="SELECT * FROM membership m
	INNER JOIN user u ON m.user_record_index=u.record_index
	INNER JOIN library l on m.library_record_index=l.record_index
	WHERE u.userid='".$_SESSION["customer"]["ID"]."' AND user_info_hash!='".$_SESSION["customerHash"]."'";

$result = mysqli_query($con, $query);
$numUpdates = mysqli_num_rows($result);
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
	</tr>	


	<?php
		}//end hide if not disabled
	}
	echo '</table><!--updatable libraries table-->';
}




//Query for libraries that we are not a member of and aren't natively from
//Later I will need to adjust this to show libraries needing an update (the hash differs from our own).
$query="SELECT * FROM library l 
JOIN librarycom lc ON l.record_index=lc.library_record_index
WHERE l.record_index != ".$_SESSION["libraryData"]["libraryRecordIndex"]." AND l.record_index NOT IN (
SELECT m.library_record_index from user u INNER JOIN membership m ON u.record_index = m.user_record_index 
WHERE u.userid='".$_SESSION["customer"]["ID"]."')";

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