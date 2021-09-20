<?php

ini_set('display_errors','on');
error_reporting(E_ALL);

// Testing email functionality







$from = "Me Libaries <noreply@melibraries.ca>";
$to = "jd@jdlien.com";
$subject = 'You have joined';
$body = "You now have access to its collections with your home library card number!\n";

$host = "smtp.epl.ca";

$headers = array (
	'From' => $from,
	'To' => $to,
	'Subject' => $subject);


// See if I can send mail the simple, old fashioned way
// mail($to,$subject,$body);

$smtpInfo["host"] = $host;
$smtpInfo["port"] = "25";
$smtpInfo["auth"] = false;
	
require_once "Mail.php";
$smtp = (new Mail)->factory('smtp', $smtpInfo);
// $smtp = Mail::factory('smtp', $smtpInfo);

$mail = $smtp->send($to, $headers, $body);

if (PEAR::isError($mail)) {
  echo("<p>" . $mail->getMessage() . "</p>");
} else {
  echo('<p>You have been sent an email about the following:</p>');
}



?>

