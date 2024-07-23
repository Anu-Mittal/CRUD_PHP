<div class="header">
    <div class="wrapper">
        <div class="logo"><a href="#"><img src="images/logo.png"></a></div>


        <div class="right_side">
            <ul>
                <li><a href="profile-edit.php"><?php echo $_SESSION['firstname']; ?></a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </div>
        <div class="nav_top">
            <ul>
                <li ><a href="dashboard.php">Dashboard</a></li>
                <li><a href="list-users.php">Users</a></li>
                <li><a href=" agentloclist.php ">Setting</a></li>
                <li><a href=" geoloclist.php ">Configuration</a></li>
            </ul>

        </div>
    </div>
</div>
<script>
	console.log(window.location.pathname);
</script>