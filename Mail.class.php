<?php

use PHPMailer\PHPMailer\PHPMailer;

include_once(__PATH__ . "/vendor/phpmailer/phpmailer/src/PHPMailer.php");

/**
 * Mail Class
 * Utility class for sending emails via PHPMailer and EPL SMTP
 */
class Mail {

    private $host = "smtp.epl.ca";
    private $port = 25;
    private $useTLS = false;
    private $from_email = "noreply@melibraries.ca";
    private $from_name = "Me Libraries";
    private $replyto_email = $this->from_email;
    private $replyto_name = $this->from_name;

    /**
     * Send Email
     * 
     * @param string $subject   The email's subject.
     * @param string $body      The email's HTML content. 
     * @param string $to_email  The recipient's email address. 
     * @param string $to_name   The recipient's name.
     * 
     * @return bool Whether the email has been sent successfully.
     */
    public function send($subject, $body, $to_email, $to_name) {
        
        // initialize PHPMailer
        $mail = new PHPMailer();

        // configure mail server settings
        $mail->isSMTP();
        $mail->Host = $this->host;
        $mail->SMTPAutoTLS = $this->useTLS;
        $mail->Port = $this->port;

        // configure mail recipients
        $mail->setFrom($this->from_email, $this->from_name);
        $mail->setReplyTo($this->replyto_email, $this->replyto_name);
        $mail->setAddress($to_email, $to_name);

        // configure mail content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        // send the email
        $sent = $mail->send();

        // handle errors
        if (!$sent) {
            error_log("PHPMailer Error: {$mail->ErrorInfo}");
            return false;
        }

        return true;
    }

}