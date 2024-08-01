<?php
include '../connect.php';
session_start();
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true) {
	header('Location:../login');
	exit;
}

$email = $_SESSION['email'];

// week -> (60*60*24*7)
//  day -> (60*60*24)
// month -> (60*60*24*30)




// $a = array("month", "week", "day");
// (array_map("myfunction", $a));

// $grp = 'month';

// if (isset($_POST['grp'])) {
// 	$grp = $_POST['grp'];
// }


$grp = isset($_GET['grp']) ? $_GET['grp'] : 'month';

$time = time();
$timeFilter = "";

switch ($grp) {
	case 'day':
		$timeFilter = "AND user_createdAt >= $time - (60*60*24)";
		break;
	case 'week':
		$timeFilter = "AND user_createdAt >= $time - (60*60*24*7)";
		break;
	case 'month':
		$timeFilter = "AND user_createdAt >= $time - (60*60*24*30)";
		break;
	default:
		$timeFilter = "";
}
// Queries for graph
//for weeks
$week4 = "SELECT * FROM em_users WHERE user_isDeleted = 0 AND user_createdAt BETWEEN $time-(60*60*24*7) AND $time $timeFilter";
$result4 = mysqli_query($con, $week4);
$w4 = mysqli_num_rows($result4);

$week3 = "SELECT * FROM em_users WHERE user_isDeleted = 0 AND user_createdAt BETWEEN $time-(60*60*24*14) AND $time-(60*60*24*7) $timeFilter";
$result3 = mysqli_query($con, $week3);
$w3 = mysqli_num_rows($result3);

$week2 = "SELECT * FROM em_users WHERE user_isDeleted = 0 AND user_createdAt BETWEEN $time-(60*60*24*21) AND $time-(60*60*24*14) $timeFilter";
$result2 = mysqli_query($con, $week2);
$w2 = mysqli_num_rows($result2);

$week1 = "SELECT * FROM em_users WHERE user_isDeleted = 0 AND user_createdAt BETWEEN $time-(60*60*24*28) AND $time-(60*60*24*21) $timeFilter";
$result1 = mysqli_query($con, $week1);
$w1 = mysqli_num_rows($result1);


////for days
$day1 = "SELECT * FROM em_users  WHERE user_isDeleted = 0 AND user_createdAt BETWEEN $time-(60*60*24*7) AND $time-(60*60*24*6) $timeFilter";
$r1 = mysqli_query($con, $day1);
$d1 = mysqli_num_rows($r1);

$day2 = "SELECT * FROM em_users WHERE user_isDeleted = 0 AND user_createdAt BETWEEN $time-(60*60*24*6) AND $time-(60*60*24*5) $timeFilter";
$r2 = mysqli_query($con, $day2);
$d2 = mysqli_num_rows($r2);

$day3 = "SELECT * FROM em_users  WHERE user_isDeleted = 0 AND user_createdAt BETWEEN $time-(60*60*24*5) AND $time-(60*60*24*4) $timeFilter";
$r3 = mysqli_query($con, $day3);
$d3 = mysqli_num_rows($r3);

$day4 = "SELECT * FROM em_users  WHERE user_isDeleted = 0 AND user_createdAt BETWEEN $time-(60*60*24*4) AND $time-(60*60*24*3) $timeFilter";
$r4 = mysqli_query($con, $day4);
$d4 = mysqli_num_rows($r4);
$day5 = "SELECT * FROM em_users  WHERE user_isDeleted = 0 AND user_createdAt BETWEEN $time-(60*60*24*3) AND $time-(60*60*24*2) $timeFilter";
$r5 = mysqli_query($con, $day5);
$d5 = mysqli_num_rows($r5);

$day6 = "SELECT * FROM em_users  WHERE user_isDeleted = 0 AND user_createdAt BETWEEN $time-(60*60*24*2) AND $time-(60*60*24*1) $timeFilter";
$r6 = mysqli_query($con, $day6);
$d6 = mysqli_num_rows($r6);

$day7 = "SELECT * FROM em_users  WHERE user_isDeleted = 0 AND user_createdAt BETWEEN $time-(60*60*24) AND $time $timeFilter";
$r7 = mysqli_query($con, $day7);
$d7 = mysqli_num_rows($r7);

//queries for 1 day 
$day_time = strtotime('today midnight');
$time1 = "SELECT * FROM em_users WHERE user_isDeleted = 0 AND user_createdAt BETWEEN $day_time AND $day_time+(60*60*4) $timeFilter";
$rslt1 = mysqli_query($con, $time1);
$t1 = mysqli_num_rows($rslt1);

