<?php
// $showerror = false;
// $login = false;
include '../connect.php';
include '../email-service.php';
session_start();

if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    header('Location: ../dashboard');
    exit;
}

// if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true) {
//   header('Location:../login');
//   exit;
// }
$_SESSION['status'] = "";
$token1 = $_GET['t'];
$password = '';
$retype = '';
if (isset($token1)) {
    $sql = "select * from em_users where user_token='$token1'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    if (empty($row)) {
        // echo "<h1>Invalid URL</h1>";
        $_SESSION['status'] = "Token expired.";
        header("location:../login");
        // $_SESSION['status']="Password Successfully Changed.";
        die(mysqli_error($con));
    } else {
        if (!(time() > $row['user_expiry_token'])  || !(time() <= ($row['user_expiry_token'] + 60 * 5))) {
            $sql = "update `em_users` set user_token=NULL,user_expiry_token=NULL where user_token='$token1'";
            $result = mysqli_query($con, $sql);
            if (!$result) {
                //     echo "print";

                // }
                // } else {
                // echo "expire time";
                die(mysqli_error($con));
            }
        }
    }
}

if (isset($_POST['submit'])) {
    $errors = [];
    if (empty($_POST['password'])) {
        $errors['password'] = false;
        $_SESSION['status'] = "Password is required.";
        // unset($_SESSION['status']);
    } else {
        $password = $_POST['password'];
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()\-_=+{};:,<.>])[A-Za-z\d!@#$%^&*()\-_=+{};:,<.>.]{8,}$/';
        if (!preg_match($pattern, $password)) {
            $errors['password'] = false;
            $_SESSION['status'] = "Password must fulfill all conditions.";
        }
    }
    if (empty($_POST['retype'])) {
        if (!empty($_POST['password'])) {
            $errors['retype'] = false;
            $_SESSION['status'] = ' Retype Password is required.';
        }
    } else {
        $retype = $_POST['retype'];
        if ($retype != $password) {
            $errors['retype'] = false;
            $_SESSION['status'] = 'Password not match.';
        }
        // else {
        //     $password = md5($password);
        // }
    }
    // }

    if (empty($errors)) {
        $sql = "update `em_users` set user_password='$password',user_token=NULL,user_expiry_token=NULL where user_token='$token1'";
        $result = mysqli_query($con, $sql);
        if ($result) {
            // echo "Data inserted successfully";
            $subject = "Password Changed Successfully!";
            $name = $row['user_first_name'];

            $sql = "select * from em_templates where template_name='change_password'";
            $result = mysqli_query($con, $sql);

            $row1 = mysqli_fetch_assoc($result);
            $body = $row1['template_body'];

            $body = str_replace("{{name}}", $name, $body);
            // unset($_SESSION['status']);
            if(!empty($token1)){
            $_SESSION['status'] = "Password successfully changed.";
            sendEmail($row['user_first_name'], $row['user_email'], $subject, $body, " ");

            header('location:../login');}
        } else {
            $_SESSION['status'] = "Did not update password, something went wrong.";
            header("Location: change-password");
            exit(0);
            // echo "Error: " . $sql . "<br>" . die(mysqli_error($con));
        }
    }
}





?>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin</title>
    <link href="../css/dashboard.css" rel="stylesheet">

    <style>
        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip .tooltiptext {
            /* visibility: hidden; */
            display: none;
            width: 200px;
            background-color: "white";
            color: black;
            text-align: center;
            border-radius: 5px;
            border: 1px solid black;
            padding: 5px;
            position: absolute;
            z-index: 1;
            top: -30px;
            left: -39%;


            /* bottom: -80%; */
            /* Position the tooltip above the text */

            margin-left: -100px;
            /* opacity: 0; */
            transition: opacity 0.3s;
        }

        .tooltip .tooltiptext::after {
            content: "";
            position: absolute;
            top: 50%;
            right: -10px;
            /* Position arrow to the left of the tooltip */
            transform: translateY(-50%);
            border-width: 5px;
            border-style: solid;
            border-color: transparent transparent transparent black;
        }

        /* .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        } */
        /* .tooltip input:focus{
            border:1px solid blue;
        } */

        .pass_input:focus+.tooltiptext {
            display: block !important;
            opacity: 1 !important;

        }

        .error {
            color: red;
            font-size: 12px;
            padding: 10px;
        }
    </style>
</head>



