<?php
session_start();

$pageTitle="ME Libraries | Join";
include 'header.php';

/*
	1. Check if user is already a member of this library.
	2. If he is a member, set a variable that will change my request to Update
	3. If the create/update was succesfull (ok response), insert/update the database hash
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

<a href="logout.php" style="border:none;" title="Return to Login"><img id="meLogoTop" src="images/ME_Libraries_Logo_black.png"/></a>
<h1 class="pageTitle greenbg">Joining &amp; Updating</h1>

<div class="subContent">



<?php



/* Connect to the DB and get the list of libraries that the user is not already registered to
or where the hash is different (for updating) */
include '/home/its/mysql_config.php';

// Check connection
if (mysqli_connect_errno($con))  {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


$query="select * from librarycom  lc
JOIN library l ON l.record_index=lc.library_record_index
where lc.library_record_index='".$_POST["joinLibrary"]."'";
//I will also need to filter out other libraries that the user has already joined

if ($result=mysqli_query($con, $query)) {
	$libraryComData = mysqli_fetch_assoc($result);

} else echo "<h2>No communication info for library<h2>";


//Check to see if the user is already a member here. If so, we do an update.
$query="select u.record_index, m.record_index AS member_record_index, u.userid, u.home_library_record_index, m.user_record_index, m.library_record_index FROM user u
	INNER JOIN membership m
	ON u.record_index = m.user_record_index
	WHERE u.userid='".$_SESSION["customer"]["ID"]."' AND m.library_record_index=".$_POST["joinLibrary"];


$userExists=false;
$hasMembership=false;
	
$result=mysqli_query($con, $query);
if ($result->num_rows > 0) {
		$userInfo = mysqli_fetch_assoc($result);
		$hasMembership=true;
		$userExists=true;
} else {
	//Now check that the user exists at all
	$query="select * from user WHERE userid='".$_SESSION["customer"]["ID"]."'";
	$result=mysqli_query($con, $query);
	if ($result->num_rows > 0) {
		$userInfo = mysqli_fetch_assoc($result);
		$userExists=true;
	} 	
}


	

/* Do Socket connection and add user to foreign library 	*/
	//Token/API Key
	$authorityToken="55u1dqzu4tfSk2V4u5PW6VTMqi9bzt2d";
	$host = $libraryComData["library_server_url"];
	$port = $libraryComData["library_server_port"];

	
	
	//Hangup string we send when we're done
	$hangup = "XX0";
	//Buffer length in bytes
	$bufferlen = 2048;

	//JSON formatted parameters for socket with newline to terminate
	$message=array(
		"code" => "GET_STATUS",
		"authorityToken" => $authorityToken,
		"customer" => "null"
	);
	// If GET_STATUS is not okay, do error handling
	$message=json_encode($message);
	//Add newline so the server knows when the message is done.
	$message.="\n";

	// 10s Timeout 
	set_time_limit(10);

	// Create Socket
	$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");

	// Connect to the server
	if ($result = socket_connect($socket, $host, $port) == false) {
		$error=true;
		$errorMsg="Can't connect to server $host on port $port";
		echo('<h2>Error</h2><div class="subContent"><p class="error" style="display:inline;">'.$errorMsg.'</p><p>Please return to <a href="/">MeLibraries.ca</a>.</p></div>'); 
		include 'footer.php';
		exit();
	}


	// Read initial server message/ack
	if ($result = socket_read($socket, $bufferlen) == false) {
		$data["error"]=true;
		$data["errorMsg"]="Can't read from server $host";
		$data=json_encode($data);
		die ($data);
	};

	//Testing with Card No: "21221012345678", Pin: "64058","Billy, Balzac"
	
	if ($hasMembership) {
		$message=array(
			"code" => "UPDATE_CUSTOMER",
			"authorityToken" => "55u1dqzu4tfSk2V4u5PW6VTMqi9bzt2d",
			"userId" => '',
			"pin" => '',
			"customer" => json_encode($_SESSION["customer"])
			);
	
	} else {
		$message=array(
			"code" => "CREATE_CUSTOMER",
			"authorityToken" => "55u1dqzu4tfSk2V4u5PW6VTMqi9bzt2d",
			"userId" => '',
			"pin" => '',
			"customer" => json_encode($_SESSION["customer"])
			);
	}
	$message=json_encode($message);
	$message.="\n";

	//echo "<b>Sending Message:</b>\n<br />".$message;
	$result = (socket_write($socket, $message, strlen($message)));
	if ($result == false) {
		$error=true;
		$errorMsg="Could not send data to server $host";
		echo('<div class="mainContent" id="mainContent" style="min-width:695px;"><a href="index.php" style="border:none;"><img id="meLogoTop" src="images/Me_Logo_Color.png"></a><h1 class="pageTitle bluebg">Error</h1><div class="subContent"><p class="error" style="display:inline;">'.$errorMsg.'</p><p>Please return to <a href="/">MeLibraries.ca</a>.</p></div></div>'); 
		include 'footer.php';
		exit();
	}
	
	$serverReply = socket_read ($socket, $bufferlen);
	if ($serverReply == false) {
		$data["error"]=true;
		$data["errorMsg"]="Could not read server response";
		$data=json_encode($data);
		die($data);
	}
	echo '<p class="debug"><b>Reply From Server:</b><br />'.$serverReply.'</p>';
	$serverReply=json_decode($serverReply, true);
	
	// Hang up socket connection
	socket_write($socket, $hangup, strlen($hangup)) or die("Could not send data to server\n");

	//Close the socket
	socket_close($socket);


	
	//If the server replied that it was successful, we can do our database fun stuff.
	if ($serverReply["code"] == "SUCCESS") {
		echo '<h2 class="green" style="clear:both;">';
		if ($hasMembership) echo "Thanks for the update";
		else echo 'Welcome to the '.$libraryComData["library_name"];
		echo '.</h2>';
		
		//Insert the new user if he doesn't exist yet
		if ($userExists == false) {
			$query="INSERT INTO user (userid, home_library_record_index, date_last_activity, date_created)
			VALUES('".$_SESSION["customer"]["ID"]."','".$_SESSION["libraryData"]["libraryRecordIndex"]."', NOW(), NOW())";
			$result = mysqli_query($con,$query);
			if ( false===$result ) {
				printf('<p class="SQL error" style="display:block;">error: %s</p>\n', mysqli_error($con));
			}

			$user_record_index=mysqli_insert_id($con);
		} else {
			$user_record_index=$userInfo["record_index"];
		}
		
		if ($hasMembership == false) {
			$query="INSERT INTO membership (user_record_index, library_record_index, date_last_activity, user_info_hash)
			VALUES('".$user_record_index."','".$_POST["joinLibrary"]."', NOW(), '".$_SESSION["customerHash"]."')";
		}
		else {
			//else we update an existing record
			$query="UPDATE membership SET date_last_activity=NOW(), user_info_hash='".$_SESSION["customerHash"]."' WHERE record_index='".$userInfo["member_record_index"]."'";
		}
		//Execute the insert or update query.
		$result = mysqli_query($con,$query);
		if ( false===$result ) {
			printf('<p class="error" style="display:block;">SQL error: %s</p>\n', mysqli_error($con));
		}
		
		
	} elseif ($serverReply["code"] == "PIN_CHANGE_REQUIRED") {
		
		$newPin = preg_replace("/^ ?(\d+)[\a\s:](.*)/", '<span class="pin">$1</span><span class="libMessage">$2</span>', $serverReply["responseMessage"]);
		$newNakedPin = preg_replace("/^ ?(\d+)[\a\s:](.*)/", '$1', $serverReply["responseMessage"]);
		echo '<h2 class="green" style="clear:both;">';
		if ($hasMembership) {
			echo "Thanks for the update.</h2>";
			$pinMessage =  "<span style=\"color:red;\">Note:</span> your PIN for ".$libraryComData["library_name"]." has been changed to <b>$newPin</b>";
		} else {
			echo 'Welcome to the '.$libraryComData["library_name"].'.</h2>';
			$pinMessage = '<span style=\"color:red;\">Note:</span> your PIN for this library is different.<br />';
			$pinMessage.= 'Your pin for '.$libraryComData["library_name"].' has been set to: '.$newPin;

			
			//Send an email to the customer if they have a valid email address
			if (strlen($_SESSION["customer"]["EMAIL"]) > 5) {
				require_once "Mail.php";

				$from = "Me Libaries <noreply@melibraries.ca>";
				$to = $_SESSION["customer"]["FIRSTNAME"]." ".$_SESSION["customer"]["LASTNAME"]." <".$_SESSION["customer"]["EMAIL"].">";
				$subject = 'You have joined '.$libraryComData["library_name"];
				$body = "This is a friendly notice that the you now have joined ".$libraryComData["library_name"];
				$body .= " and now have access to its collections with your home library card number!\n";
				$body .= "Visit ".$libraryComData["library_name"]." at ".$libraryComData["library_url"];

				//Add a comment about the new PIN if it has been changed
				if ($serverReply["code"] == "PIN_CHANGE_REQUIRED") {
						
					//$newPin is set above
					$body .= "\n\nNote: Your PIN for ".$libraryComData["library_name"]." is different.\nIt has been set to ".$newNakedPin.".";
				}

				$host = "mail1.epl.ca";

				$headers = array (
					'From' => $from,
					'To' => $to,
					'Subject' => $subject);
					
				$smtp = Mail::factory(
					'smtp',
					array (
						'host' => $host,
						'auth' => false)
					);

				$mail = $smtp->send($to, $headers, $body);

				if (PEAR::isError($mail)) {
				  echo("<p>" . $mail->getMessage() . "</p>");
				} else {
				  echo('<p>You have been sent an email about the following:</p>');
				}
			}//END - If email longer than 3 characters

			
			
		}//End if is a new membership

		
		//Do database inserts for when PIN Changes is required - new user
		if ($userExists == false) {
			$query="INSERT INTO user (userid, home_library_record_index, date_last_activity, date_created)
			VALUES('".$_SESSION["customer"]["ID"]."','".$_SESSION["libraryData"]["libraryRecordIndex"]."', NOW(), NOW())";
			$result = mysqli_query($con,$query);
			if ( false===$result ) {
				printf('<p class="SQL error" style="display:block;">error: %s</p>\n', mysqli_error($con));
			}

			$user_record_index=mysqli_insert_id($con);
		} else {
			$user_record_index=$userInfo["record_index"];
		}
		
		if ($hasMembership == false) {
			$query="INSERT INTO membership (user_record_index, library_record_index, date_last_activity, user_info_hash)
			VALUES('".$user_record_index."','".$_POST["joinLibrary"]."', NOW(), '".$_SESSION["customerHash"]."')";
		}
		else {
			//else we update an existing record
			$query="UPDATE membership SET date_last_activity=NOW(), user_info_hash='".$_SESSION["customerHash"]."' WHERE record_index='".$userInfo["member_record_index"]."'";
		}
		//Execute the insert or update query.
		$result = mysqli_query($con,$query);
		if ( false===$result ) {
			printf('<p class="error" style="display:block;">SQL error: %s</p>\n', mysqli_error($con));
		}

		
		

	} else {
		echo '<p class="error" style="display:block;">'.$serverReply["responseMessage"].'</p>';
		$error=true;
	}
	
//Show debug info only for JD at EPL
if ($_SESSION['originating_ip']=='10.3.0.79'){	
	echo '<pre class="debug">';
			print_r($userInfo);
			echo "Customer:<br />";
			print_r($_SESSION['customer']);
			
		/*
			print_r($_POST);
			echo "<br />";
			print_r($libraryComData);
			echo "<br />";
			echo "serverReply:<br />";
			print_r($serverReply);
		*/	
	echo '</pre>';
}?>		

	
	
<div class="centered" style="width:90%;">	
	<?php
	if (isset($pinMessage)) {
		echo '<p style="text-align:center;">'.$pinMessage.'</p>';
	}
	?>
	<p style="margin-bottom:20px; margin-top:20px; text-align:center;">
	<?php
		if ($error != true) {
			if ($hasMembership) echo "Your record at ".$libraryComData["library_name"]." is now up to date";
			else  echo "You now have access to the ".$libraryComData["library_name"];
	?>
	.<br />Click the logo below to visit their website, or <a class="green" href="signup.php">join another library</a>.</p>
	
	<?php  } // End success message ?>
<a href="<?=$libraryComData['library_url']?>" style="border:none;height:160px;" class="centered"><img src="<?=$libraryComData["library_logo_url"]?>" class="centered" style="height:160px;vertical-align:middle;" alt="<?=$libraryComData["library_name"]?>" title="<?=$libraryComData["library_name"]?>"></a>	
</div>
<p style="text-align:center;margin-top:30px;">Note that it may take up to 5 minutes to update your account. If you are finished with this service, you can close your browser tab to end your session.</p>
<p style="text-align:center;font-weight:bold;">
<a href="signup.php">Join/Update more libraries</a>
<br /><br />
<a href="logout.php">Logout of ME Libraries</a>
</p>
</div><!--subContent-->
<div id="spacer"></div>
</div><!--mainContent-->
<?php
include 'footer.php';
?>