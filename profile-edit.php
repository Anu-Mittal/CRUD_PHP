<?php
session_start();
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true) {
  header('Location:login.php');
  exit;
}

?>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin</title>

  <!-- Bootstrap -->
  <link rel="stylesheet" type="text/css" href="css/dashboard.css">

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
  <?php include "header.php" ?>

  <div class="clear"></div>
  <div class="clear"></div>
  <div class="content">
    <div class="wrapper">
      <div class="bedcram">
        <ul>
          <li><a href="dashboard.php">Home</a></li>
          <li><a href="list-users.php">List Users</a></li>
          <li>Edit Users</li>
        </ul>
      </div>
      <div class="left_sidebr">
        <ul>
          <li><a href="dashboard.php" class="dashboard">Dashboard</a></li>
          <li><a href="list-users.php" class="user">Users</a>
            <ul class="submenu">
              <li><a href="">Mange Users</a></li>

            </ul>

          </li>
          <li><a href="" class="Setting">Setting</a>
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

          </li>
        </ul>
      </div>
      <div class="right_side_content">
        <h1>User Information</h1>
        <div class="list-contet">


          <!-- form -->
          <!-- <form class="form-edit" name="signupForm" method="post" id="myform"> -->
            <!-- firstname -->
            <div class="form-row">
              <div class="form-label">
                <label><span></span> </label>
              </div>
              <div class="input-field">
                <a href="edit-user.php?uid=$_SESSION['id']"><input type="submit" name="submit" class="submit-btn" value="Edit"></a>
              </div>
            </div>

            <ul>
              <li> User Id: <?php echo $_SESSION['Id']?></li>
                
              <li> First Name : <?php echo $_SESSION['firstname'] ?></li>
              <li> Last Name : <?php echo $_row['lastname'] ?></li>
              <li> Email : <?php echo $_SESSION['email'] ?></li>
              <li> Mobile : <?php echo $_SESSION['mobile'] ?></li>

             
 
            </ul>
        </div>

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