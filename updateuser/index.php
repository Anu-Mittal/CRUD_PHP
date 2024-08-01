<?php
include '../connect.php';

session_start();
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true) {
  header('Location:../login');
  exit;
}
$myid = $_SESSION['id'];
$sql1 = "SELECT * from `em_users` where user_id=$myid";
$result1 = mysqli_query($con, $sql1);
$row1 = mysqli_fetch_assoc($result1);
$role1 = $row1['user_role_id'];



$id = $_GET['uid'];
$sql = "SELECT * from `em_users` where user_id=$id";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);

$firstname = $row['user_first_name'];
$lastname = $row['user_last_name'];
$email = $row['user_email'];
$mobile = $row['user_phone'];
$gender = $row['user_gender'];
$role = $row['user_role_id'];

$country = $row['user_country_id'];
$state = $row['user_state_id'];

$city = $row['user_city_id'];
$password = $row['user_password'];
$email1 = '';

//for permission
// echo $myid;
// echo $row['Id'];
if ($role1 != 1  && $role1 != 5  && $myid != $row['user_id']) {
  header("Location:../dashboard");
  exit;
}


// $retype=$row['retype'];

if (isset($_POST['submit'])) {

  if (empty($_POST['firstname'])) {
    $errors['firstname'] = "Firstname is required in this field.";
  } else {
    $firstname = $_POST['firstname'];
  }

  if (empty($_POST['lastname'])) {
    $errors['lastname'] = "Lastname is required in this field.";
  } else {
    $lastname = $_POST['lastname'];
  }


  //////////////////////////////////


  if (empty($_POST['email'])) {
    $errors['email'] = "Email is required.";
  } else {
    $email1 = $_POST['email'];
    if ($email != $email1) {
      if (!filter_var($email1, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
      } else {
        $sql_email = "SELECT * FROM `em_users` WHERE user_email='$email1'";
        $result_email = mysqli_query($con, $sql_email);
        if (mysqli_num_rows($result_email) > 0) {
          $errors['email'] = "Email already exists.";
        }
      }
    }
    // }
  }

  // if (empty($_POST['email'])) {
  //   $errors['email'] = "Email is required.";
  // } else {
  //   $email = $_POST['email'];

  //   } else {


  //     if ($row1 = mysqli_fetch_array($result_email)) {

  //     }
  //   }
  // }
  /////////////////////////////////////////////////


  if (empty($_POST['mobile'])) {
    $errors['mobile'] = "Mobile is required in this field";
  } else {
    $mobile = $_POST['mobile'];
    $pattern = '/^\d{10}$/';
    if (!preg_match($pattern, $mobile)) {
      $errors['mobile'] = "Mobile must be of 10 characters long ";
    } else {
      $mobile =  $_POST['mobile'];
    }
  }

  if (empty($_POST['gender'])) {
    $errors['gender'] = "Gender must be selected.";
  } else {
    $gender = $_POST['gender'];
  }

  if (empty($_POST['role'])) {
    $errors['role'] = "Role is required in this field";
  } else {
    $role = $_POST['role'];
  }

  if (empty($_POST['country'])) {
    $errors['country'] = "Country name is required in this field";
  } else {
    $country = $_POST['country'];
    $updatedAt = time();

    //     $get_country = "select * from Countries where id=$country";
    //     $result =  mysqli_query($con, $get_country);
    //     $row = mysqli_fetch_array($result);
    //     $country = $row['countrynames'];
  }

  if (empty($_POST['state']) || $_POST['state'] == '') {
    $errors['state'] = "State must be selected.";
  } else {
    $state = $_POST['state'];
  }

  if (empty($_POST['city']) || $_POST['city'] == '') {
    $errors['city'] = "City must be selected.";
  } else {
    $city = $_POST['city'];
  }

  if (!empty($_POST['password'])) {
    $errors['password'] = "Password is required.";
    $c_password = $_POST['password'];
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()\-_=+{};:,<.>])[A-Za-z\d!@#$%^&*()\-_=+{};:,<.>.]{8,}$/';

    if (!preg_match($pattern, $c_password)) {
      $errors['password'] = "Password must fulfill all conditions.";
    }
  } else {
    $c_password = $password;
  }
  if (!empty($_POST['password'])) {
    if (empty($_POST['retype'])) {
      $errors['retype'] = 'Please Confirm Password';
    } else {
      $retype = $_POST['retype'];
      if ($retype != $password) {
        $errors['retype'] = 'Password not match';
      } else {
        $password = md5($password);
      }
    }
  }




  if (empty($errors)) {

      if($myid===$id){
        $_SESSION['email']=$email1;
        $email1=$_SESSION['email'];
        $_SESSION['firstname']=$firstname;
        $firstname=$_SESSION['firstname'];
        $_SESSION['lastname']=$lastname;
        $lastname=$_SESSION['lastname'];
         $_SESSION['role']=$role;
         $role=$_SESSION['role'];
        $_SESSION['gender']=$gender;
        $gender=$_SESSION['gender'];
        $_SESSION['country']=$country;
        $country=$_SESSION['country'];
        $_SESSION['state']=$state;
        $state=$_SESSION['state'];
        $_SESSION['city']=$city;
        $city=$_SESSION['city'];
      }




    $sql = "UPDATE `em_users` set user_first_name='$firstname',user_last_name='$lastname',user_email= '$email1',user_phone='$mobile',user_gender='$gender',user_role_id='$role', user_country_id='$country',user_state_id='$state',user_city_id='$city',user_password='$c_password',user_updatedAt='$updatedAt' where user_id=$id ";
    $result = mysqli_query($con, $sql);
    if ($result) {
      // echo "Data updated successfully";
      header('location:../listusers');
    } else {
      echo "Error: " . $sql . "<br>" . die(mysqli_error($con));
    }
  }
}