$time2 = "SELECT * FROM em_users WHERE user_isDeleted = 0 AND user_createdAt BETWEEN $day_time+(60*60*4) AND $day_time+(60*60*8) $timeFilter";
$rslt2 = mysqli_query($con, $time2);
$t2 = mysqli_num_rows($rslt2);

$time3 = "SELECT * FROM em_users WHERE user_isDeleted = 0 AND user_createdAt BETWEEN $day_time+(60*60*8) AND $day_time+(60*60*12) $timeFilter";
$rslt3 = mysqli_query($con, $time3);
$t3 = mysqli_num_rows($rslt3);

$time4 = "SELECT * FROM em_users WHERE user_isDeleted = 0 AND user_createdAt BETWEEN $day_time+(60*60*12) AND $day_time+(60*60*16) $timeFilter";
$rslt4 = mysqli_query($con, $time4);
$t4 = mysqli_num_rows($rslt4);

$time5 = "SELECT * FROM em_users WHERE user_isDeleted = 0 AND user_createdAt BETWEEN $day_time+(60*60*16) AND $day_time+(60*60*20) $timeFilter";
$rslt5 = mysqli_query($con, $time5);
$t5 = mysqli_num_rows($rslt5);

$time6 = "SELECT * FROM em_users WHERE user_isDeleted = 0 AND user_createdAt BETWEEN $day_time+(60*60*20) AND $day_time+(60*60*24) $timeFilter";
$rslt6 = mysqli_query($con, $time6);
$t6 = mysqli_num_rows($rslt6);


// Queries for pie chart
$male = "SELECT * FROM em_users  WHERE user_gender = 'male' $timeFilter";
$result5 = mysqli_query($con, $male);
$m = mysqli_num_rows($result5);

