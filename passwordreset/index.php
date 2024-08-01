<?php
include '../connect.php';
include '../email-service.php';
session_start();
// if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true) {
//   header('Location:../login');
//   exit;
// }
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    header('Location:../dashboard');
    exit;
}

$_SESSION['status'] = "";


$email = '';

if (isset($_POST['submit'])) {
    $errors = [];


    if (empty($_POST['email'])) {
        $errors['email'] = false;
        $_SESSION['status'] = "Email is required.";
    } else {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = false;
            $_SESSION['status'] = "Invalid Email Format.";
            // echo $_SESSION['status'];
        } else {
            $sql_email = "SELECT * FROM `em_users` WHERE user_email='$email'";
            $result = mysqli_query($con, $sql_email);
            $row1 = mysqli_fetch_assoc($result);
            $id = $row1['user_id'];

            if (empty($row1)) {
                $errors['email'] = false;
                $_SESSION['status'] = "Email not registered.";
            }
        }
    }


    if (empty($errors)) {

        $token = uniqid();
        $expiry_token = time();
        $sql1 = "UPDATE em_users SET user_token = '$token', user_expiry_token = '$expiry_token' WHERE user_email = '$email'";
        $result1 = mysqli_query($con, $sql1);


        if ($result1) {
            // echo var_dump($row1);
            // die();
            $subject = "Password Change Request";
            $name = $row1['user_first_name'];

            $sql = "select * from em_templates where template_name='forget_password'";
            $result = mysqli_query($con, $sql);

            $row = mysqli_fetch_assoc($result);
            $body = $row['template_body'];

            $body = str_replace("{{name}}", $name, $body);
            $link = "localhost/crud_design/passwordchange?t=$token&uid=$id";

            $body = str_replace("{{url}}", $link, $body);



            sendEmail($row1['user_first_name'], $email, $subject, $body, $id);

            $_SESSION['status'] = "Password successfully sent to your e-mail.";
            // header("location:../login");
        } else {
            die(mysqli_error($con));
        }
    }
}


// echo var_dump($errors);
?>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin</title>
    <link href="../css/dashboard.css" rel="stylesheet">
    <style>
        .error {
            color: red;
            font-size: 12px;
            padding: 10px;
        }
    </style>
</head>

<body>
    <div class="login_section">
        <div class="wrapper relative">

            <div class="heading-top">
                <div class="logo-cebter"><a href="#"><img src="../images/at your service_banner.png"></a></div>
            </div>
            <div class="box">
                <div class="outer_div">


                    <h2>Reset<span> Password </span></h2>
                    <?php if (!empty($_SESSION['status'])) : ?>
                        <div class="error-message-div error-msg" style="margin-bottom:10px;"><?php echo $_SESSION['status'];
                                                                                                unset($_SESSION['status']); ?></div>
                    <?php endif; ?>
                    <!-- <div class="error-message-div error-msg" id="msg" style="display:none;"></div> -->

                    <form name="resetForm" id="myform" class="margin_bottom" role="form" method="POST" onsubmit="return validateReset()" novalidate>
                        <div class="form-group" style="padding-top: 16px;margin-bottom: 15px;">
                            <label for="email" style="padding-top: 10px;">Email <span>*</span></label>
                            <input type="email" class="form-control" name="email" placeholder="Enter Email." />
                            <p id='email-error' class='error'><?php echo (isset($errors['email'])) ? $errors['email'] : ''; ?></p>
                        </div>
                        <!-- <button style="" type="submit" name="password_reset_link">Send Recover mail</button> -->
                        <button type="submit" name="submit" class="btn_login">Submit</button>
                        <div style=" padding:10px;padding-top: 10px;">
                            <h5><span>Back to login ? </span><a href="../login" style="color: blue;">Login</a>
                            </h5>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    function validateReset() {
        // return true;
        var isValid = true;

        var myform = document.getElementById("myform");
        var emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
        var email = document.forms["resetForm"]["email"].value;
        if (email == "") {
            document.getElementById("email-error").innerText = "Email is required.";
            isValid = false;

        } else if (!email.match(emailPattern)) {
            document.getElementById("email-error").innerText = "Invalid Email Format.";
            isValid = false;
        }
        // if (!isValid) {
        // document.getElementById('msg').style.display = 'block';
        // document.getElementById('msg').innerText = 'Enter Valid Email';



        // }
        return isValid;
    }
</script>

</html>