<?php
session_start();
include('connect.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendSignupMail($get_name, $get_email)
{
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->SMTPSecure = "ssl";
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = '465';
        $mail->Username = 'anumittal4321@gmail.com'; // SMTP account username
        $mail->Password = 'ihhp hhxn myeq helu';
        $mail->SMTPKeepAlive = true;
        $mail->Mailer = "smtp";
        $mail->IsSMTP(); // telling the class to use SMTP  
        $mail->SMTPAuth = true; // enable SMTP authentication  
        $mail->CharSet = 'utf-8';
        $mail->SMTPDebug = 0;

        // Recipients
        $mail->setFrom('anumittal4321@gmail.com', 'Anu Mittal');
        $mail->addAddress($get_email, $get_name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = 'Hello ' . $get_name . ',<br><br>Welcome: You have successfully completed the registation on our website. ';
        // Send email
        $mail->send();
        // echo 'Message has been sent';
        // header("Location: " . $_SERVER['HTTP_REFERER'] . "");
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// if (isset($_POST['submit'])) {

   
//     $email = mysqli_real_escape_string($con, $_POST['email']);
    
//     $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
    
//     sendSignupMail($firstname,$email);
    
          
//     }