$female = "SELECT * FROM em_users  WHERE user_gender='female' $timeFilter";
$result6 = mysqli_query($con, $female);
$f = mysqli_num_rows($result6);


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

	<!-- pie chart style -->

	<style>
		/* :root {
			position: absolute;
			top: 0;
			left: 0;
			padding: 0;
			overflow: hidden;
			font-family: -apple-system, "system-ui", "Segoe UI", Roboto,
				"Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif,
				"Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol",
				"Noto Color Emoji";
		}

		:root[data-dark-mode="true"] {
			background: #192232;
			color: white;
		}

		:root,
		body {
			height: 100%;
			width: 100%;
			margin: 0;
			box-sizing: border-box;
		}

		body {
			display: grid;
			grid-auto-rows: minmax(0, 1fr);
			grid-auto-columns: minmax(0, 1fr);
			padding: 1rem;
		} */

		button:not(#myGrid button,
			#myChart1 button,
			button[class*="ag-"],
			.ag-chart-context-menu button) {
			--background-color: transparent;
			--text-color: #212529;
			--color-border-primary: #d0d5dd;
			--hover-background-color: rgba(0, 0, 0, 0.1);

			appearance: none;
			border: 1px solid var(--color-border-primary);
			border-radius: 6px;
			height: 36px;
			color: var(--text-color);
			background-color: var(--background-color);
			cursor: pointer;
			display: inline-block;
			font-size: 14px;
			font-weight: 500;
			letter-spacing: 0.01em;
			padding: 0.375em 1em 0.5em;
			white-space: nowrap;
			margin-right: 6px;
			margin-bottom: 8px;
			transition: background-color 0.25s ease-in-out;
		}

		button:not(#myGrid button,
			#myChart1 button,
			button[class*="ag-"],
			.ag-chart-context-menu button):hover {
			background-color: var(--hover-background-color);
		}

		:root[data-dark-mode="true"] button:not(#myGrid button,
			#myChart1 button,
			button[class*="ag-"],
			.ag-chart-context-menu button) {
			--text-color: #f8f9fa;
			--color-border-primary: rgba(255, 255, 255, 0.2);
			--hover-background-color: #2a343e;
		}



		/* bar graph style */

		/* :root {
        position: absolute;
        top: 0;
        left: 0;
        padding: 0;
        overflow: hidden;
        font-family: -apple-system, "system-ui", "Segoe UI", Roboto,
          "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif,
          "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol",
          "Noto Color Emoji";
      }

      :root[data-dark-mode="true"] {
        background: #192232;
        color: white;
      }

      :root,
      body {
        height: 100%;
        width: 100%;
        margin: 0;
        box-sizing: border-box;
      }

      body {
        display: grid;
        grid-auto-rows: minmax(0, 1fr);
        grid-auto-columns: minmax(0, 1fr);
        padding: 1rem;
      } */

		button:not(#myGrid button,
			#myChart1 button,
			button[class*="ag-"],
			.ag-chart-context-menu button) {
			--background-color: transparent;
			--text-color: #212529;
			--color-border-primary: #d0d5dd;
			--hover-background-color: rgba(0, 0, 0, 0.1);

			appearance: none;
			border: 1px solid var(--color-border-primary);
			border-radius: 6px;
			height: 36px;
			color: var(--text-color);
			background-color: var(--background-color);
			cursor: pointer;
			display: inline-block;
			font-size: 14px;
			font-weight: 500;
			letter-spacing: 0.01em;
			padding: 0.375em 1em 0.5em;
			white-space: nowrap;
			margin-right: 6px;
			margin-bottom: 8px;
			transition: background-color 0.25s ease-in-out;
		}

		button:not(#myGrid button,
			#myChart1 button,
			button[class*="ag-"],
			.ag-chart-context-menu button):hover {
			background-color: var(--hover-background-color);
		}

		:root[data-dark-mode="true"] button:not(#myGrid button,
			#myChart1 button,
			button[class*="ag-"],
			.ag-chart-context-menu button) {
			--text-color: #f8f9fa;
			--color-border-primary: rgba(255, 255, 255, 0.2);
			--hover-background-color: #2a343e;
		}

		/* line graph */
		/* :root {
        position: absolute;
        top: 0;
        left: 0;
        padding: 0;
        overflow: hidden;
        font-family: -apple-system, "system-ui", "Segoe UI", Roboto,
          "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif,
          "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol",
          "Noto Color Emoji";
      }

      :root[data-dark-mode="true"] {
        background: #192232;
        color: white;
      }

      :root,
      body {
        height: 100%;
        width: 100%;
        margin: 0;
        box-sizing: border-box;
      }

      body {
        display: grid;
        grid-auto-rows: minmax(0, 1fr);
        grid-auto-columns: minmax(0, 1fr);
        padding: 1rem;
      } */

		button:not(#myGrid button,
			#myChart2 button,
			button[class*="ag-"],
			.ag-chart-context-menu button) {
			--background-color: transparent;
			--text-color: #212529;
			--color-border-primary: #d0d5dd;
			--hover-background-color: rgba(0, 0, 0, 0.1);

			appearance: none;
			border: 1px solid var(--color-border-primary);
			border-radius: 6px;
			height: 36px;
			color: var(--text-color);
			background-color: var(--background-color);
			cursor: pointer;
			display: inline-block;
			font-size: 14px;
			font-weight: 500;
			letter-spacing: 0.01em;
			padding: 0.375em 1em 0.5em;
			white-space: nowrap;
			margin-right: 6px;
			margin-bottom: 8px;
			transition: background-color 0.25s ease-in-out;
		}

		button:not(#myGrid button,
			#myChart2 button,
			button[class*="ag-"],
			.ag-chart-context-menu button):hover {
			background-color: var(--hover-background-color);
		}

		:root[data-dark-mode="true"] button:not(#myGrid button,
			#myChart2 button,
			button[class*="ag-"],
			.ag-chart-context-menu button) {
			--text-color: #f8f9fa;
			--color-border-primary: rgba(255, 255, 255, 0.2);
			--hover-background-color: #2a343e;
		}
	</style>
</head>

