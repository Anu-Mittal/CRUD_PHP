<?php
include '../connect.php';

session_start();
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true) {
    header('Location:../login');

    exit;
}

$id = $_SESSION['id'];
// echo $_SESSION['id'];
$email = $_SESSION['email'];

$image_path = null;

$sql = "SELECT user_id,user_first_name,user_last_name,user_email,user_gender,user_country_id,em_countries.country_name,user_state_id,em_states.state_name,user_city_id,em_cities.city_name,user_phone,em_roles.role_name,user_image FROM `em_users` left join em_roles on em_roles.role_id=em_users.user_role_id left join em_countries on em_users.user_country_id=em_countries.country_id left join em_states on em_users.user_state_id=em_states.state_id left join em_cities on em_users.user_city_id=em_cities.city_id where user_isDeleted=0 and em_users.user_id=$id";


$result = mysqli_query($con, $sql);

$row = mysqli_fetch_assoc($result);

// $id1=$row['Id'];
$firstname = $_SESSION['firstname'];
$lastname = $row['user_last_name'];
// $email = $row['email'];
$mobile = $row['user_phone'];
$gender = $row['user_gender'];
$country = $row['country_name'];
// $get_country = "select * from Countries where id=$country";
//         $result =  mysqli_query($con, $get_country);
//         $row = mysqli_fetch_array($result);
//         $country = $row['countrynames'];
$image_path = $row['user_image'];
$state = $row['state_name'];
$city = $row['city_name'];
// $password = $row['password'];
// $retype=$row['retype'];
$role = $row['role_name'];



// if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile'])) {
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['profile'])) {
        $upload_dir = '../img/';
        $file = $_FILES['profile'];

        if (file_exists($upload_dir . $image_path && $image_path)) {
            unlink($upload_dir . $image_path);
        }


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
                $sql = "UPDATE em_users SET image = '$new_file_name' WHERE user_id = $id";

                $result = mysqli_query($con, $sql);

                // Execute the query
                if ($result) {
                    $image_path = $new_file_name;
                    //   unlink($upload_dir . $image_to_delete);
                }
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
        if (file_exists($upload_dir . $image_to_delete)) {
            unlink($upload_dir . $image_to_delete);
        }
        //   unlink(../img/);
        $image_path = null;
        header('location:../updateprofile');
    } else {
        die(mysqli_error($con));
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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



        .pop-up {
            display: none;
            position: absolute;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            justify-content: center;
            align-items: center;
            z-index: 1;
            background-color: rgba(0, 0, 0, 0.151);
            border: none;
        }

        .pop-up .box {
            box-shadow: -2px 2px 20px 3px rgba(0, 0, 0, 0.2);
            height: 150px;
            padding: 26px;
            background-color: white;
            border-radius: 10px;
            font-size: 12px;

            text-align: center;

        }

        .box .buttons {
            display: flex;
            justify-content: space-evenly;
            height: 53px;
            padding-top: 20px;


        }
        #dlt:hover{
            cursor:pointer;
        }

        .buttons #cancel {
            border: none;
            background-color: #ff651b;
            color: white;
            padding: 0px 16px;
            border-radius: 5px;
            font-size: 14px;

        }

        .buttons #dlt {
            background-color: red;
            width: 65px;
            color: white;
            font-size: 14px;
            border-radius: 5px;
            padding: 7px 54px 6px 16;
        }

        .delete:hover {
            cursor: pointer;
        }

        #cancel:hover {
            cursor: pointer;
        }


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

    <!-- delete confirmation -->
    <div class="pop-up" id="modal">
        <div class="box">
            <h2> Would You like to Delete this record? </h2>
            <div class="buttons">
                <button id="cancel" onclick="return samePage()">Cancel</button>
                <a id="dlt">Delete</a>
            </div>
        </div>
    </div>
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
                        <img src=<?php echo $image_path ? '../img/' . $image_path : '../images/userr.png'; ?> alt="img" id="profile-image">
                        <form id="image-form" method="POST" enctype="multipart/form-data">
                            <div class="edit-btn">
                                <i class="fa-solid fa-pencil"></i>
                                <input type="file" name="profile" id="select-file" onchange="uploadImage(event)">
                            </div>
                            <!-- <input type="submit" value="Upload"> -->
                            <button type="button" class="delete-icon delete" id="delete-image" onclick=myFunction()>
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            <input type="hidden" name="delete-image" id="delete-image-input" value="0">

                        </form>

                    </div>


                    <div class="container">
                        <div class="user-data">
                            <p data-label="First Name"><span><?php echo $_SESSION['firstname'] ?></span></p>
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
        //FOR DELETE CONFIRMATION
        function myFunction() {
            // console.log("hello");
            document.getElementById('modal').style.display = "flex";
            document.getElementById('dlt').addEventListener('click', function() {
                document.getElementById('delete-image-input').value = '1';
                document.getElementById('image-form').submit();
            });
        }

        function samePage() {
            document.getElementById('modal').style.display = "none";
            document.getElementById('cancel').href = "/updateprofile";

        }



        // document.getElementById('delete-image').addEventListener('click', function() {
        //     document.getElementById('delete-image-input').value = '1';
        //     document.getElementById('image-form').submit();
        // });
    </script>


</body>

</html>