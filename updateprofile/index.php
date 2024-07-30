<?php
include '../connect.php';

session_start();
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true) {
  header('Location:../login');

  exit;
}
$email = $_SESSION['email'];

$image_path = null;
$id = $_SESSION['id'];
$sql = "SELECT employees.Id,firstname,lastname,email,gender,country,Countries.countrynames,state,States.statenames,city,Cities.citynames,mobile,roles.role,image FROM `employees` left join roles on roles.id=employees.role_id left join Countries on employees.country=Countries.id left join States on employees.state=States.id left join Cities on employees.city=Cities.id where isDeleted=0 and employees.Id=$id";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$firstname = $row['firstname'];
$lastname = $row['lastname'];
// $email = $row['email'];
$mobile = $row['mobile'];
$gender = $row['gender'];
$country = $row['countrynames'];
// $get_country = "select * from Countries where id=$country";
//         $result =  mysqli_query($con, $get_country);
//         $row = mysqli_fetch_array($result);
//         $country = $row['countrynames'];
$image_path = $row['image'];
$state = $row['statenames'];
$city = $row['citynames'];
// $password = $row['password'];
// $retype=$row['retype'];
$role = $row['role'];



// if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile'])) {
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_FILES['profile']) ) {
    $upload_dir = '../img/';
    $file = $_FILES['profile'];

    // Check if file was uploaded without errors
    if ($file['error'] == UPLOAD_ERR_OK) {
      $file_tmp_path = $file['tmp_name'];
      $file_name = $file['name'];
      $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

      // Generate a unique name for the file
      $new_file_name = uniqid() . '.' . $file_extension;
      $dest_path = $upload_dir . $new_file_name;

      // Move the file to the img/ folder
      if (move_uploaded_file($file_tmp_path, $dest_path)) {
        // Prepare an SQL update query
        $sql = "UPDATE employees SET image = '$new_file_name' WHERE Id = $id";

        $result = mysqli_query($con, $sql);

        // Execute the query
        if ($result) {
          $image_path = $new_file_name;
         
        } else {
          echo "Error updating record: ";
        }
      } else {
        echo "Error moving the uploaded file.";
      }
    } else {
      echo "Error uploading the file.";
    }
  }



  if (isset($_POST['delete-image']) && $_POST['delete-image'] == '1') {
    $upload_dir = '../img/';
    $image_to_delete = $image_path;

    // Prepare an SQL update query to set image to null
  
    $sql = "UPDATE employees SET image = NULL WHERE Id = $id";
    // $sql = "DELETE from  employees WHERE Id = $id";
    $result = mysqli_query($con, $sql);

    // Execute the query
    if ($result) {
      // Remove the image file from the server
      // if (file_exists($upload_dir . $image_to_delete)) {
      //   unlink($upload_dir . $image_to_delete);
      // }
      //unlink me folder / file name
      $image_path = null;
      header('location:../updateprofile');
    } else {
      die(mysqli_error($con));
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
    /* .profile-pic img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      border: 3px solid #333;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      display: block;
      margin: 0 auto 20px auto;
    } */
    .profile-pic {
        display: flex;
        align-items: center;
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .profile-pic {
        margin: 13px 217px;
        border: none;
        /* padding-bottom: 10px; */
    }

    .profile-pic img {
        height: 150px;
        width: 170px;
        border-radius: 50%;
    }

    #image-form {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .container {
        display: flex;
        justify-content: center;
        align-items: center;

    }

    .user-data {
        border-collapse: collapse;
        font-size: 18px;
        text-align: left;
    }

    .user-data p {
        display: table-row;
    }

    .user-data p::before {
        content: attr(data-label);
        display: table-cell;
        font-weight: bold;
        padding: 10px;
        border: 1px solid #ddd;
        background-color: #f2f2f2;
    }

    .user-data p span {
        display: table-cell;
        padding: 10px;
        border: 1px solid #ddd;
    }

    .user-data p:nth-child(even) span {
        background-color: #f9f9f9;
    }

    .btn {
        display: flex;
        justify-content: center;

        align-items: center;
        padding-top: 10px;
    }

    .submit-btn a {
        color: white;
        text-decoration: none;


    }

    .edit-btn {
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 10px;
        overflow: hidden;
        background-color: #FF651B;
        position: relative;
        cursor: pointer;
        transition: 0.3s;
        margin-left: 27px;
    }

    .edit-btn input {
        position: absolute;
        cursor: pointer;
        height: 40px;
        opacity: 0;
        top: 0;
        left: 0;
    }

    .edit-btn i {
        margin: 0 !important;
        padding-left: 8px;
        cursor: pointer;
        color: white;
    }

    .edit-btn:hover {
        background-color: #214139;
    }

    /* 
    .fa-pencil:before {
      padding: 10px;
      padding-top: 10;
      margin-left: 18px;
      border-radius: 11px;
      color: white;
    } */

    .delete-icon {
        /* border: 1px solid black; */
        padding: 8px;
        margin-left: 30px;
        margin-top: 14px;
        border-radius: 10px;
        cursor: pointer;
        font-size: 24px;
        background-color: #FF651B;
        color: white;
        cursor: pointer;
        transition: 0.3s;
        border: none;
    }

    .delete-icon:hover {
        background-color: #214139;

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
                <h1>My Profile</h1>
                <div class="list-content">

                    <div class="profile-pic" style="height:150px;">
                        <img src=<?php echo $image_path ? '../img/' . $image_path : '../images/userr.png'; ?> alt="img"
                            id="profile-image">
                        <form id="image-form"  method="POST" enctype="multipart/form-data">
                            <div class="edit-btn">
                                <i class="fa-solid fa-pencil"></i>
                                <input type="file" name="profile" id="select-file" onchange="uploadImage(event)">
                            </div>
                            <!-- <input type="submit" value="Upload"> -->
                            <button type="button" class="delete-icon" id="delete-image">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            <input type="hidden" name="delete-image" id="delete-image-input" value="0">

                        </form>

                    </div>


                    <div class="container">
                        <div class="user-data">
                            <p data-label="First Name"><span><?php echo $firstname ?></span></p>
                            <p data-label="Last Name"><span><?php echo $lastname ?></span></p>
                            <p data-label="Email"><span><?php echo $email ?></span></p>
                            <p data-label="Role"><span><?php echo $role; ?></span></p>
                            <p data-label="Gender"><span><?php echo $gender; ?></span></p>
                            <p data-label="Country"><span><?php echo $country; ?></span></p>
                            <p data-label="State"><span><?php echo $state; ?></span></p>
                            <p data-label="City"><span><?php echo $city; ?></span> </p>

                        </div>
                    </div>

                    <div class="btn">
                        <button class="submit-btn"> <a href="../updateuser?uid=<?php echo $id; ?>" style="color:white;">
                                Edit</a></button>
                    </div>




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
    function uploadImage(event) {
        const image = event.target.files[0];
        const img = document.getElementById("profile-image")
        const imageUrl = URL.createObjectURL(image);
        img.src = imageUrl;
        document.getElementById('image-form').submit()
    }

    document.getElementById('delete-image').addEventListener('click', function() {
        document.getElementById('delete-image-input').value = '1';
        document.getElementById('image-form').submit();
    });
    </script>


</body>

</html>