<body>
	<?php include '../header.php' ?>
	<div class="clear"></div>
	<div class="clear"></div>
	<div class="content">
		<div class="wrapper">
			<div class="left_sidebr">
				<ul>
					<li id="left_dashboard"><a href="../dashboard" class="dashboard">Dashboard</a></li>
					<li id="left_user"><a href="../listusers" class="user">Users</a>
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
				<h1>Dashboard</h1>
				<div class="tab">
					<!-- <ul>
						<li class="selected"><a href=""><span class="left"><img class="selected-act" src="images/dashboard-hover.png"><img src="images/dashboard.png" class="hidden" /></span><span class="right">Dashboard</span></a></li>
						<li><a href="list-users.php"><span class="left"><img class="selected-act" src="images/user-hover.png"><img class="hidden" src="images/user.png" /></span><span class="right">Users</span></a></li>
						<li><a href=""><span class="left"><img class="selected-act" src="images/setting-hover.png"><img class="hidden" src="images/setting.png" /></span><span class="right">Setting</span></a></li>
						<li><a href=""><span class="left"><img class="selected-act" src="images/configuration-hover.png"><img class="hidden" src="images/configuration.png" /></span><span class="right">Configuration</span></a></li>

					</ul> -->
					<!-- </div> -->
					<!-- for drop down -->
					<div class="filter-container" style="text-align: right; margin-bottom: 20px;">
						<label for="chartFilter"></label>
						<select id="chartFilter" onchange="updateCharts()">
							<!-- <option value="all" >All</option> -->
							<option value="month" <?php echo ($grp == 'month') ? 'selected' : ''; ?>>Month</option>
							<option value="week" <?php echo ($grp == 'week') ? 'selected' : ''; ?>>Week</option>
							<option value="day" <?php echo ($grp == 'day') ? 'selected' : ''; ?>>Day</option>
						</select>
					</div>


					<!-- *************graphs*************** -->
					<div class="main-graph-div" style="display:flex; justify-content: space-around">

						<!-- pie chart-->
						<div id="myChart"></div>

						<!-- bargraph -->
						<div id="myChart1"></div>

					</div>
					<div class="main-graph-div" style="display:flex; justify-content: space-around">

						<!-- line graph -->
						<div class="graph-line">
							<div id="mychart2"></div>

						</div>

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


	<!-- ******************for pie chart **********-->

	<script src="https://cdn.jsdelivr.net/npm/ag-charts-community@10.0.2/dist/umd/ag-charts-community.js?t=1721133054136"></script>
	<!-- <script src="/vanilla/pie-series/examples/simple-pie/data.js"></script>
	    <script src="/vanilla/pie-series/examples/simple-pie/main.js"></script> -->
	<script>
		function getData() {
			return [{
					asset: "Male",
					amount: <?php echo $m ?>
				},
				{
					asset: "Female",
					amount: <?php echo $f ?>
				},

			];
		}

		const {
			AgCharts
		} = agCharts;

		const options = {
			container: document.getElementById("myChart"),
			data: getData(),
			title: {
				text: "Gender Ratio",
			},
			series: [{
				type: "pie",
				angleKey: "amount",
				legendItemKey: "asset",
			}, ],
		};

		AgCharts.create(options);
	</script>



	<!-- ********************** for bar graph****************** -->
	<script src="https://cdn.jsdelivr.net/npm/ag-charts-enterprise@10.0.2/dist/umd/ag-charts-enterprise.js?t=1721132995911"></script>
	<script>
		//data
		function getData1() {
			const grp = <?php echo  '"' . $grp . '"'; ?>

			// console.log(grp);
			if (grp === "month") {
				return [{
						week: " Week 1",
						visitors: <?php echo $w1 ?>
					},
					{
						week: " Week 2",
						visitors: <?php echo $w2 ?>
					},
					{
						week: " Week 3",
						visitors: <?php echo $w3 ?>
					},
					{
						week: "Week 4",
						visitors: <?php echo $w4 ?>
					},
				];

			} else if (grp === "week") {
				return [{
						week: " Day 1",
						visitors: <?php echo $d1 ?>
					},
					{
						week: " Day 2",
						visitors: <?php echo $d2 ?>
					},
					{
						week: " Day 3",
						visitors: <?php echo $d3 ?>
					},
					{
						week: "Day 4",
						visitors: <?php echo $d4 ?>
					},
					{
						week: " Day 5",
						visitors: <?php echo $d5 ?>
					},
					{
						week: " Day 6",
						visitors: <?php echo $d6 ?>
					},
					{
						week: "Day 7",
						visitors: <?php echo $d7 ?>
					},
				];
			} else if (grp === "day") {
				return [{
						week: "12:00 - 4:00 a.m.",
						visitors: <?php echo $t1 ?>
					},
					{
						week: "4:00 - 8:00 a.m.",
						visitors: <?php echo $t2 ?>
					},
					{
						week: "8:00 - 12:00 p.m.",
						visitors: <?php echo $t3 ?>
					},
					{
						week: "12:00 - 4:00 p.m.",
						visitors: <?php echo $t4 ?>
					},
					{
						week: "4:00 - 8:00 p.m.",
						visitors: <?php echo $t5 ?>
					},
					{
						week: "8:00 - 12:00 a.m.",
						visitors: <?php echo $t6 ?>
					},



				];

			}
		}

		/*     *****************************

		

		    *********************************** */

		function formatNumber(value) {
			return `${(value)}`;
		}

		const options1 = {
			container: document.getElementById("myChart1"),
			data: getData1(),
			title: {
				text: "Sign-Up Records",
			},
			footnote: {
				text: "",
			},
			series: [{
				type: "bar",
				xKey: "week",
				yKey: "visitors",
				label: {
					formatter: ({
						value
					}) => formatNumber(value),
				},
				tooltip: {
					renderer: ({
						datum,
						xKey,
						yKey
					}) => {
						return {
							title: datum[xKey],
							content: formatNumber(datum[yKey])
						};
					},
				},
			}, ],
			axes: [{
					type: "category",
					position: "bottom",
					title: {
						text: "",
					},
				},
				{
					type: "number",
					position: "left",
					title: {
						text: "USERS",
					},
					label: {
						formatter: ({
							value
						}) => formatNumber(value),
					},
					crosshair: {
						label: {
							renderer: ({
									value
								}) =>
								`<div style="padding: 0 7px; border-radius: 2px; line-height: 1.7em; background-color: rgb(71,71,71); color: rgb(255, 255, 255);">${formatNumber(value)}</div>`,
						},
					},
				},
			],
		};
		AgCharts.create(options1);
	</script>


	<!-- *********** for line graph ****************-->
	<script src="https://cdn.jsdelivr.net/npm/ag-charts-enterprise@10.0.2/dist/umd/ag-charts-enterprise.js?t=1721132995946"></script>
	<script>
		function getData2() {


			const grp = <?php echo  '"' . $grp . '"'; ?>

			console.log(grp);

			const data = [{
					Weeks: " Week 1",
					Users: <?php echo $w1 ?>
				},
				{
					Weeks: "Week 2",
					Users: <?php echo $w2 ?>
				},
				{
					Weeks: "Week 3",
					Users: <?php echo $w3 ?>
				},
				{
					Weeks: "Week 4",
					Users: <?php echo $w4 ?>
				},
			]
			const data1 = [{
					Weeks: "12:00 - 4:00 a.m.",
					Users: <?php echo $t1 ?>
				},
				{
					Weeks: "4:00 - 8:00 a.m.",
					Users: <?php echo $t2 ?>
				},
				{
					Weeks: "8:00 - 12:00 p.m.",
					Users: <?php echo $t3 ?>
				},
				{
					Weeks: "12:00 - 4:00 p.m.",
					Users: <?php echo $t4 ?>
				},
				{
					Weeks: "4:00 - 8:00 p.m.",
					Users: <?php echo $t5 ?>
				},
				{
					Weeks: "8:00 - 12:00 a.m.",
					Users: <?php echo $t6 ?>
				},
			]
			const data2 = [{
					Weeks: " Day 1",
					Users: <?php echo $d1 ?>
				},
				{
					Weeks: "  Day 2",
					Users: <?php echo $d2 ?>
				},
				{
					Weeks: "Day 3",
					Users: <?php echo $d3 ?>
				},
				{
					Weeks: "Day 4",
					Users: <?php echo $d4 ?>
				},
				{
					Weeks: "Day 5",
					Users: <?php echo $d5 ?>
				},
				{
					Weeks: "Day 6",
					Users: <?php echo $d6 ?>
				},
				{
					Weeks: "Day 7",
					Users: <?php echo $d7 ?>
				},


			]
			if (grp === "month") {
				return data;
			} else if (grp === "day") {
				return data1;


			} else if (grp === "week") {
				return data2;

			}

		}


		//    const data = [{
		// 			Weeks: 1,
		// 			Users: 
		// 		},
		// 		{
		// 			Weeks: 2,
		// 			Users: 
		// 		},
		// 		{
		// 			Weeks: 3,
		// 			Users:
		// 		},
		// 		{
		// 			Weeks: 4,
		// 			Users: 
		// 		},
		// 	]

		// return data;
		// }

		const dateFormatter = new Intl.DateTimeFormat("en-US");
		const tooltip = {
			renderer: ({
				title,
				datum,
				xKey,
				yKey
			}) => ({
				title,
				content: `${datum[xKey]}: ${datum[yKey]}`,
			}),
		};

		const options2 = {
			container: document.getElementById("mychart2"),
			data: getData2(),
			title: {
				text: "Sign-Up Records",
			},
			footnote: {
				text: "",
			},
			series: [{
					type: "line",
					xKey: "Weeks",
					yKey: "Users",
					tooltip,
				},
				// {
				//   type: "line",
				//   xKey: "Weeks",
				//   yKey: " Total Users",
				//   tooltip,
				// },
			],
			axes: [{
					position: "bottom",
					type: "category",
					title: {
						text: "",
					},
					label: {
						formatter: ({
							value
						}) => formatNumber(value),
					},
				},
				{
					position: "left",
					type: "number",
					title: {
						text: "USERS",
					},
				},
			],
		};

		AgCharts.create(options2);
	</script>

	<script>
		//  ** ** ** ** ** ** ** ** jsfor dropdown ** ** ** ** ** ** ** 
		function updateCharts() {
			const value = document.getElementById("chartFilter").value
			console.log("here", value);
			window.location.href = "http://localhost/employee_management/dashboard/?grp=" + value
		}
	</script>


</body>

</html>