<body>
    <div class="login_section">
        <div class="wrapper relative" style="height:300px">
            <div style="display:none" class="meassage_successful_login" id="sucess">You have Successfull Edit </div>
            <div class="heading-top">
                <div class="logo-cebter"><a href="#"><img src="../images/at your service_banner.png"></a></div>
            </div>
            <div class="box">
                <div class="outer_div">

                    <h2> Update<span> Password</span></h2>
                    <?php if (!empty($_SESSION['status'])) : ?>
                        <div class="error-message-div error-msg" style="margin-bottom:10px;"><?php echo $_SESSION['status'];
                                                                                                unset($_SESSION['status']); ?></div>
                    <?php endif; ?>

                    <!-- <div class="error-message-div error-msg" id="msg" style="display:none;"><img src="../images/unsucess-msg.png"><strong>Invalid!</strong> password or not matched </div> -->

                    <form name="updateForm" id="myform" class="margin_bottom" role="form" method="POST" onsubmit="return validateChange()" novalidate>

                        <div class="form-group tooltip">
                            <label>Password <span>*</span></label>
                            <input type="password" class="search-box pass_input" name="password" placeholder="Enter New Password" id="password" onkeyup="validatePassword()" value="<?php echo $password ?>" />
                            <span class="tooltiptext">
                                <ul>
                                    <li class="pass_len">at least 8 characters </li>
                                    <li class="pass_lower">at least one lowercase letter,</li>
                                    <li class="pass_upper">one uppercase letter,</li>
                                    <li class="pass_num">one numeric digit</li>
                                    <li class="pass_special">one special character.</li>
                                </ul>
                            </span>
                            <p id='password-error' class='error'><?php echo (isset($errors['password'])) ? $errors['password'] : ''; ?></p>

                        </div>



                        <!-- <div class="form-group">
                            <label for="new_password">Password</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Enter New Password" />
                        </div> -->

                        <div class="form-group">
                            <label for="retype">Confirm Password <span>*</span></label>
                            <input type="password" class="form-control" name="retype" id="retype" placeholder="Confirm Password" value="<?php echo $retype;  ?>" />
                            <p id='retype-error' class='error'><?php echo (isset($errors['retype'])) ? $errors['retype'] : ''; ?></p>
                        </div>

                        <button type="submit" name="submit" class="btn_login">Reset</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        //validatePassword
        function highlightErrors(errorClass) {
            // First, reset all to default color
            let allClasses = ["pass_lower", "pass_upper", "pass_num", "pass_special", "pass_len"];
            allClasses.forEach(cls => {
                let elements = document.getElementsByClassName(cls);
                for (let element of elements) {
                    element.style.color = "green"; // default color
                }
            });

            // Now, set the color to red for classes in the errorClass array
            errorClass.forEach(cls => {
                let elements = document.getElementsByClassName(cls);
                for (let element of elements) {
                    element.style.color = "red";
                }
            });
        }

        function validatePassword() {
            const password = document.getElementById("password").value;
            const errorMessage = document.getElementById("error-message");

            let errors = [];
            let errorClass = []
            if (!/(?=.*[a-z])/.test(password)) {
                errors.push("at least one lowercase letter");
                errorClass.push("pass_lower")

            }
            if (!/(?=.*[A-Z])/.test(password)) {
                errors.push("at least one uppercase letter");
                errorClass.push("pass_upper")
            }
            if (!/(?=.*\d)/.test(password)) {
                errors.push("at least one digit");
                errorClass.push("pass_num")
            }
            if (!/(?=.*[!@#$%^&*()\-_=+{};:,<.>])/.test(password)) {
                errors.push("at least one special character");
                errorClass.push("pass_special")
            }
            if (password.length < 8) {
                errors.push("a minimum length of 8 characters");
                errorClass.push("pass_len")
            }

            highlightErrors(errorClass);
        }






        function validateChange() {
            // return true;

            var isValid = true;


            var myform = document.getElementById("myform");
            var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()\-_=+{};:,<.>])[A-Za-z\d!@#$%^&*()\-_=+{};:,<.>.]{8,}$/;
            var password = document.forms["updateForm"]["password"].value;
            var retype = document.forms["updateForm"]["retype"].value;

            document.getElementById("password-error").innerText = "";
            document.getElementById("retype-error").innerText = "";


            if (password == "") {
                document.getElementById("password-error").innerText = "Password is required.";
                isValid = false;
            } else if (!password.match(passwordPattern)) {
                document.getElementById("password-error").innerText = "Password must be at least 8 characters long and include at least one lowercase letter, one uppercase letter, one numeric digit, and one special character";
                isValid = false;
            }

            if (retype == "") {
                document.getElementById("retype-error").innerText = " Confirmation is required.";
                isValid = false;
            } else if (retype != password) {
                document.getElementById("retype-error").innerText = "Not matched with password ";
                isValid = false;
            }


            // if (!isValid) {
            //     document.getElementById('msg').style.display = 'block';

            // }

            return isValid;
            // document.getElementById('sucess').style.display = 'block';
        }
    </script>
</body>

</html>