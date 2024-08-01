<div class="header">
    <div class="wrapper">
        <div class="logo"><img src="../images/logo.png"></div>


        <div class="right_side">
            <ul>
                <li><a href="../updateprofile"><?php echo $_SESSION['firstname']; ?></a></li>
                <li><a href="../logout.php">Log Out</a></li>
            </ul>
        </div>
        <div class="nav_top">
            <ul>
                <li id="dashboard"><a href="../dashboard" >Dashboard</a></li>
                <li  id="listusers"><a href="../listusers">Users</a></li>
                <!-- <li><a href=" agentloclist.php ">Setting</a></li>
                <li><a href=" geoloclist.php ">Configuration</a></li> -->
            </ul>

        </div>
    </div>
</div>
<script>
	console.log(window.location.pathname);
    if(window.location.pathname.includes("dashboard"))
   {document.getElementById('dashboard').className = 'active';}
   else if(window.location.pathname.includes('listusers')){
    document.getElementById('listusers').className = 'active';
   }

</script>