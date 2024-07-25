<?php
$login = false;
include 'connect.php';
session_start();
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
  header('Location:dashboard.php');
  exit;
}



$email = '';
$password = '';
$id = '';

$firstname = '';


$errors = [];
if (isset($_POST['submit'])) {

  if (empty($_POST['email']) || empty($_POST['password'])) {
    $errors['email'] = "Email is required";
  } else {
    $email = $_POST['email'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = "Please enter valid Email ";
    }
  }

  if (empty($_POST['password'])) {
    $errors['password'] = "Password is required ";
  } else {
    $password = $_POST['password'];
  }

  if (empty($errors)) {

    $sql = "SELECT Id,firstname,email, role_id,password from `employees` where email='$email'";
    $result = mysqli_query($con, $sql);

    if ($result) {
      if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        if ($row['password'] == md5($password)) {

          $login = true;
          $_SESSION['loggedIn'] = true;
          $_SESSION['email'] = $row['email'];
          $_SESSION['password'] = $row['password'];
          $_SESSION['id'] = $row['Id'];
          $_SESSION['firstname'] = $row['firstname'];
          $_SESSION['role'] = $row['role_id'];


          if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
            header('Location:dashboard.php');
            exit;
          }
        } else {
          $errors['password'] = "Invalid Password";
        }
      } else {
        $errors['email'] = "Email is not registered";
      }
    }
  }
  // }

  else {
    die(mysqli_error($con));
  }
}


// $hash_password = md5($password);
//   $sql = "SELECT * from `employees` where email='$email' && password='$hash_password'";
//   $result = mysqli_query($con, $sql);

//   if (mysqli_num_rows($result) == 1) {
//   echo "hello";
//     $row = mysqli_fetch_assoc($result);
//     if ($row['email'] === $email && $row['password'] === $hash_password) {
//       echo "Logged In";
//       $_SESSION['email'] = $row['email'];
//       $_SESSION['firstname'] = $row['firstname'];
//       $_SESSION['id'] = $row['Id'];
//       header("Location: dashboard.php");
//       exit();
//     }
//  }



// }

?>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin</title>

  <!-- Bootstrap -->
  <link href="css/dashboard.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
  <div class="login_section">
    <div class="wrapper relative">
      <div style="display:none" class="meassage_successful_login">You have Successfull Edit </div>
      <div class="heading-top">
        <div class="logo-cebter"><a href="#"><img src="images/at your service_banner.png"></a></div>
      </div>
      <div class="box">
        <div class="outer_div">

          <h2>Admin <span>Login</span></h2>
          <?php
          if (!empty($errors)) {
            echo "<div class='error-message-div error-msg'><img src='images/unsucess-msg.png'><strong>Invalid!</strong> username or password</div>";
          }
          ?>

          <div class="error-message-div error-msg" id="msg" style="display:none;"><img src="images/unsucess-msg.png"><strong>Invalid!</strong> username or password </div>
          <form name="signupForm" id="myform" class="margin_bottom" method="post" onsubmit="return validateLogin()">


            <div class="form-group">
              <label for="exampleInputEmail1">Email</label>
              <input type="email" class="form-control" name="email" id="email" value="<?php echo $email; ?>">

            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Password</label>
              <input type="password" class="form-control" name="password" id="password" value="<?php echo $password; ?>">
              <h5 style="font-size: 12px;text-align: end;">
                <a href="password-reset.php" style="color:blue;"> forget password?</a>
              </h5>
            </div>
            <!-- <h5></h5> -->
            <button type="submit" name="submit" class="btn_login">Login</button>

            <div style=" padding:10px;padding-top: 10px;">
              <h5><span>Do you want to create an Account? </span><a href="sign-up.php" style="color: blue;">SignUp</a>
              </h5>

            </div>
          </form>
        </div>
      </div>
    </div>


    <script>
      function validateLogin() {
        var isValid = true;

        var myform = document.getElementById("myform");
        var emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
        var email = document.forms["signupForm"]["email"].value;
        var password = document.forms["signupForm"]["password"].value;
        if (email == "") {

          isValid = false;
        } else if (!email.match(emailPattern)) {
          isValid = false;
        }

        if (password == "") {

          isValid = false;

        }
        if (!isValid) {
          document.getElementById('msg').style.display = 'block';
        }
        return isValid;
      }
    </script>
</body>

</html>