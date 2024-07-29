<?php
  include 'connect.php';
  $filter = $_GET['filter'] ?? 'month';
  $time=time();
  switch ($filter) {
      case 'today':
          $startTime = strtotime('today');
          break;
      case 'week':
          $startTime = strtotime('-1 week');
          break;
      case 'month':
          $startTime = strtotime('-1 month');
          break;
    //   case 'all':
      default:
          $startTime = 0;
          break;
  }
  
  $weeklyData = [];
  if ( $filter == 'month') {
      for ($i = 0; $i < 4; $i++) {
          $end = $time - ($i * 60 * 60 * 24 * 7);
          $start = $end - 60 * 60 * 24 * 7;
          $query = "SELECT * FROM employees WHERE createdAt BETWEEN $start AND $end";
          $result = mysqli_query($con, $query);
          $weeklyData[] = mysqli_num_rows($result);
      }
      $weeklyData = array_reverse($weeklyData);
  } elseif ($filter == 'week') {
      $start = strtotime('-1 week');
      for ($i = 0; $i < 7; $i++) {
          $end = $start + 60 * 60 * 24;
          $query = "SELECT * FROM employee WHERE createdAt BETWEEN $start AND $end";
          $result = mysqli_query($con, $query);
          $weeklyData[] = mysqli_num_rows($result);
          $start = $end;
      }
  } elseif ($filter == 'today') {
      for ($i = 0; $i < 24; $i++) {
          $start = strtotime("today") + ($i * 60 * 60);
          $end = $start + 60 * 60;
          $query = "SELECT * FROM employees WHERE createdAt BETWEEN $start AND $end";
          $result = mysqli_query($con, $query);
          $weeklyData[] = mysqli_num_rows($result);
      }
  }
  
  // Fetch gender data
  $maleQuery = "SELECT * FROM employees WHERE gender = 'Male' AND createdAt >= $startTime";
  $result1 = mysqli_query($con, $maleQuery);
  $m = mysqli_num_rows($result1);
  
  $femaleQuery = "SELECT * FROM employees WHERE gender = 'Female' AND createdAt >= $startTime";
  $result2 = mysqli_query($con, $femaleQuery);
  $f = mysqli_num_rows($result2);
  echo json_encode([
        'weeklyData' => $weeklyData,
        'genderData' => [
            'Male' => $m,
            'Female' => $f
        ]
    ]);
?>