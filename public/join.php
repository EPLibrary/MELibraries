<?php
session_start();

$pageTitle="ME Libraries | Join";
include 'header.php';

/*
  - Checks if user is already a member of this library.
  - If a member, set a variable that will change my request to Update
  - If the create/update was succesfull (ok response), insert/update the database hash
*/
?>

<div class="mainContent" id="mainContent">
<a href="index.php" style="border:none;" title="Return to Login"><img id="meLogoTop" src="images/ME_Libraries_Logo_black.png"/></a>
<h1 class="pageTitle greenbg">Joining &amp; Updating</h1>

<div class="subContent">
<?php
/* Connect to the DB and get the list of libraries that the user is not already registered to
or where the hash is different (for updating) */
include '../melibraries-db-config.php';

// Check connection
if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

$query = "SELECT * FROM librarycom  lc
JOIN library l ON l.record_index=lc.library_record_index
WHERE lc.library_record_index='".$_POST["joinLibrary"]."'";
//I will also need to filter out other libraries that the user has already joined

if ($result=mysqli_query($con, $query)) $libraryComData = mysqli_fetch_assoc($result);
else echo "<h2>No communication info for library<h2>";

//Check to see if the user is already a member here. If so, we do an update.
//2014-06-17 - now we also check for matching hashes in case we have a lost card.
$query = "SELECT
  u.record_index,
  m.userid AS muserid,
  m.user_info_hash,
  m.record_index AS member_record_index,
  u.userid,
  u.home_library_record_index,
  m.user_record_index,
  m.library_record_index
  FROM user u
  INNER JOIN membership m
  ON u.record_index = m.user_record_index
  WHERE u.userid='".$_SESSION["customer"]["ID"]."' AND m.library_record_index=".$_POST["joinLibrary"]."
  OR (m.user_info_hash='".$_SESSION["customerHash"]."' AND m.library_record_index=".$_POST["joinLibrary"].")";

$userExists = false;
$hasMembership = false;
$hasLostCard = false;
$prevCard = '';
// If it's a lost card, we need to determine the original userid so we can update that record.

// Check to see if this user has a matching hash but no matching userid. If so, we most likely have a lost card.
// Where's the userid for this libraryrecord?

$result = mysqli_query($con, $query);
if ($result->num_rows > 0) {
    $userInfo = mysqli_fetch_assoc($result);
    $hasMembership = true;
    $userExists = true;
    // If the currently logged-in userid doesn't match the one on record for this membership, card is lost
    // We also set the old card number to a variable so we can handle the updates appropriately.
    if ($userInfo["muserid"] != $_SESSION["customer"]["ID"]) {
      $hasLostCard = true;
      $prevCard = $userInfo["muserid"];
      $_SESSION["customer"]["ISLOSTCARD"] = 'Y';
      $_SESSION["customer"]["ALTERNATE_ID"] = $prevCard;
    }
} else {
  // Now check that the user exists at all
  // I don't think I need to do anything special here for lost card.
  $query="select * from user WHERE userid='".$_SESSION["customer"]["ID"]."'";
  $result=mysqli_query($con, $query);
  if ($result->num_rows > 0) {
    $userInfo = mysqli_fetch_assoc($result);
    $userExists = true;
  }
}

