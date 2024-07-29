<?php
include 'connect.php';
include 'email-service.php';


$email = '';

if (isset($_POST['submit'])) {
    $errors = [];


    if (empty($_POST['email'])) {
        $errors['email'] = "Email is required.";
    } else {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format.";
        } else {
            $sql_email = "SELECT * FROM `employees` WHERE email='$email'";
            $result = mysqli_query($con, $sql_email);
            $row1 = mysqli_fetch_assoc($result);

            if (empty($row1)) {
                $errors['email'] = "Email not registered.";
            }
        }
    }

    if (empty($errors)) {

        $token = uniqid();
        $expiry_token = time();
        $sql1 = "UPDATE employees SET token = '$token', expiry_token = '$expiry_token' WHERE email = '$email'";
        $result1 = mysqli_query($con, $sql1);


        if ($result1) {
            // echo var_dump($row1);
            // die();
            $subject = "Password Change Request";
            $name = $row1['firstname'];
            
            $sql = "select * from templates_info where temp_names='forget_password'";
            $result=mysqli_query($con,$sql);

            $row = mysqli_fetch_assoc($result);
            $body = $row['templates'];

            $body = str_replace("{{name}}",$name,$body);
            $link="localhost/crud_design/password-change.php?t=$token";
            
            $body=str_replace("{{url}}",$link,$body);

            sendEmail($row1['firstname'], $email, $subject, $body);
            header("location:login.php");
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
    <link href="css/dashboard.css" rel="stylesheet">
</head>

<body>
    <div class="login_section">
        <div class="wrapper relative">

            <div class="heading-top">
                <div class="logo-cebter"><a href="#"><img src="images/at your service_banner.png"></a></div>
            </div>
            <div class="box">
                <div class="outer_div">
                    <?php
                    if (!empty($errors)) {
                        echo "<div class='error-message-div error-msg'><img src='images/unsucess-msg.png'><strong>Invalid!</strong>   Email </div>";
                    }
                    ?>
                    <div class="error-message-div error-msg" id="msg" style="display:none;"><img src="images/unsucess-msg.png"><strong>Invalid!</strong> Email</div>
                    <!-- <h2>Reset<span> Password </span></h2> -->

                    <form name="signupForm" id="myform" class="margin_bottom" role="form" method="POST" onsubmit="return validateLogin()">
                        <div class="form-group" style="padding-top: 50px;margin-bottom: 15px;">
                            <label for="email" style="padding-top: 26px;">Email Address</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter Email Address" />
                        </div>
                        <!-- <button style="" type="submit" name="password_reset_link">Send Recover mail</button> -->
                        <button type="submit" name="submit" class="btn_login">Send Link</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    function avalidateLogin() {
        var isValid = true;

        var myform = document.getElementById("myform");
        var emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
        var email = document.forms["signupForm"]["email"].value;
        if (email == "") {
            isValid = false;
        } else if (!email.match(emailPattern)) {
            isValid = false;
        }
        if (!isValid) {
            document.getElementById('msg').style.display = 'block';
        }
        return isValid;
    }
</script>

</html>