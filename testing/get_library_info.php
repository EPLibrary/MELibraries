<?php
session_start(); 
/* get_library_info.php
 * Contains function get_library_info(barcode, pin, libraryid)
 * Returns a data structure containing information about a customer.
 *
 * This is primarily to determine whether a user exists at that library at all
 * 
 */


function get_library_info($barcode, $pin = null, $libraryid = null) {

	if (is_null($pin)) {
		$data["error"]=true;
		// $_SESSION["error"]=true;
		$data["errorMsg"]="No pin specified.";
		// $_SESSION["errorMsg"]="Invalid card number.";
		// $dataJSON=json_encode($data);
		//return the JSON
		return $data;
		exit();
	}

	// Create connection by including a line like
	// $con=mysqli_connect("hostname","user","password","db");
	include '/home/its/mysql_config.php';

	// Check connection
	if (mysqli_connect_errno($con))  {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	//Trim spaces from entered card number
	$barcode = trim($barcode);

	//Set the number of digits used for the library prefix
	if (strlen($barcode) == 13) $numDigits=3;
	else $numDigits=5;

	//Exit if the user tries to pass something that's not a valid card number
	//This is primarily to prevent SQL injection
	if (!ctype_digit($barcode) || strlen($barcode) > 14) {
		$data["error"]=true;
		// $_SESSION["error"]=true;
		$data["errorMsg"]="Invalid card number.";
		// $_SESSION["errorMsg"]="Invalid card number.";
		// $dataJSON=json_encode($data);
		//return the JSON
		return $data;
		exit();
	}

	/* If the libraryid is defined, we get that library's information */
	if (!is_null($libraryid)) {
		$query="SELECT * FROM library l 
		JOIN librarycom lc ON l.record_index=lc.library_record_index
		WHERE l.record_index=$libraryid;";	
	} else {
		// We will check home library, but first check if we should use a security lockout
		// Check the FailedLogins table to see if we've exceeded the threshold over the last hour
		$failQuery="SELECT SUM(Attempts) AS TotalAttempts FROM FailedLogins WHERE (CardNumber='".$barcode."') AND lastAttemptTime > DATE_SUB(NOW(), INTERVAL 30 MINUTE)";
		$failResult = mysqli_query($con, $failQuery);
		$failuresArr = mysqli_fetch_assoc($failResult);
		$failures=$failuresArr['TotalAttempts'];

		//Don't bother checking and kick the user out if they have too many login attempts.
		//We count excessive attempts as twice as many attempts. Not sure why, but okay.
		if ($failures > 5) {
			if ($failures < 20) {
				$failQuery="INSERT INTO FailedLogins (CardNumber, Attempts, LastIP, LastAttemptTime) VALUES('".$barcode."', 2, '".$_SERVER['REMOTE_ADDR']."', NOW())";
				$failResult = mysqli_query($con, $failQuery);
			}
			$data["error"]=true;
			// $_SESSION['error']=true;
			$data["errorMsg"]="You have exceeded the maximum number of login attempts.<br />You may try again in 30 minutes.";
			// $_SESSION['errorMsg']="You have exceeded the maximum number of login attempts.<br />You may try again in 30 minutes.";
			// $dataJSON=json_encode($data);
			//return the JSON
			return $data;
			exit();
		}

		/* If there's no libraryid, we use the card prefix to determine the home library and query that info */
		$query="SELECT * FROM library l 
		JOIN libraryprefixes lp ON l.record_index=lp.library_record_index
		JOIN librarycom lc ON l.record_index=lc.library_record_index
		WHERE lp.userid_prefix='".substr($barcode, 0, $numDigits)."';";
	}

	$result = mysqli_query($con, $query);
	if (mysqli_num_rows($result)>0) {
		$data = array();
		$data["cardNumber"] = $barcode;
		$data["pin"] = $pin;
		while($row = mysqli_fetch_assoc($result)) {
			/* make an array of interesting variables to be stored in $_SESSION */
			$data["libraryData"]=array(
				//"error" => false,
				//"errorMsg" => "",
				// card and pin moved into root
				// "cardNumber" => $barcode,
				// "pin" => $pin,
				"libraryName" => $row['library_name'],
				"libraryAddress" => $row['library_address'],
				"libraryProvince" => $row['library_province'],
				"libraryPostalCode" => $row['library_postal_code'],
				"libraryPhoneNumber" => $row['library_phone_number'],
				"libraryEmailAddress" => $row['library_email_address'],
				"libraryRecordIndex" => $row['library_record_index'],
				/* This information doesn't need to be passed to the user
				"libraryServerUrl" => $row['library_server_url'],
				"libraryServerPort" => $row['library_server_port'],
				*/
			);
			$host=$row['library_server_url'];
			$port=$row['library_server_port'];
		}//end query loop

		/*	I can do the socket connection here and if it's all good, return the appropriate data. */
		/* PHP Socket Client */

		
		//Token/API Key
		$authorityToken="55u1dqzu4tfSk2V4u5PW6VTMqi9bzt2d";
		
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
		/* If GET_STATUS is not okay, do error handling */
		$message=json_encode($message);
		
		//Add newline so the server knows when the message is done.
		$message.="\n";

		// 10s Timeout. Does this do anything?
		set_time_limit(10);
		

		// Create Socket
		$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
		//Set timeout to 10 seconds.
		//stream_set_blocking($socket, TRUE );
		//stream_set_timeout($socket, 10);

		// Connect to the server
		if ($result = socket_connect($socket, $host, $port) == false) {
			$data["error"]=true;
			$data["errorMsg"]="Can't connect to server $host";
			$data=json_encode($data);
			die ($data);
		};

		// Read initial server message/ack
		if ($result = socket_read($socket, $bufferlen) == false) {
			$data["error"]=true;
			$data["errorMsg"]="Can't read from server $host";
			$data=json_encode($data);
			die ($data);
		};

		$message=array(
			"code" => "GET_CUSTOMER",
			"authorityToken" => $authorityToken,
			"userId" => $port = $data["cardNumber"],
			"pin" => $data["pin"],
			"customer" => "null"
		);
		$message=json_encode($message);
		$message.="\n";

		//Sending Message: $message
		$result = (socket_write($socket, $message, strlen($message)));
		if ($result == false) {
			$data["error"]=true;
			$data["errorMsg"]="Could not send data to server $host";
			$data=json_encode($data);		
			die($data);
		}
		
		$result = socket_read ($socket, $bufferlen);
		if ($result == false) {
			$data["error"]=true;
			$data["errorMsg"]="Could not read server response";
			$data=json_encode($data);
			die($data);
		}
		//echo "<p><b>Reply From Server:</b><br />".$result."</p>";

		// Hang up socket connection
		socket_write($socket, $hangup, strlen($hangup)) or die("Could not send data to server\n");

		//Close the socket
		socket_close($socket);



		/* Do error handling here: no connection, invalid credentials, etc
		OK, SUCCESS, FAIL, ERROR, UNKNOWN, BUSY, UNAVAILABLE */

			/* Merge new JSON to the data I already have so I still have the library info. */
			$resultArr=json_decode($result, true);
			
			//Store customer information from ME server in Session variable
			// $_SESSION['response']=$resultArr;
			$data['response']=$resultArr;
			
			$customerData=json_decode($resultArr['customer'], true);
			// $_SESSION['customer']=$customerData;
			$data['customer']=$customerData;
			//echo $result;
			//We don't do this anymore because we don't want Customer information to be held in the user agent.
			//$data = array_merge_recursive($data, $resultArr);
			
			switch ($resultArr["code"]) {
				case "FAIL";
				case "ERROR";
				case "UNKNOWN";
				case "UNAVAILABLE";
				case "UNAUTHORIZED";
					$data["error"]=true;
					// $_SESSION['error']=true;
					$data["errorMsg"]=$resultArr["responseMessage"];
					// $_SESSION['errorMsg']=$resultArr["responseMessage"];
					
					//Log the unsuccessful login attempt if this is the home library
					if (is_null($libraryid)) {
						$query="INSERT INTO FailedLogins (CardNumber, Attempts, LastIP, LastAttemptTime) VALUES('".$barcode."', 1, '".$_SERVER['REMOTE_ADDR']."', NOW())";
						
						if (mysqli_connect_errno($con))  {
							echo "Failed to connect to MySQL: " . mysqli_connect_error();
						}
						$result=mysqli_query($con,$query);
						if ( false===$result ) {
							printf("error: %s\n", mysqli_error($con));
						}
					}
					break;
			}
			
			// $_SESSION['libraryData']=$data;
			// $dataJSON=json_encode($data);
			//This is the entire output of this .php if successful.
			return $data;
		
		
	/* If we can't match the card number to a library, return an error to that effect. */
	} else {
		$data = array("error" => true, "errorMsg" => "Unknown card number");
		// $_SESSION['error']=true;
		// $_SESSION['errorMsg']='Unknown card number.';
		// $_SESSION['libraryData']=$data;
		// $dataJSON=json_encode($data);
		return $data;
	}
}//end get_library_info()



//Trim spaces from entered card number
$_POST['cardNoField'] = trim($_POST['cardNoField']);


$data = get_library_info($_POST['cardNoField'], $_POST['pin']);


// print_r($data);

// if it worked (dataObj.error == false)
// - set session variables
// - get the membership branches, then loop the following through those.

// print_r($data);

if (isset($data['error']) && ($data['error'] == true || $data['error'] == 1)) {
	$_SESSION["error"]=$data['error'];
	$_SESSION["errorMsg"]=$data['errorMsg'];	
	// echo json_encode($data);

} else {
	$_SESSION['response']=$data['response'];
	$_SESSION['customer']=$data['customer'];
	$_SESSION["libraryData"]=array(
		"libraryName" => $data['libraryData']['libraryName'],
		"libraryAddress" => $data['libraryData']['libraryAddress'],
		"libraryProvince" => $data['libraryData']['libraryProvince'],
		"libraryPostalCode" => $data['libraryData']['libraryPostalCode'],
		"libraryPhoneNumber" => $data['libraryData']['libraryPhoneNumber'],
		"libraryEmailAddress" => $data['libraryData']['libraryEmailAddress'],
		"libraryRecordIndex" => $data['libraryData']['libraryRecordIndex'],
	);
	
	// Now we have the session data set, we can query and loop through existing memberships.
	include '/home/its/mysql_config.php';

	$query = "SELECT * FROM membership WHERE userid='".$_POST['cardNoField']."' ORDER BY library_record_index";

	$result = mysqli_query($con, $query);
	if (mysqli_num_rows($result)>0) {
		while($row = mysqli_fetch_assoc($result)) {
			$invalid = false;
			$patronData = get_library_info($_POST['cardNoField'], $_POST['pin'], $row['library_record_index']);
			if (in_array('error', $patronData)) {
				if ($patronData['error'] == 1) {$invalid = true;}

			} else {
				$code = $patronData['response']['code'];
				if ($code == "FAIL" || $code == "UNAUTHORIZED") {$invalid = true;}
			}

			//Now check to see what code we have. If we have certain codes (FAIL), we will run a query to delete this user's membership
			//Delete record from membership table
			if ($invalid) {
				$delQuery = "DELETE FROM membership WHERE record_index = ".$row['record_index']." AND userid = '".$_POST['cardNoField']."'";
				echo $delQuery.'<br /><br />';
			}


		}//end while
	}//end if

}


echo json_encode($data);



?>