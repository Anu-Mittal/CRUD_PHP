<?php
include 'connect.php';
session_start();
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true) {
  header('Location:login.php');
  exit;
}
$id=$_SESSION['id'];
$query= "select role_id from employees where Id=$id";
$result = mysqli_query($con, $query);
$row=mysqli_fetch_assoc($result);
if($row['role_id']!= 1  && $row['role_id']!= 5 ){
	header("Location:dashboard.php");
	exit;
}

//****************** pagination*************************
$search='';
//define total number of results you want per page  
$row_per_page = 4;   //limit
//find the total number of results stored in the database  
$query = "SELECT employees.Id,firstname,lastname,email,mobile,role_id,roles.role,roles.id FROM `employees` left join roles on roles.id=employees.role_id where isDeleted=0";
$result = mysqli_query($con, $query);
$row=mysqli_fetch_assoc($result);
$total_rows = mysqli_num_rows($result);
$role=$row['role_id'];




//determine the total number of pages available  
$number_of_page = ceil($total_rows / $row_per_page);

//determine which page number visitor is currently on  
if (!isset($_GET['page'])) {
	$page = 1;
} else {
	$page = $_GET['page'];
}
//determine the sql LIMIT starting number for the results on the displaying page  
$first_row = ($page - 1) * $row_per_page;    //offset

//****************** pagination end*************************


// ******************SORTING************************************

// Sorting Part
$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'Id';
$sort_order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'desc' : 'ASC';
$valid_columns = ['Id', 'firstname', 'lastname', 'email', 'mobile', 'role'];

if (!in_array($sort_column, $valid_columns)) {
	$sort_column = 'Id'; // Default sorting column
}
// ******************SORTING END********************************
// ******************SEARCHING********************************                                                                            

//Searching Part
$search = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
	$search = $_GET['search'];
}
// ******************SEARCHING END ********************************    


// ******************Combination of searching sorting pagination*******************************
// SQL Query with Pagination, Sorting, and Searching
$sql = "SELECT employees.Id,firstname,lastname,email,mobile,roles.role FROM `employees` left join roles on roles.id=employees.role_id where isDeleted=0 ";

if (!empty($search)) {
	$sql .= " AND (firstname LIKE '%$search%' OR lastname LIKE '%$search%' OR email LIKE '%$search%' OR mobile LIKE '%$search%' Or role like '%$search%')";

	$search_count = mysqli_query($con,$sql);

	$total_rows = mysqli_num_rows($search_count);
	$number_of_page = ceil($total_rows / $row_per_page);
}



// ******************Combination of searching sorting pagination end*******************************




?>

<!-- html -->
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin</title>

	<!-- Bootstrap -->
	<link href="css/dashboard.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<style>
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
		
	</style>
</head>

