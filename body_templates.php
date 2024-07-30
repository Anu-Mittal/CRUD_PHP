<!-- forget password mail template`    -->
<!DOCTYPE html>
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
            <p>Hello $name</p>
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
</html>`


<!-- change password template -->
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Password Changed Successfully</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 50px auto; background-color: #ffffff; padding: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        .header { text-align: center; padding: 10px 0; background-color: #007BFF; color: #ffffff; }
        .content { padding: 20px; text-align: center; }
        .button { background-color: #007BFF; color: white; padding: 15px 25px; text-decoration: none; border-radius: 5px; font-size: 16px; }
        .footer { text-align: center; font-size: 12px; color: #777777; padding: 20px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>Password Changed Successfully</h1>
        </div>
        <div class='content'>
            <p>Hello {$row['name']},</p>
            <p style='font-size:14px; margin-bottom: 30px;'>Your password has been successfully reset. If you did not perform this action, please contact our support team immediately.</p>
        </div>
        <div class='footer'>
            <p>If you did not request a password reset, please ignore this email or contact support.</p>
        </div>
    </div>
</body>
</html>

<!-- sign-up mail  -->
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 600px;
        }
        .header {
            background-color: #5A7C5A;
            color: #ffffff;
            padding: 10px 0;
            text-align: center;
        }
        .content {
            padding: 20px;
            text-align: left;
        }
        .footer {
            background-color: #f4f4f4;
            color: #888888;
            padding: 10px 0;
            text-align: center;
            font-size: 12px;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            margin: 10px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Our Website!</h1>
        </div>
        <div class="content">
            <p>Hello ' . $firstname . ',</p>
            <p>Thank you for registering on our website.</p>
            <p>If you have any questions, feel free to reach out to our support team.</p>
        </div>
        <div class="footer">
            <p>&copy; ' . date("Y") . ' Your Website. All rights reserved.</p>
        </div>
    </div>
</body>
</html>