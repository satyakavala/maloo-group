<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
   // $mail->SMTPDebug = 2;                                       // Enable verbose debug output
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       = 'mail.nspira.in';  // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'suresh.sandanaboina@nspira.in';                     // SMTP username
    $mail->Password   = 'suresh@123';                               // SMTP password
    $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('suresh.sandanaboina@nspira.in', 'Suresh');
    $mail->addAddress('venkatasatyanarayana.kavala@nspira.in', 'Satya kavala');     // Add a recipient
    //$mail->addAddress('ellen@example.com');               // Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    // Attachments
   // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = "Hi,Your Login details are:\r\n<br>URL: <a href='https://branding.narayanagroup.com/login.php'>https://branding.narayanagroup.com/login.php</a>\r\n<br>User Name: vvsnarayana.kavala@gmail.com\r\n Password:satya\r\n<br>Thank you";
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if($mail->send())
	{
    echo 'Message has been sent';
	}
	else
	{
		 echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>