/* Do Socket connection and add user to foreign library 	*/
  // Token/API Key
  $authorityToken = "55u1dqzu4tfSk2V4u5PW6VTMqi9bzt2d";
  $host = $libraryComData["library_server_url"];
  $port = $libraryComData["library_server_port"];



  // Hangup string we send when we're done
  $hangup = "XX0";
  // Buffer length in bytes
  $bufferlen = 2048;

  // Default error variable to false
  $error = false;

  // JSON formatted parameters for socket with newline to terminate
  $message = [
      "code" => "GET_STATUS",
      "authorityToken" => $authorityToken,
      "customer" => "null"
  ];

  // If GET_STATUS is not okay, do error handling
  $message = json_encode($message);
  //Add newline so the server knows when the message is done.
  $message .= "\n";

  // 10s Timeout
  set_time_limit(10);

  // Create Socket
  $socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");

  // Connect to the server
  if ($result = socket_connect($socket, $host, $port) == false) {
    $error = true;
    $errorMsg = "Can't connect to server $host on port $port";
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
    $message = ["code" => "UPDATE_CUSTOMER", "authorityToken" => "55u1dqzu4tfSk2V4u5PW6VTMqi9bzt2d", "userId" => '', "pin" => '', "customer" => json_encode($_SESSION["customer"])];
  } else {
    $message = ["code" => "CREATE_CUSTOMER", "authorityToken" => "55u1dqzu4tfSk2V4u5PW6VTMqi9bzt2d", "userId" => '', "pin" => '', "customer" => json_encode($_SESSION["customer"])];
  }

  $message = json_encode($message);
  $message .= "\n";

  if ($_SESSION['originating_ip'] == '10.3.0.79'){
    echo '<pre class="debug">';
    echo 'Sending Message:';
    print_r($message);
    echo '</pre>';
  }

  $result = (socket_write($socket, $message, strlen($message)));
  if ($result == false) {
    $error = true;
    $errorMsg = "Could not send data to server $host";
    echo('<div class="mainContent" id="mainContent" style="min-width:695px;"><a href="index.php" style="border:none;"><img id="meLogoTop" src="images/ME_Libraries_Logo_black.png"></a><h1 class="pageTitle">Error</h1><div class="subContent"><p class="error" style="display:inline;">'.$errorMsg.'</p><p>Please return to <a href="/">MeLibraries.ca</a>.</p></div></div>');
    include 'footer.php';
    exit();
  }

  $serverReply = socket_read ($socket, $bufferlen);
  if ($serverReply == false) {
    $data["error"] = true;
    $data["errorMsg"] = "Could not read server response";
    $data=json_encode($data);
    die($data);
  }

  echo '<p class="debug"><b>Reply From Server:</b><br />'.$serverReply.'</p>';
  $serverReply = json_decode($serverReply, true);

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

    $operation = "U";

    //Insert the new user if he doesn't exist yet
    if ($userExists == false) {
      $query="INSERT INTO user (userid, home_library_record_index, date_last_activity, date_created)
      VALUES('".$_SESSION["customer"]["ID"]."','".$_SESSION["libraryData"]["libraryRecordIndex"]."', NOW(), NOW())";
      $result = mysqli_query($con,$query);
      if ( false === $result ) {
        printf('<p class="SQL error" style="display:block;">error: %s</p>\n', mysqli_error($con));
      }

      $user_record_index=mysqli_insert_id($con);
    } else {
      $user_record_index=$userInfo["record_index"];
      //If the user exists but has a lost card, we will update his/her record
      if ($hasLostCard) {
        $query="UPDATE user SET userid='".$_SESSION["customer"]["ID"]."',
        lost_card='".$prevCard."',
        date_last_activity=NOW()
        WHERE record_index=".$user_record_index;
        $result = mysqli_query($con,$query);
        if ( false === $result ) {
          printf('<p class="SQL error" style="display:block;">error: %s</p>\n', mysqli_error($con));
        }
      }
    }

    if ($hasMembership == false) {
      $operation = "C";
      $query="INSERT INTO membership (user_record_index, library_record_index, date_last_activity, user_info_hash, userid)
      VALUES('".$user_record_index."','".$_POST["joinLibrary"]."', NOW(), '".$_SESSION["customerHash"]."', '".$_SESSION["customer"]["ID"]."')";
    }
    else {
      //else we update an existing record
      $query="UPDATE membership SET date_last_activity=NOW(), user_info_hash='".$_SESSION["customerHash"]."', userid='".$_SESSION["customer"]["ID"]."' WHERE record_index='".$userInfo["member_record_index"]."'";
    }
    //Execute the insert or update query.
    $result = mysqli_query($con,$query);
    if ( false===$result ) {
      printf('<p class="error" style="display:block;">SQL error: %s</p>\n', mysqli_error($con));
    }


    // Insert record into membership_log
    $query = "INSERT INTO membership_log (user_record_index, userid, home_library_record_index, remote_library_record_index, operation, activity_datetime)
    VALUES(".$user_record_index.", '".$_SESSION["customer"]["ID"]."', ".$_SESSION["libraryData"]["libraryRecordIndex"].", ".$_POST["joinLibrary"].", '".$operation."', NOW());";
    $result = mysqli_query($con,$query);
    if ( false===$result ) {
      printf('<p class="error" style="display:block;">SQL error: %s</p>\n', mysqli_error($con));
    }


  } elseif ($serverReply["code"] == "PIN_CHANGE_REQUIRED") {

    $newPin = preg_replace("/^ ?(\d+)[\a\s:](.*)/", '<span class="pin">$1</span><span class="libMessage">$2</span>', (string) $serverReply["responseMessage"]);
    $newNakedPin = preg_replace("/^ ?(\d+)[\a\s:](.*)/", '$1', (string) $serverReply["responseMessage"]);
    echo '<h2 class="green" style="clear:both;">';

    echo 'Welcome to the '.$libraryComData["library_name"].'.</h2>';
    $pinMessage = '<span style=\"color:red;\">Note:</span> your PIN for this library is different.<br />';
    $pinMessage.= 'Your pin for '.$libraryComData["library_name"].' has been set to: '.$newPin;


    //Send an email to the customer if they have a valid email address
    if (strlen((string) $_SESSION["customer"]["EMAIL"]) > 5) {
      $from = "Me Libaries <noreply@melibraries.ca>";
      $to_email = $_SESSION["customer"]["EMAIL"];
      $to_name = $_SESSION["customer"]["FIRSTNAME"]." ".$_SESSION["customer"]["LASTNAME"];
      $subject = 'You have joined '.$libraryComData["library_name"];
      $body = "This is a friendly notice that the you now have joined ".$libraryComData["library_name"];
      $body .= " and now have access to its collections with your home library card number!\n";
      $body .= "Visit ".$libraryComData["library_name"]." at ".$libraryComData["library_url"];

      //Add a comment about the new PIN if it has been changed
      if ($serverReply["code"] == "PIN_CHANGE_REQUIRED") {
        //$newPin is set above
        $body .= "\n\nNote: Your PIN for ".$libraryComData["library_name"]." is different.\nIt has been set to ".$newNakedPin.".";
      }

      try {
        include_once("../Mail.class.php");
        $mail = new Mail();
        $mail_sent = $mail->send($subject, $body, $to_email, $to_name);
        $mail_error = $mail->error_message;
      } catch (Exception $e) {
        $mail_sent = false;
        $mail_error = $e->getMessage();
      }

      if (!$mail_sent) {
        echo("<p>$mail_error</p>");
      } else {
        echo('<p>You have been sent an email about the following:</p>');
      }
    }//END - If email longer than 5 characters


    //Do database inserts for when PIN Changes is required - new user
    if ($userExists == false) {
      $query="INSERT INTO user (userid, home_library_record_index, date_last_activity, date_created)
      VALUES('".$_SESSION["customer"]["ID"]."','".$_SESSION["libraryData"]["libraryRecordIndex"]."', NOW(), NOW())";
      $result = mysqli_query($con,$query);
      if ( false===$result ) {
        printf('<p class="SQL error" style="display:block;">error: %s</p>\n', mysqli_error($con));
      }

      $user_record_index=mysqli_insert_id($con);
    } else $user_record_index = $userInfo["record_index"];

    if ($hasMembership == false) {
      $query="INSERT INTO membership (user_record_index, library_record_index, date_last_activity, user_info_hash, userid)
      VALUES('".$user_record_index."','".$_POST["joinLibrary"]."', NOW(), '".$_SESSION["customerHash"]."', '".$_SESSION["customer"]["ID"]."')";
    } else {
      //else we update an existing record
      $query = "UPDATE membership SET date_last_activity=NOW(), user_info_hash='".$_SESSION["customerHash"]."', userid='".$_SESSION["customer"]["ID"]."' WHERE record_index='".$userInfo["member_record_index"]."'";
    }

    //Execute the insert or update query.
    $result = mysqli_query($con,$query);
    if (false === $result) {
      printf('<p class="error" style="display:block;">SQL error: %s</p>\n', mysqli_error($con));
    }
  } else {
    echo '<p class="error" style="display:block;">'.$serverReply["responseMessage"].'</p>';
    $error = true;
  }
?>

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
      else  echo "You now have access to the ".$libraryComData["library_name"].".";
  ?>
  <br />Click the logo below to visit their website, or <a class="green" href="signup.php">join another library</a>.</p>

  <?php  } // End success message ?>
<a href="<?=$libraryComData['library_url']?>" style="border:none;height:160px;max-width:300px;text-align:center;" class="centered"><img src="<?=$libraryComData["library_logo_url"]?>" class="centered" style="height:160px;vertical-align:middle;" alt="<?=$libraryComData["library_name"]?>" title="<?=$libraryComData["library_name"]?>"></a>
</div>
<p style="text-align:center;margin-top:30px;">Note that it may take up to 5 minutes to update your account. If you are finished with this service, you can close your browser tab to end your session.</p>
<p style="text-align:center;font-weight:bold;">
<a href="signup.php">Join/Update more libraries</a>
<br /><br />
<a href="logout.php">Log Out of ME Libraries</a>
</p>
</div><!--subContent-->
<div id="spacer"></div>
</div><!--mainContent-->
<?php include 'footer.php';
