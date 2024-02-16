<?php
session_start();
//Parse the inputted number and then we can lookup the prefix in the db.

// Create connection by including a line like
// $con=mysqli_connect("hostname","user","password","db");
include '../melibraries-db-config.php';

// Check connection
if (mysqli_connect_errno())  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

//Trim spaces from entered card number
$_POST['cardNoField'] = trim($_POST['cardNoField']);

//Set the number of digits used for the library prefix
if (strlen($_POST['cardNoField']) == 13) $numDigits=3;
else $numDigits=5;

//Exit if the user tries to pass something that's not a valid card number
//This is primarily to prevent SQL injection
if (!ctype_digit($_POST['cardNoField']) || strlen($_POST['cardNoField']) > 14) {
  $data["error"]=true;
  $_SESSION["error"]=true;
  $data["errorMsg"]="Invalid card number.";
  $_SESSION["errorMsg"]="Invalid card number.";
  $dataJSON=json_encode($data);
  //return the JSON
  echo $dataJSON;
  exit();
}

/* Delete old failures over 12 hours old as they aren't needed
$failQuery="DELETE FROM FailedLogins WHERE (CardNumber='".$_POST['cardNoField']."') AND lastAttemptTime < DATE_SUB(NOW(), INTERVAL 24 HOUR)";
$failResult = mysqli_query($con, $failQuery);
*/

/* Check the FailedLogins table to see if we've exceeded the threshold over the last hour */
$failQuery="SELECT SUM(Attempts) AS TotalAttempts FROM FailedLogins WHERE (CardNumber='".$_POST['cardNoField']."') AND lastAttemptTime > DATE_SUB(NOW(), INTERVAL 30 MINUTE)";
$failResult = mysqli_query($con, $failQuery);
$failuresArr = mysqli_fetch_assoc($failResult);
$failures=$failuresArr['TotalAttempts'];

//Lock the user out if they have too many login attempts
if ($failures > 5) {
  if ($failures < 20) {
    $failQuery="INSERT INTO FailedLogins (CardNumber, Attempts, LastIP, LastAttemptTime) VALUES('".$_POST["cardNoField"]."', 2, '".$_SERVER['REMOTE_ADDR']."', NOW())";
    $failResult = mysqli_query($con, $failQuery);
  }
  $data["error"]=true;
  $_SESSION['error']=true;
  $data["errorMsg"]="You have exceeded the maximum number of login attempts.<br />You may try again in 30 minutes.";
  $_SESSION['errorMsg']="You have exceeded the maximum number of login attempts.<br />You may try again in 30 minutes.";
  $dataJSON=json_encode($data);
  //return the JSON
  echo $dataJSON;
  exit();
}


/* Query for information about library where the prefix matches */
$query="SELECT * FROM library l
JOIN libraryprefixes lp ON l.record_index=lp.library_record_index
JOIN librarycom lc ON l.record_index=lc.library_record_index
WHERE lp.userid_prefix='".substr($_POST["cardNoField"], 0, $numDigits)."';";


$result = mysqli_query($con, $query);
if (mysqli_num_rows($result)>0) {

  while($row = mysqli_fetch_assoc($result)) {
    /* make an array of interesting variables to be stored in $_SESSION */
    $data=array(
      //"error" => false,
      //"errorMsg" => "",
      "cardNumber" => $_POST['cardNoField'],
      "pin" => $_POST['pin'],
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
  }

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
    $_SESSION['response']=$resultArr;

    $customerData=json_decode($resultArr['customer'], true);
    $_SESSION['customer']=$customerData;
    //echo $result;
    //We don't do this anymore because we don't want Customer information to be held in the user agent.
    //$data = array_merge_recursive($data, $resultArr);

    switch ($resultArr["code"]) {
      case "FAIL";
      case "ERROR";
      case "UNKNOWN";
      case "UNAVAILABLE";
      case "UNAUTHORIZED";
      case "USER_PIN_INVALID";
        $data["error"]=true;
        $_SESSION['error']=true;
        $data["errorMsg"]=$resultArr["responseMessage"];
        $_SESSION['errorMsg']=$resultArr["responseMessage"];

        //Log the unsuccessful login attempt
        $query="INSERT INTO FailedLogins (CardNumber, Attempts, LastIP, LastAttemptTime) VALUES('".$_POST["cardNoField"]."', 1, '".$_SERVER['REMOTE_ADDR']."', NOW())";

        if (mysqli_connect_errno($con))  {
          echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        $result=mysqli_query($con,$query);
        if ( false===$result ) {
          printf("error: %s\n", mysqli_error($con));
        }
        break;
    }

    $_SESSION['libraryData']=$data;
    $dataJSON=json_encode($data);
    //This is the entire output of this .php if successful.
    echo $dataJSON;


/* If we can't match the card number to a library, return an error to that effect. */
} else {
  $data = array("error" => true, "errorMsg" => "Unknown card number");
  $_SESSION['error']=true;
  $_SESSION['errorMsg']='Unknown card number.';
  $_SESSION['libraryData']=$data;
  $dataJSON=json_encode($data);
  echo $dataJSON;
}

?>