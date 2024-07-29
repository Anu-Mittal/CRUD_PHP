<?php
session_start();
$login = false;
include '../connect.php';
include '../email-service.php';


if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    header('Location:../dashboard');
    exit;
}

$firstname = '';
$lastname = '';
$email = '';
$mobile = '';
$gender = '';
$role = '';
$country = '';
$state = '';
$city = '';
$password = '';
$retype = '';

if (isset($_POST['submit'])) {
    $errors = [];
    if (empty($_POST['firstname'])) {
        $errors['firstname'] = "Firstname is required.";
    } else {
        $firstname = $_POST['firstname'];
    }

    if (empty($_POST['lastname'])) {
        $errors['lastname'] = "Lastname is required.";
    } else {
        $lastname = $_POST['lastname'];
    }

    if (empty($_POST['email'])) {
        $errors['email'] = "Email is required.";
    } else {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format.";
        } else {
            $sql_email = "SELECT * FROM `employees` WHERE email='$email'";
            $result_email = mysqli_query($con, $sql_email);

            if ($row1 = mysqli_fetch_array($result_email)) {
                $errors['email'] = "Email already exists.";
            }
        }
    }

    if (empty($_POST['mobile'])) {
        $errors['mobile'] = "Mobile is required.";
    } else {
        $mobile = $_POST['mobile'];
        $pattern = '/^\d{10}$/';
        if (!preg_match($pattern, $mobile)) {
            $errors['mobile'] = "Mobile must be of 10 numbers long.";
        } else {
            $mobile =  $_POST['mobile'];
        }
    }
    if (empty($_POST['gender'])) {
        $errors['gender'] = "Gender must be selected.";
    } else {
        $gender = $_POST['gender'];
    }


    if (empty($_POST['role']) || $_POST['role'] == '') {
        $errors['role'] = "Role must be selected.";
    } else {
        $role = $_POST['role'];
    }

    if (empty($_POST['country']) || $_POST['country'] == '') {
        $errors['country'] = "Country must be selected.";
    } else {
        $country = $_POST['country'];
        // $get_country = "select * from Countries where id=$country";
        // $result =  mysqli_query($con, $get_country);
        // $row = mysqli_fetch_array($result);
        // $country = $row['countrynames'];
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


    if (empty($_POST['password'])) {
        $errors['password'] = "Password is required.";
    } else {
        $password = $_POST['password'];
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()\-_=+{};:,<.>])[A-Za-z\d!@#$%^&*()\-_=+{};:,<.>.]{8,}$/';

        if (!preg_match($pattern, $password)) {
            $errors['password'] = "Password must fulfill all conditions.";
        }
    }

    if (empty($_POST['retype'])) {
        $errors['retype'] = "Confirmation of password is required.";
    } else {
        $retype = $password;
    }


    if (empty($errors)) {
        $mobile = $_POST['mobile'];
        $country = $_POST['country'];
        $state = $_POST['state'];
        $city = $_POST['city'];
        $role = $_POST['role'];
        $createdAt = time();
        $updatedAt = time();


        $sql = "INSERT  into `employees` (firstname,lastname, email,mobile,gender,role_id,country,state,city,password,createdAt,updatedAt)
     values ('$firstname','$lastname','$email','$mobile','$gender','$role','$country','$state','$city','$password','$createdAt','$updatedAt')";

        $result = mysqli_query($con, $sql);
        if ($result) {
            $subject = 'Account Created Successfuly!';
            $name = $row1['firstname'];
            
            $sql = "select * from templates_info where temp_names='signup_mail'";
            $result=mysqli_query($con,$sql);

            $row = mysqli_fetch_assoc($result);
            $body = $row['templates'];

            $body = str_replace("{{name}}",$name,$body);
        
            sendEmail($row1['firstname'],$email, $subject, $body);
            header('Location:../login');
            // echo "Data inserted successfully"
            // $login = true;
            // session_start();
            // $_SESSION['loggedIn'] = true;
            // $_SESSION['email'] = $email;
            //     if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
            //       exit;

            // } 
        } else {
            echo "Error: " . $sql . "<br>" . die(mysqli_error($con));
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
        .wrapper {
            margin-left: 100px;

        }

        .outer_div {
            width: 700px;
        }

        .outer_div select {
            width: 650px;
        }

        .form-group p {
            color: red;
            padding: 8px;
            font-size: 12px;

        }

        .form-group {
            margin-bottom: 3px;

        }

        .radio-row .for-row {
            display: flex;

        }


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
            left: -18%;


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
    </style>

</head>

<body>
    <div class="login_section">
        <div class="wrapper relative">
            <div style="display:none" class="meassage_successful_login">You have Successfull Edit </div>
            <!-- <div class="heading-top">
        <div class="logo-cebter"><a href="#"><img src="images/at your service_banner.png"></a></div>
      </div> -->
            <div class="box">
                <div class="outer_div">

                    <h2> User <span> Sign-Up</span></h2>
                    <?php
                    // if (!empty($errors)) {
                    //     echo "<div class='error-message-div error-msg'><img src='../images/unsucess-msg.png'><strong>Invalid!</strong> username or password</div>";
                    // }
                    ?>

                    <div class="error-message-div error-msg" id="msg" style="display:none;"><img src="../images/unsucess-msg.png"><strong>Invalid!</strong> Details </div>
                    <form name="signupForm" id="myform" class="margin_bottom" method="post" onsubmit="return validateSignup()">


                        <form class="form-edit" name="signupForm" id="myform" method="POST" action="../adduser" onsubmit="return validateForm()">
                            <!-- firstname -->
                            <div class="form-group">
                                <label for="exampleInputEmail1">First Name : <span>*</span></label>
                                <input type="text" id="firstname" class="search-box" name="firstname" placeholder="Enter First Name" value="<?php echo $firstname; ?>" />
                                <p id='firstname-error' class='error'><?php echo (isset($errors['firstname'])) ? $errors['firstname'] : ''; ?></p>

                            </div>
                            <!-- lastname -->
                            <div class="form-group">

                                <label>Last Name : <span>*</span></label>


                                <input type="text" class="search-box" name="lastname" placeholder="Enter Last Name" id="lastname" value="<?php echo $lastname; ?>" />
                                <p id='lastname-error' class='error'><?php echo (isset($errors['lastname'])) ? $errors['lastname'] : ''; ?></p>

                            </div>
                            <!-- email -->
                            <div class="form-group">

                                <label>Email: <span>*</span></label>


                                <input type="email" class="search-box" name="email" placeholder="Enter Email" id="email" value="<?php echo $email; ?>" />
                                <p id='email-error' class='error'><?php echo (isset($errors['email'])) ? $errors['email'] : ''; ?></p>

                            </div>

                            <!-- mobile -->
                            <div class="form-group">

                                <label>Mobile: <span>*</span> </label>


                                <input type="text" class="search-box" name="mobile" placeholder="Enter Mobile Number" id="mobile" value="<?php echo $mobile; ?>" />
                                <p id='mobile-error' class='error'><?php echo (isset($errors['mobile'])) ? $errors['mobile'] : ''; ?></p>

                            </div>
                            <!-- gender -->
                            <div class="form-group radio-row">

                                <label>Gender: <span>*</span> </label>


                                <label><input type="radio" name="gender" value="male" <?php if ($gender == 'male') {
                                                                                            echo "checked";
                                                                                        } ?>> <span class="gender"> Male </span></label>

                                <label> <input type="radio" name="gender" value="female" <?php if ($gender == 'female') {
                                                                                                echo "checked";
                                                                                            } ?>> <span class="gender"> Female </span> </label><br>

                                <p id="gender-error" class="error"><?php echo isset($errors['gender']) ? $errors['gender'] : ''; ?></p>


                            </div>

                            <!-- role -->
                            <div class="form-group">
                                <label>Role: <span>*</span> </label>
                                <div class="select">
                                    <select name="role" class="role-info" id="role">
                                        <option value="">Select Your Role</option>
                                        <?php
                                        $sql1 = "select * from `roles`";
                                        $result1 = mysqli_query($con, $sql1);
                                        while ($row1 = mysqli_fetch_array($result1)) {

                                            if (($role == $row1['id'])) {

                                                echo "<option selected value='$row1[id]'>$row1[role]</option>";
                                            } else {
                                                // if ($row1['id'] == 2) {
                                                //     echo "<option selected value='$row1[id]'>$row1[role]</option>";
                                                // } else {
                                                    echo "<option value='$row1[id]'>$row1[role]</option>";
                                                }
                                            }
                                        // }
                                        ?>
                                    </select>
                                    <p id="role-error" class="error"><?php echo isset($errors['role']) ? $errors['role'] : ''; ?></p>


                                </div>
                            </div>


                            <!-- country -->
                            <div class="form-group">

                                <label>Country: <span>*</span> </label>


                                <div class="select">
                                    <select name="country" class="country-info" id="countryId" onchange="fetchState()">
                                        <option value="">Select Your Country</option>
                                        <?php
                                        $sql1 = "select * from `Countries`";
                                        $result1 = mysqli_query($con, $sql1);
                                        while ($row1 = mysqli_fetch_array($result1)) {
                                            if ($country == $row1['id']) {
                                                echo "<option selected value='$row1[id]'>$row1[countrynames]</option>";
                                            } else {
                                                echo "<option value='$row1[id]'>$row1[countrynames]</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <p id="country-error" class="error"><?php echo isset($errors['country']) ? $errors['country'] : ''; ?></p>


                                </div>
                            </div>

                            <!-- state -->
                            <div class="form-group">

                                <label>State: <span>*</span> </label>


                                <div class="select">
                                    <select disabled name="state" class="state-info" id="stateId" onchange="fetchCity()">
                                        <option value="<?php echo $state ?>">Select Your State</option>
                                        <?php
                                        $sql1 = "select * from `States`";
                                        $result1 = mysqli_query($con, $sql1);
                                        while ($row1 = mysqli_fetch_array($result1)) {
                                            if ($state == $row1['id']) {
                                                echo "<option selected value='$row1[id]'>$row1[statenames]</option>";
                                            } else {
                                                echo "<option value='$row1[id]'>$row1[statenames]</option>";
                                            }
                                        }
                                        ?>

                                    </select>
                                    <p id="state-error" class="error"><?php echo isset($errors['state']) ? $errors['state'] : ''; ?></p>


                                </div>
                            </div>

                            <!-- city -->
                            <div class="form-group">

                                <label>City: <span>*</span> </label>


                                <div class="select">
                                    <select disabled name="city" class="city-info" id="cityId">
                                        <option value="">Select Your City</option>
                                        <?php
                                        $sql1 = "select * from `Cities`";
                                        $result1 = mysqli_query($con, $sql1);
                                        while ($row1 = mysqli_fetch_array($result1)) {
                                            if ($city == $row1['id']) {
                                                echo "<option selected value='$row1[id]'>$row1[citynames]</option>";
                                            } else {
                                                echo "<option value='$row1[id]'>$row1[citynames]</option>";
                                            }
                                        }
                                        ?>

                                    </select>
                                    <p id="city-error" class="error"><?php echo isset($errors['city']) ? $errors['city'] : ''; ?></p>
                                </div>


                            </div>

                            <!-- password -->
                            <div class="form-group tooltip">
                                <label>Password: <span>*</span></label>
                                <input type="password" class="search-box pass_input" name="password" placeholder="Enter Password" id="password" onkeyup="validatePassword()" value="<?php echo $password; ?>" />
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

                                <label>Password: <span>*</span></label>

                                <input type="password" class="search-box" name="password" placeholder="Enter Password" id="password" value="<?php echo $password; ?>" />
                                <span class="tooltiptext">Password must be at least 8 characters long and include at least one lowercase letter, one uppercase letter, one numeric digit, and one special character.</span>
                                <p id='password-error' class='error'><?php echo (isset($errors['password'])) ? $errors['password'] : ''; ?></p>

                            </div> -->

                            <!--Retype password -->
                            <div class="form-group">

                                <label>Retype Password: <span>*</span></label>

                                <input type="password" class="search-box" name="retype" placeholder="Re-type Password" id="retype" value="<?php echo $retype; ?>" />
                                <p id='retype-error' class='error'><?php echo (isset($errors['retype'])) ? $errors['retype'] : ''; ?></p>

                            </div>

                            <!--save button -->
                            <button type="submit" name="submit" class="btn_login">Sign-Up</button>



                            <!-- for login -->
                            <div style=" padding:10px;padding-top: 10px;">
                                <h5><span>Already Have an account? </span><a href="../login" style="color: blue;">Login</a>
                                    <h5>
                            </div>
                        </form>


                </div>
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
                            // stateSelect.innerHTML = '';
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
                            // let option = document.createElement('option');
                            // option.textContent ="Select Your City";
                            // option.value ="";
                            // citySelect.appendChild(option);
                            // option = null
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
            //validations   
            function validateSignup() {
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
                    document.getElementById("mobile-error").innerText = "Mobile must be of 10 numbers long";
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
                    document.getElementById("password-error").innerText = "Password must be at least 8 characters long and include at least one lowercase letter, one uppercase letter, one numeric digit, and one special character";
                    isValid = false;
                }

                if (retype == "") {
                    document.getElementById("retype-error").innerText = " Confirmation of password is required.";
                    isValid = false;
                } else if (retype != password) {
                    document.getElementById("retype-error").innerText = "It is not matched with above written password ";
                    isValid = false;
                }

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