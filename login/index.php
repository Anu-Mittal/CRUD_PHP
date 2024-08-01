<?php
$login = false;
include '../connect.php';
session_start();
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
  header('Location:../dashboard');
  exit;
}



$email = '';
$password = '';
$id = '';

$firstname = '';


$errors = [];
if (isset($_POST['submit'])) {

  if (empty($_POST['email']) || empty($_POST['password'])) {
    $errors['email'] = false;
    // $_SESSION['status'] = "Email is required.";
  } else {
    $email = $_POST['email'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = false;
      // $_SESSION['status'] = "Please enter valid Email.";
    }
  }

  if (empty($_POST['password'])) {
    $errors['password'] = false;
    // $_SESSION['status'] = "Password is required.";
  } else {
    $password = $_POST['password'];
  }

  if (empty($errors)) {
    // echo $email;
    $sql = "SELECT user_id,user_first_name,user_email, user_role_id,user_password,user_gender,user_country_id,user_state_id,user_city_id from `em_users` where user_email='$email' and user_isDeleted=0";
    $result = mysqli_query($con, $sql);

    echo "print";
    if ($result) {
      if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        if ($row['user_password'] == md5($password)) {

          $login = true;
          $_SESSION['loggedIn'] = true;
          $_SESSION['email'] = $row['user_email'];
          $_SESSION['password'] = $row['user_password'];
          $_SESSION['id'] = $row['user_id'];
          $_SESSION['firstname'] = $row['user_first_name'];
          $_SESSION['role'] = $row['user_role_id'];
          $_SESSION['gender'] = $row['user_gender'];
          $_SESSION['country'] = $row['user_country_id'];
          $_SESSION['state'] = $row['user_state_id'];
          $_SESSION['city'] = $row['user_city_id'];



          if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
            header('Location:../dashboard');
            exit;
          }
        } else {
          $errors['password'] = "Invalid Password";
        }
      } else {
        $errors['email'] = "Email is not registered";
      }
    }
    else {
      die(mysqli_error($con));
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

  <!-- Bootstrap -->
  <link href="../css/dashboard.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  <style>
    .error {
      color: red;
      font-size: 12px;
      padding-top: 10px;
      padding-left: 10px;
    }
  </style>
</head>

<body>
  <div class="login_section">
    <div class="wrapper relative">
      <div style="display:none" class="meassage_successful_login">You have Successfull Edit </div>
      <div class="heading-top">
        <div class="logo-cebter"><a href="#"><img src="../images/at your service_banner.png"></a></div>
      </div>
      <div class="box">
        <div class="outer_div">

          <h2>Admin <span>Login</span></h2>
          <?php if (!empty($_SESSION['status'])) : ?>
            <div class="error-message-div error-msg" style="margin-bottom:10px;"><?php echo $_SESSION['status'];
                                                                                  unset($_SESSION['status']); ?></div>
          <?php endif; ?>

     
          <!-- <div class="error-message-div error-msg" id="msg" style="display:none;"><img src="../images/unsucess-msg.png"><strong>Invalid!</strong> username or password </div> -->
          <?php
                    if (!empty($errors)) {
                        echo "<div class='error-message-div error-msg'><img src='../images/unsucess-msg.png'><strong>Invalid!</strong> username or password</div>";
                    }
                    ?>




          <!-- form -->
          <form name="signupForm" id="myform" class="margin_bottom" method="post" onsubmit="return validateLogin()" novalidate>


            <div class="form-group">
              <label for="exampleInputEmail1">Email <span>*</span></label>
              <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email" value="<?php echo $email; ?>">
              <p id='email-error' class='error'><?php echo (isset($errors['email'])) ? $errors['email'] : ''; ?></p>
            </div>


            <div class="form-group">
              <label for="exampleInputPassword1">Password <span>*</span></label>
              <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" value="<?php echo $password; ?>">
              <p id='password-error' class='error'><?php echo (isset($errors['password'])) ? $errors['password'] : ''; ?></p>
              <h5 style="font-size: 12px;text-align: end;">
                <a href="../passwordreset" style="color:blue;"> forget password?</a>
              </h5>
            </div>

            <button type="submit" name="submit" class="btn_login">Login</button>

            <div style=" padding:10px;padding-top: 10px;">
              <h5><span>Do you want to create an Account? </span><a href="../signup" style="color: blue;">SignUp</a>
              </h5>
            </div>

          </form>
        </div>
      </div>
    </div>


    <script>
      function validateLogin() {
        // return true;
        var isValid = true;

        var myform = document.getElementById("myform");
        var emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
        var email = document.forms["signupForm"]["email"].value;
        var password = document.forms["signupForm"]["password"].value;

        if (email == "") {
          document.getElementById("email-error").innerText = "Email is required.";
          isValid = false;
        }
         else if (!email.match(emailPattern)) {
          document.getElementById("email-error").innerText = "Enter valid email.";
          isValid = false;
        }

        if (password == "") {
          document.getElementById("password-error").innerText = "Password is required.";
          isValid = false;

        }
        // if (!isValid) {
        //   document.getElementById('msg').style.display = 'block';
        // }
        return isValid;
      }
    </script>
</body>

</html>