?>

<!-- html -->
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin</title>

  <!-- Bootstrap -->
  <link rel="stylesheet" type="text/css" href="../css/dashboard.css">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  <style>
    .form-group {
      margin: 5px 0;
    }
  </style>


</head>

<body>
  <?php include "../header.php" ?>

  <div class="clear"></div>
  <div class="clear"></div>
  <div class="content">
    <div class="wrapper">
      <div class="bedcram">
        <ul>
          <li><a href="../dashboard">Home</a></li>
          <li><a href="../listusers">List Users</a></li>
          <li>Edit Users</li>
        </ul>
      </div>
      <div class="left_sidebr">
        <ul>
          <li><a href="../dashboard" class="dashboard">Dashboard</a></li>
          <li><a href="../listusers" class="user">Users</a>
            <!-- <ul class="submenu">
              <li><a href="">Mange Users</a></li>

            </ul> -->

          </li>
          <!-- <li><a href="" class="Setting">Setting</a>
            <ul class="submenu">
              <li><a href="">Chnage Password</a></li>
              <li><a href="">Mange Contact Request</a></li>
              <li><a href="#">Manage Login Page</a></li>

            </ul>

          </li>
          <li><a href="" class="social">Configuration</a>
            <ul class="submenu">
              <li><a href="">Payment Settings</a></li>
              <li><a href="">Manage Email Content</a></li>
              <li><a href="#">Manage Limits</a></li>
            </ul>

          </li> -->
        </ul>
      </div>
      <div class="right_side_content">
        <h1>Update Users</h1>
        <div class="list-contet">
          <?php
          if (!empty($errors)) {
            echo "<div class='error-message-div error-msg'><img src='../images/unsucess-msg.png'>Enter Details Correctly";
          }
          ?>
          <div class="error-message-div error-msg" id="msg" style="display:none;"><img src="../images/unsucess-msg.png">Enter Details Correctly </div>
          <!-- form -->
          <form class="form-edit" name="signupForm" method="post" id="myform" onsubmit="return validateForm()">
            <!-- firstname -->
            <div class="form-row">
              <div class="form-label">
                <label>First Name : <span>*</span></label>
              </div>
              <div class="input-field">
                <input type="text" class="search-box" name="firstname" placeholder="Enter First Name" id="firstname" value="<?php echo $firstname; ?>" />
                <p id="firstname-error" class="error"><?php echo isset($errors['firstname']) ? $errors['firstname'] : ''; ?></p>
              </div>
            </div>
            <!-- lastname -->
            <div class="form-row">
              <div class="form-label">
                <label>Last Name : <span>*</span></label>
              </div>
              <div class="input-field">
                <input type="text" class="search-box" name="lastname" placeholder="Enter Last Name" id="lastname" value="<?php echo $lastname; ?>" />
                <p id="lastname-error" class="error"><?php echo isset($errors['lastname']) ? $errors['lastname'] : ''; ?></p>
              </div>
            </div>

            <div class="form-row">
              <div class="form-label">
                <label>Email: <span>*</span></label>
              </div>
              <div class="input-field">
                <input type="text" class="search-box" name="email" placeholder="Enter Email" id="email" value="<?php echo $email; ?>" />
                <p id="email-error" class="error"><?php echo isset($errors['email']) ? $errors['email'] : ''; ?></p>
              </div>
            </div>

            <div class="form-row">
              <div class="form-label">
                <label>Mobile: <span>*</span> </label>
              </div>
              <div class="input-field">
                <input type="text" class="search-box" name="mobile" placeholder="Enter Mobile No." id="mobile" value="<?php echo $mobile; ?>" />
                <p id="mobile-error" class="error"><?php echo isset($errors['mobile']) ? $errors['mobile'] : ''; ?></p>
              </div>
            </div>

            <div class="form-group radio-row">
              <div class="form-label">
                <label>Gender: <span>*</span> </label>
              </div>
              <div class="input-field">
                <label><input type="radio" name="gender" value="male" <?php if ($gender == 'male') {
                                                                        echo "checked='checked'";
                                                                      } ?>> <span class="gender"> Male </span></label>
                <label> <input type="radio" name="gender" value="female" <?php if ($gender == 'female') {
                                                                            echo "checked='checked'";
                                                                          } ?>> <span class="gender"> Female </span> </label><br>
                <p id="gender-error" class="error"><?php echo isset($errors['gender']) ? $errors['gender'] : ''; ?></p>
              </div>

            </div>

            <div class="form-row">
              <div class="form-label">
                <label>Role: <span>*</span> </label>
              </div>
              <div class="input-field">
                <div class="select">
                  <select name="role" class="role-info" id="role" value="<?php echo $role; ?>">
                    <option value="">Select Your Role</option>
                    <?php
                    $sql1 = "select * from `em_roles`";
                    $result1 = mysqli_query($con, $sql1);
                    while ($row1 = mysqli_fetch_array($result1)) {
                      if ($role == $row1['role_id']) {
                        echo "<option selected value='$row1[role_id]'>$row1[role_name]</option>";
                      } else {
                        echo "<option value='$row1[role_id]'>$row1[role_name]</option>";
                      }
                    }
                    ?>
                  </select>
                  <p id="role-error" class="error"><?php echo isset($errors['role']) ? $errors['role'] : ''; ?></p>
                </div>

              </div>
            </div>

            <div class="form-row">
              <div class="form-label">
                <label>Country: <span>*</span> </label>
              </div>
              <div class="input-field">
                <div class="select">
                  <select name="country" class="country-info" id="countryId" value="<?php echo $country; ?>" onchange="fetchState()">
                    <option value="">Select Your Country</option>
                    <?php
                    $sql1 = "select * from `em_countries`";
                    $result1 = mysqli_query($con, $sql1);
                    while ($row1 = mysqli_fetch_array($result1)) {
                      if ($country == $row1['country_id']) {
                        echo "<option selected value='$row1[country_id]'>$row1[country_name]</option>";
                      } else {
                        echo "<option value='$row1[country_id]'>$row1[country_name]</option>";
                      }
                    }
                    ?>
                  </select>
                  <p id="country-error" class="error"><?php echo isset($errors['country']) ? $errors['country'] : ''; ?></p>
                </div>

              </div>
            </div>

            <!-- state -->
            <div class="form-row">
              <div class="form-label">
                <label>State: <span>*</span> </label>
              </div>
              <div class="input-field">
                <div class="select">
                  <select name="state" class="state-info" id="stateId" value="<?php echo $state ?>" onchange="fetchCity()">
                    <option value="">Select Your State</option>
                    <?php
                    $sql2 = "select * from `em_states` where country_id=$country";

                    $result2 = mysqli_query($con, $sql2);
                    while ($row2 = mysqli_fetch_array($result2)) {
                      if ($state == $row2['state_id']) {
                        // $s_id = $row2['id'];

                        echo "<option selected value='$row2[state_id]'>$row2[state_name] </option>";
                      } else {
                        echo "<option value='$row2[state_id]'>$row2[state_name]</option>";
                      }
                    }

                    ?>
                  </select>
                  <p id="state-error" class="error"><?php echo isset($errors['state']) ? $errors['state'] : ''; ?></p>
                </div>
              </div>
            </div>

            <!-- city -->
            <div class="form-row">
              <div class="form-label">
                <label>City: <span>*</span> </label>
              </div>
              <div class="input-field">
                <div class="select">
                  <select name="city" class="city-info" id="cityId" value="<?php echo $state ?>">
                    <option value="">Select Your City</option>
                    <?php
                    $sql3 = "select * from `em_cities` where state_id=$state";
                    $result3 = mysqli_query($con, $sql3);
                    while ($row3 = mysqli_fetch_array($result3)) {
                      if ($city == $row3['city_id']) {
                        echo "<option selected value='$row3[city_id]'>$row3[city_name] </option>";
                      } else {
                        echo "<option value='$row3[city_id]'>$row3[city_name]</option>";
                      }
                    }

                    ?>


                  </select>
                  <p id="city-error" class="error"><?php echo isset($errors['city']) ? $errors['city'] : ''; ?></p>

                </div>

              </div>
            </div>



            <div class="form-row">
              <div class="form-label">
                <label>Password: <span>*</span></label>
              </div>
              <div class="input-field">
                <input type="password" class="search-box" name="password" placeholder="Enter Password" id="" />
                <p id="password-error" class="error"><?php echo isset($errors['password']) ? $errors['password'] : ''; ?></p>
              </div>
            </div>

            <div class="form-row">
              <div class="form-label">
                <label> Retype Password: <span>*</span></label>
              </div>
              <div class="input-field">
                <input type="password" class="search-box" name="retype" placeholder="Enter Password" id="" />
                <p id="retype-error" class="error"><?php echo isset($errors['retype']) ? $errors['retype'] : ''; ?></p>
              </div>
            </div>

            <div class="form-row">
              <div class="form-label">
                <label><span></span> </label>
              </div>
              <div class="input-field">

                <input type="submit" name="submit" class="submit-btn" value="Save">

              </div>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
  <div class="footer">
    <div class="wrapper">
      <p>Copyright © 2014 yourwebsite.com. All rights reserved</p>
    </div>

  </div>

  <script>
    //    for  states names
    function fetchState() {
      const countryId = document.getElementById('countryId').value;
      console.log("world");
      // document.getElementById('stateId').removeAttribute("disabled");

      const requestOptions = {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          countryId: countryId
        }),
        redirect: 'follow'
      };

      fetch("../statefetch.php", requestOptions)
        .then(response => {

          if (!response.ok) {
            throw new Error('Network response was not ok');
          }

          const contentType = response.headers.get('content-type');
          if (contentType && contentType.includes('application/json')) {
            return response.json();
          } else {
            throw new Error('Response is not JSON');
          }
        })
        .then(result => {
            console.log(result)
            const stateSelect = document.getElementById('stateId');
            stateSelect.innerHTML = '<option value="">Select Your State</option>';
            result.forEach(element => {
              let option = document.createElement('option');
              option.textContent = element.state_name;
              option.value = element.id;
              stateSelect.appendChild(option);
              option = null
            });
            stateSelect.removeAttribute('disabled')

          }

        )
        .catch(error => console.log('error', error));
    }

    // for city names
    function fetchCity() {
      const stateId = document.getElementById('stateId').value;
      // document.getElementById('stateId').removeAttribute("disabled");

      const requestOptions = {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          stateId: stateId
        }),
        redirect: 'follow'
      };

      fetch("../cityfetch.php", requestOptions)
        .then(response => {

          if (!response.ok) {
            throw new Error('Network response was not ok');
          }

          const contentType = response.headers.get('content-type');
          if (contentType && contentType.includes('application/json')) {
            return response.json();
          } else {
            throw new Error('Response is not JSON');
          }
        })
        .then(result => {
            console.log(result)
            const citySelect = document.getElementById('cityId');
            citySelect.innerHTML = '<option value="">Select Your City</option>';
            result.forEach(element => {
              let option = document.createElement('option');
              option.textContent = element.city_name;
              option.value = element.id;
              citySelect.appendChild(option);
              option = null
            });
            citySelect.removeAttribute('disabled')

          }

        )
        .catch(error => console.log('error', error));
    }


    //validations
    function validateForm() {
      return true;
      var isValid = true;

      var myform = document.getElementById("myform");
      var firstname = document.forms["signupForm"]["firstname"].value;
      var lastname = document.forms["signupForm"]["lastname"].value;
      var emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
      var email = document.forms["signupForm"]["email"].value;
      var mobilePattern = /^\d{10}$/;
      var mobile = document.forms["signupForm"]["mobile"].value;

      var gender = document.forms["signupForm"]["gender"].value;
      var role = document.forms["signupForm"]["role"].value;
      var country = document.forms["signupForm"]["country"].value;
      var state = document.forms["signupForm"]["state"].value;
      var city = document.forms["signupForm"]["city"].value;
      var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()\-_=+{};:,<.>])[A-Za-z\d!@#$%^&*()\-_=+{};:,<.>.]{8,}$/;
      var password = document.forms["signupForm"]["password"].value;
      var retype = document.forms["signupForm"]["retype"].value;
      // var confirmation = document.forms["signupForm"]["confirmation"].value;
      // console.log("here");
      // return false;




      document.getElementById("firstname-error").innerText = "";
      document.getElementById("lastname-error").innerText = "";
      document.getElementById("email-error").innerText = "";
      document.getElementById("mobile-error").innerText = "";
      document.getElementById("gender-error").innerText = "";
      document.getElementById("role-error").innerText = "";
      document.getElementById("country-error").innerText = "";
      document.getElementById("state-error").innerText = "";
      document.getElementById("city-error").innerText = "";
      document.getElementById("password-error").innerText = "";
      document.getElementById("retype-error").innerText = "";
      // document.getElementById("confirmation-error").innerText = "";


      if (firstname == "") {
        document.getElementById("firstname-error").innerText = "Firstname is required.";
        isValid = false;
      }

      if (lastname == "") {
        document.getElementById("lastname-error").innerText = "Lastname is required.";
        isValid = false;
      }

      if (email == "") {
        document.getElementById("email-error").innerText = "Email is required.";
        isValid = false;
      } else if (!email.match(emailPattern)) {
        document.getElementById("email-error").innerText = "Please enter a valid email address.";
        isValid = false;
      }

      if (mobile == "") {
        document.getElementById("mobile-error").innerText = "Mobile is required.";
        isValid = false;
      } else if (!mobile.match(mobilePattern)) {
        document.getElementById("mobile-error").innerText = "Mobile must be correctly filled";
        isValid = false;
      }

      if (gender == "") {
        document.getElementById("gender-error").innerText = "Gender must be selected.";
        isValid = false;
      }

      if (role == "") {
        document.getElementById("role-error").innerText = "Role must be selected.";
        isValid = false;
      }

      if (country == "") {
        document.getElementById("country-error").innerText = "Country  must be selected.";
        isValid = false;
      }

      if (state == "") {
        document.getElementById("state-error").innerText = "State must be selected.";
        isValid = false;
      }
      if (city == "") {
        document.getElementById("city-error").innerText = "City  must be selected.";
        isValid = false;
      }

      if (password == "") {
        document.getElementById("password-error").innerText = "Password is required.";
        isValid = false;
      } else if (!password.match(passwordPattern)) {
        document.getElementById("password-error").innerText = "Password must fulfill all conditions.";
        isValid = false;
      }

      // if (retype == "") {
      //   document.getElementById("retype-error").innerText = " Confirmation of password is required.";
      //   isValid = false;
      // } else if (retype != password) {
      //   document.getElementById("retype-error").innerText = "It is not matched with above written password ";
      //   isValid = false;
      // }

      // if (confirmation == "") {
      //   document.getElementById("confirmation-error").innerText = "Must checked.";
      //   isValid = false;
      // }

      if (!isValid) {
        document.getElementById('msg').style.display = 'block';
      }
      return isValid;
    }
  </script>


</body>

</html>