<body>
	<div class="pop-up" id="modal">
		<div class="box">
			<h2> Would You like to Delete this record? </h2>
			<div class="buttons" >
				<button id="cancel" onclick="return samePage()">Cancel</button>
				<a id="dlt" >Delete</a>
			</div>
		</div>
	</div>
	<?php include "header.php" ?>
	<div class="clear"></div>
	<div class="clear"></div>
	<div class="content">
		<div class="wrapper">
			<div class="bedcram">
				<ul>
					<li><a href="dashboard.php">Home</a></li>
					<li>List Users</li>
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
				<h1>List Users</h1>
				<div class="list-contet">
					<div class="form-left">
						<div class="form">

							<form role="form">
								<label>Sort By : </label>
								<div class="select">
									<select name="sort" onchange="sortSelect()" id="sortbyname">
										<option <?php echo 'firstname' == $sort_column ? 'selected' : ''; ?> value="firstname">First Name</option>
										<option <?php echo 'lastname' == $sort_column ? 'selected' : ''; ?> value="lastname">Last Name</option>
										<option <?php echo 'email' == $sort_column ? 'selected' : ''; ?> value="email">E-Mail</option>
										<option <?php echo 'role' == $sort_column ? 'selected' : ''; ?> value="role">Roles</option>

									</select>

								</div>
								<input type="text" class="search-box search-upper" placeholder="Search.." name="search" value="<?php echo htmlspecialchars($search); ?>" />
								<!-- <input type="hidden" name="sort" value=">
						 -->

								<input type="submit" class="submit-btn" value="Search">
							</form>
						</div>
						<a href="add-user.php" class="submit-btn add-user">Add More Users</a>
					</div>
					<table width="100%" cellspacing="0">

						<tbody>
							<tr class="table-heading">
								<th width="10px">S.no</th>
								<th width="98px"><a href="list-users.php?page=<?php echo $page; ?>&sort=firstname&order=<?php echo $sort_column == 'firstname' && $sort_order == 'ASC' ? 'desc' : 'asc'; ?>&search=<?php echo htmlspecialchars($search); ?>">First Name</a></th>
								<th width="100px"><a href="list-users.php?page=<?php echo $page; ?>&sort=lastname&order=<?php echo $sort_column == 'lastname' && $sort_order == 'ASC' ? 'desc' : 'asc'; ?>&search=<?php echo htmlspecialchars($search); ?>">Last Name</a></th>
								<th width="113px"><a href="list-users.php?page=<?php echo $page; ?>&sort=email&order=<?php echo $sort_column == 'email' && $sort_order == 'ASC' ? 'desc' : 'asc'; ?>&search=<?php echo htmlspecialchars($search); ?>">E-Mail</a></th>
								<th width="113px"><a href="list-users.php?page=<?php echo $page; ?>&sort=mobile&order=<?php echo $sort_column == 'mobile' && $sort_order == 'ASC' ? 'desc' : 'asc'; ?>&search=<?php echo htmlspecialchars($search); ?>">Mobile</a></th>
								<th width="97px"><a href="list-users.php?page=<?php echo $page; ?>&sort=role&order=<?php echo $sort_column == 'role' && $sort_order == 'ASC' ? 'desc' : 'asc'; ?>&search=<?php echo htmlspecialchars($search); ?>">Roles</a></th>
								<th width="126px">Operations</th>
							</tr>


							<?php

							$sql = "SELECT employees.Id,firstname,lastname,email,mobile,roles.role FROM `employees` left join roles on roles.id=employees.role_id where isDeleted=0  and (firstname LIKE '%$search%' or lastname LIKE '%$search%' or email LIKE '%$search%' or mobile LIKE '%$search%' or roles.role Like '%$search%' ) ORDER BY $sort_column $sort_order limit $row_per_page offset $first_row";

							$result = mysqli_query($con, $sql);
							$i = 1 + $first_row;

							if (mysqli_num_rows($result)>0) {
								while ($row = mysqli_fetch_assoc($result)) {
									$id = $row['Id'];
									$firstname = $row['firstname'];
									$lastname = $row['lastname'];
									$email = $row['email'];
									$mobile = $row['mobile'];
									$role = $row['role'];


									echo "
                            <tr>
                            <td>" . $i++ . "</td>
                            <td>" . $firstname . "</td>
                            <td>" . $lastname . "</td>
                            <td>" . $email . "</td>
							<td>" . $mobile . "</td>
							<td>" . $role . "</td>
							<td>
                            <a href ='edit-user.php?uid=$id' class='update' style='margin-left:10px;padding-left:10px;color:#FF651B;width:10px;gap:15px; align-content:center; '><i class='fa-solid fa-pencil'></i></a>
                            <a onclick='myFunction($id)' class='delete' style='border:none;color:red;width:10px;'><i class='fa-solid fa-xmark'></i></a>
                            </td>
                            </tr>";
								}
							}
							?>
						</tbody>
					</table>

					<!--**********************Pagination*************-->
					<div class="paginaton-div">
						<?php
						if ($page > 1) {
							echo '<a class="a-no"  href="?page=' . ($page - 1) . '&sort=' . $sort_column . '&order=' . htmlspecialchars($sort_order) . '&search=' . htmlspecialchars($search) . '"> Prev</a>';
						} else {
							echo "<a class='disable'>&laquo; Previous</a>";
						}
						for ($i = 1; $i <= $number_of_page; $i++) {
							if ($i == $page) {
								echo '<a class="a-no active" href="list-users.php?page=' . $i . '&sort=' . $sort_column . '&order=' . htmlspecialchars($sort_order) . '&search=' . htmlspecialchars($search) . '">' . $i . ' </a>';
							} else {

								echo '<a class="a-no" href="list-users.php?page=' . $i . '&sort=' . $sort_column . '&order=' . htmlspecialchars($sort_order) . '&search=' . htmlspecialchars($search) . '">' . $i . ' </a>';
							}
						}
						if ($page < $number_of_page) {
							echo '<a class="a-no" href="?page=' . ($page + 1) . '&sort=' . $sort_column . '&order=' . htmlspecialchars($sort_order) . '&search=' . htmlspecialchars($search) . '"> Next </a>';
						} else {
							echo "<a class='disable'> Next &raquo;</a>";
						}
						?>
						<!-- <ul>
							<li><a href="#">Prev</a></li>
							<li><a href="#" class="active">1</a></li>
							<li><a href="#">2</a></li>
							<li><a href="#">3</a></li>
							<li><a href="#">Next</a></li>


						</ul> -->
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
		function sortSelect() {
			var getname = document.getElementById('sortbyname').value;
			window.location.href = "http://localhost/dashboard/HTML1/list-users.php?page=<?php echo $page; ?>&sort=" + getname + "&order=<?php echo $sort_column == 'getname' && $sort_order == 'ASC' ? 'desc' : 'asc'; ?>&search=<?php echo htmlspecialchars($search); ?>";
		}
		//FOR DELETE CONFIRMATION
		function myFunction(id) {

			document.getElementById('modal').style.display = "flex";
			document.getElementById('dlt').href = "delete1.php?id=" + id;
		}

		function samePage() {
			document.getElementById('modal').style.display = "none";
			document.getElementById('cancel').href = "list-users.php";

		}
	</script>
