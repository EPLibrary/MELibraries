<?php

echo '<h1> Email Test</h1>';
echo '<p>Comment out the exit statement to run this script.</p>';

exit;


include_once("../Mail.class.php");
$mail = new Mail();
$subject = "Test Email";
$body = "This is a test email from the ME Libraries system.";
$to_email = "jd.lien@epl.ca";
$to_name = "JD Lien";

$mail_sent = $mail->send($subject, $body, $to_email, $to_name);

if ($mail_sent) echo "Email sent successfully!";
else echo "Email failed to send: " . $mail->error_message;