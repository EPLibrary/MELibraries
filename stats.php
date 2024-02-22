<?php
session_start();
$pageTitle="ME Libraries | Statistics";
include 'header.php';

/* Connect to the DB and get the list of libraries that the user is not already registered to. */
include '../melibraries-db-config.php';
?>
<div class="mainContent" id="mainContent">

<a href="index.php" style="border:none;" title="Return to Login"><img id="meLogoTop" src="images/ME_Libraries_Logo_black.png"/></a>
<h1 class="pageTitle greenbg">Statistics</h1>

<div class="subContent">
<h2>ME Libraries Statistics</h2>

<p>General usage statistics for the ME Libraries service can be found on this page.</p>

<a href="stats_by_month.php">Download CSV of Data By Month</a>

<?php

// Count the number of memberships for each library. This is the number of times that each library has been joined.
$query= "SELECT library_name, COUNT(*) AS members_joined, l.record_index FROM membership m JOIN library l ON l.record_index=m.library_record_index GROUP BY l.record_index";

$result = mysqli_query($con, $query);

?>

<h3>Total Memberships for Each Library</h3>
<p>Number of users who have joined each of the following libraries.</p>
<table class="stats">
	<tr>
		<th>Library</th>
		<th>Joined</th>
	</tr>
<?php

while($row = mysqli_fetch_assoc($result)) {
	echo "<tr>";
	echo "<td>".$row["library_name"]."</td>";
	echo '<td style="text-align:right;">'.$row["members_joined"]."</td>";
	echo "</tr>";
}
?>

</table>

<?php
// Show every Library's members grouped by their home library
$query="SELECT COUNT(*) as members_joined, l.record_index, l.library_name, u.home_library_record_index, lu.library_name AS home_library_name FROM membership m
JOIN user u on m.user_record_index=u.record_index
JOIN library l ON m.library_record_index=l.record_index
JOIN library lu ON u.home_library_record_index=lu.record_index
GROUP BY m.library_record_index, u.home_library_record_index";

$result = mysqli_query($con, $query);

?>

<h3>Registrations Grouped by Library</h3>
<p>Breakdown of the above table showing where each of the users are from.</p>
<table class="stats">
<!-- 	<tr>
		<th>Home Library</th>
		<th>Joined</th>
	</tr>
 -->
<?php
$lastLibrary="";
while($row = mysqli_fetch_assoc($result)) {
	if ($lastLibrary!=$row["library_name"]) {
		if ($lastLibrary!="") echo '<tr style="background-color:white;"><td colspan="2" style="height:20px;"></td></tr>';
		echo '<tr class="titleRow"><th>'.$row["library_name"].'</th><th>Joined</th></tr>';
	}
	echo "<tr>";
	echo "<td>".$row["home_library_name"]."</td>";
	echo '<td style="text-align:right;">'.$row["members_joined"]."</td>";
	echo "</tr>";
	$lastLibrary=$row["library_name"];
}
?>

</table>




<div style="height:50px;"></div>
<a href="http://melibraries.ca/">Return to MELibraries.ca</a>	

	
</div><!--subContent-->
<div id="spacer"></div>
</div><!--mainContent-->
<?php
include 'footer.php';
?>