</body>
<!-- <tr>
						<td>1</td>
						<td>TestBruc3</td>
						<td>Test99</td>
						<td>Test99</td>
						<td>nigroid@h..</td>
						<td>Principal</td>
									<td class="payment">
						<img src="images/cross.png">
						</td>
						<td>
							<a href="#"><img src="images/edit-icon.png"></a>
							<a href="#"><img src="images/cross.png"></a>
							<a href="#"><img src="images/correct.png"></a>
							<a href="#"><img src="images/view.png"></a>
							</td>
						
				  </tr>
				  <tr>
						<td>2</td>
						<td>TestBruc3</td>
						<td>Test99</td>
						<td>Test99</td>
						<td>nigroid@h..</td>
						<td>Principal</td>
									<td class="payment">
						<img src="images/cross.png">
						</td>
						<td>
							<a href="#"><img src="images/edit-icon.png"></a>
							<a href="#"><img src="images/cross.png"></a>
							<a href="#"><img src="images/correct.png"></a>
							<a href="#"><img src="images/view.png"></a>
							</td>
						
				  </tr>
					<tr>
						<td>3</td>
						<td>TestBruc3</td>
						<td>Test99</td>
						<td>Test99</td>
						<td>nigroid@h..</td>
						<td>Principal</td>
									<td class="payment">
						<img src="images/cross.png">
						</td>
						<td>
							<a href="#"><img src="images/edit-icon.png"></a>
							<a href="#"><img src="images/cross.png"></a>
							<a href="#"><img src="images/correct.png"></a>
							<a href="#"><img src="images/view.png"></a>
							</td>
						
				  </tr>
					<tr>
						<td>4</td>
						<td>TestBruc3</td>
						<td>Test99</td>
						<td>Test99</td>
						<td>nigroid@h..</td>
						<td>Principal</td>
									<td class="payment">
						<img src="images/cross.png">
						</td>
						<td>
							<a href="#"><img src="images/edit-icon.png"></a>
							<a href="#"><img src="images/cross.png"></a>
							<a href="#"><img src="images/correct.png"></a>
							<a href="#"><img src="images/view.png"></a>
							</td>
						
				  </tr>
					<tr>
						<td>5</td>
						<td>TestBruc3</td>
						<td>Test99</td>
						<td>Test99</td>
						<td>nigroid@h..</td>
						<td>Principal</td>
									<td class="payment">
						<img src="images/cross.png">
						</td>
						<td>
							<a href="#"><img src="images/edit-icon.png"></a>
							<a href="#"><img src="images/cross.png"></a>
							<a href="#"><img src="images/correct.png"></a>
							<a href="#"><img src="images/view.png"></a>
							</td>
						
				  </tr>
				<tr>
						<td>6</td>
						<td>TestBruc3</td>
						<td>Test99</td>
						<td>Test99</td>
						<td>nigroid@h..</td>
						<td>Principal</td>
									<td class="payment">
						<img src="images/cross.png">
						</td>
						<td>
							<a href="#"><img src="images/edit-icon.png"></a>
							<a href="#"><img src="images/cross.png"></a>
							<a href="#"><img src="images/correct.png"></a>
							<a href="#"><img src="images/view.png"></a>
							</td>
						
				  </tr>
					<tr>
						<td>7</td>
						<td>TestBruc3</td>
						<td>Test99</td>
						<td>Test99</td>
						<td>nigroid@h..</td>
						<td>Principal</td>
									<td class="payment">
						<img src="images/cross.png">
						</td>
						<td>
							<a href="#"><img src="images/edit-icon.png"></a>
							<a href="#"><img src="images/cross.png"></a>
							<a href="#"><img src="images/correct.png"></a>
							<a href="#"><img src="images/view.png"></a>
							</td>
						
				  </tr>
				<tr>
						<td>8</td>
						<td>TestBruc3</td>
						<td>Test99</td>
						<td>Test99</td>
						<td>nigroid@h..</td>
						<td>Principal</td>
									<td class="payment">
						<img src="images/cross.png">
						</td>
						<td>
							<a href="#"><img src="images/edit-icon.png"></a>
							<a href="#"><img src="images/cross.png"></a>
							<a href="#"><img src="images/correct.png"></a>
							<a href="#"><img src="images/view.png"></a>
							</td>
						
				  </tr>
				<tr>
						<td>9</td>
						<td>TestBruc3</td>
						<td>Test99</td>
						<td>Test99</td>
						<td>nigroid@h..</td>
						<td>Principal</td>
									<td class="payment">
						<img src="images/cross.png">
						</td>
						<td>
							<a href="#"><img src="images/edit-icon.png"></a>
							<a href="#"><img src="images/cross.png"></a>
							<a href="#"><img src="images/correct.png"></a>
							<a href="#"><img src="images/view.png"></a>
							</td>
						
				  </tr>
				<tr>
						<td>10</td>
						<td>TestBruc3</td>
						<td>Test99</td>
						<td>Test99</td>
						<td>nigroid@h..</td>
						<td>Principal</td>
									<td class="payment">
						<img src="images/cross.png">
						</td>
						<td>
							<a href="#"><img src="images/edit-icon.png"></a>
							<a href="#"><img src="images/cross.png"></a>
							<a href="#"><img src="images/correct.png"></a>
							<a href="#"><img src="images/view.png"></a>
							</td>
						
				  </tr> -->

</html>