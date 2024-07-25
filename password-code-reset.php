<?php
session_start();
include('connect.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function send_mail($get_name, $get_email, $token)
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
        // $mail->Body = 'Hello ' . $get_name . ',<br><br>This is a test email sent from PHPMailer. Your reset token is: ' . $token;

        $mail->Body = "
     `<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Reset Your Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 10px 0;
            background-color: #007BFF;
            color: #ffffff;
        }
        .content {
            padding: 20px;
            text-align: center;
        }
        .button {
            background-color: #007BFF;
            color: white;
            padding: 15px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777777;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>Password Reset</h1>
        </div>
        <div class='content'>
            <p>Hello $get_name</p>
            <p style='font-size:14px; margin-bottom: 30px;'>You requested a password reset for your account. Click the button below to reset your password:</p>
            <a href='localhost/crud_design/password-change.php?t=$token' class='button'>Reset Password</a>
            <p style='font-size:14px; margin-top: 30px;''>If you did not request this, please ignore this email.</p>
            <p>Thank you.</p>
        </div>
        <div class='footer'>
            <p>If you have any questions, feel free to contact our support team.</p>
            <p>&copy; 2024. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

`
";
        // Send email
        $mail->send();
        echo 'Message has been sent';
        header("Location: " . $_SERVER['HTTP_REFERER'] . "");
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if (isset($_POST['password_reset_link'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $token = md5(rand());

    $check_email = "SELECT email, firstname FROM employee WHERE email = '$email' LIMIT 1";
    $check_email_run = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($check_email_run) > 0) {
        $row = mysqli_fetch_array($check_email_run);
        $get_name = $row['firstname'];
        $get_email = $row['email'];
        echo $get_email;


        $update_token = "UPDATE employee SET verify_token = '$token' WHERE email = '$get_email' LIMIT 1";
        $update_token_run = mysqli_query($conn, $update_token);

        if ($update_token_run) {
            send_mail($get_name, $get_email, $token);
            $_SESSION['status'] = "We e-mailed you a password reset link";
            header("Location: password-reset.php");
            exit(0);
        } else {
            $_SESSION['status'] = "Something went wrong. #1";
            header("Location: password-reset.php");
        }
    } else {
        $_SESSION['status'] = "No Email Found";
        header("Location: password-reset.php");
    }
}


if (isset($_POST['password_update'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $token = mysqli_real_escape_string($conn, $_POST['password_token']);
    if (!empty($token)) {
        if (!empty($email) && !empty($new_password) && !empty($token)) {
            // checking token is valid or not
            $check_token = "SELECT verify_token FROM employee WHERE verify_token='$token' LIMIT 1";
            $check_token_run = mysqli_query($conn, $check_token);
            if (mysqli_num_rows($check_token_run) > 0) {
                if ($new_password == $confirm_password) {
                    $update_password = "UPDATE employee SET password = '$new_password' WHERE verify_token='$token' LIMIT 1";
                    $update_password_run = mysqli_query($conn, $update_password);
                    if ($update_password_run) {
                        $_SESSION['status'] = "Password Successfully Updated";
                        header("Location: password-reset.php");
                        exit(0);
                    } else {
                        $_SESSION['status'] = "Did not update password. Something went wrong";
                        header("Location: password-reset.php");
                        exit(0);
                    }
                } else {
                    $_SESSION['status'] = "Password Does not mathch";
                    header("Location: password-reset.php");
                    exit(0);
                }
            }
        } else {
            $_SESSION['status'] = "All fields are mendtatory";
            header("Location: password-change.php?token=$token&email=$email");
            exit(0);
        }
    } else {
        $_SESSION['status'] = "No token available";
        header("Location: password-reset.php");
        exit(0);
    }
}


// $get_email = $_POST['email'];
// $get_name = $_POST['name'];
// send_mail($get_name, $get_email);
