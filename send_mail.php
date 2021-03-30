<?php
/*SMTP Configuration for account Begin*/
$host=''; //smtp.gmail.com for gmail
$email='';
$password='';
$secure='ssl';   //tls or ssl
$port=465;   //465 for ssl and 587 for tls
/*SMTP Configuration for account End*/
$data=$_POST['base_img'];
$data = str_replace('data:image/png;base64,', '', $data);
$data = str_replace(' ', '+', $data);
$data = base64_decode($data);
$file_name=mktime(). '.png';
$file = 'upload/'.mktime() . '.png';
$success = file_put_contents($file, $data);
$data = base64_decode($data); 
$source_img = imagecreatefromstring($data);
$rotated_img = imagerotate($source_img, 90, 0); 
$file = 'upload/'. mktime(). '.png';
$imageSave = imagejpeg($rotated_img, $file, 10);
imagedestroy($source_img);


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'new_mailer/vendor/autoload.php';
$mail = new PHPMailer(true);
   // $mail->SMTPDebug = 2;                                       // Enable verbose debug output
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       = $host;  // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = $email;                     // SMTP username
    $mail->Password   = $password;                               // SMTP password
    $mail->SMTPSecure = $secure;                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = $port;                                    // TCP port to connect to
    $mail->SMTPOptions = array(                                 //Configuration for new versions of php
        'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
        )
    ); 
    $mail->setFrom($email, 'CryptoMailer');
    $mail->addAddress($_POST['email']);
    $mail->isHTML(true); 
    $mail->Subject = 'Currency History';
    $mail->Body = "Dear,<br> Please find the attachment.<br>";
    $mail->addAttachment($file);
    $res=$mail->send();
?>