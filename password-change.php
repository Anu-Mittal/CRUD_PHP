<?php
// $showerror = false;
// $login = false;
include 'connect.php';

session_start();
// if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
//     header('Location: dashboard.php');
//     exit;
// }
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
        <div class="wrapper relative" style="height:300px">
            <div style="display:none" class="meassage_successful_login">You have Successfull Edit </div>
            <div class="heading-top">
                <div class="logo-cebter"><a href="#"><img src="images/at your service_banner.png"></a></div>
            </div>
            <div class="box" style="overflow-y: scroll;height:460px">
                <div class="outer_div">
                    <h2 style="text-align:left;background-color:rgb(239, 239, 239); padding:7px 12px;width:90%;font-size:larger;">Reset Password <span></span></h2>
                    <?php if (isset($_SESSION['status'])): ?>
                <div class="error-message-div error-msg"><?php echo $_SESSION['status']; unset($_SESSION['status']); ?></div>
            <?php endif; ?>
                    <form class="margin_bottom" action="password-code-reset.php" role="form" method="POST">
                        <input type="hidden" name="password_token" value="<?php if(isset($_GET['token'])){echo $_GET['token'];} ?>" >
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" name="email" value="<?php if(isset($_GET['email'])){echo $_GET['email'];} ?>" placeholder="Enter Email Address"  />
                        </div>
                        <div class="form-group">
                            <label for="new_password">Password</label>
                            <input type="text" class="form-control" name="new_password" placeholder="Enter New Password"  />
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="text" class="form-control" name="confirm_password" placeholder="Update Password"  />
                        </div>
                        <button style="margin-top:15px; padding:5px;background-color:#007FFF;border:0px;border-radius:2px; color:white;" type="submit" name="password_update">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>