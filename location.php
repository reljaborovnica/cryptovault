<?php 

require 'includes/db.php'; 

session_start();
if(!isset($_SESSION['username'])){
  header("Location: login.php");
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CryptoVault - Location</title>
    <link rel="icon" type="image/x-icon" href="images/warehouse.png">
    <link rel="stylesheet" href="./css/navbar.css">
    <link rel="stylesheet" href="./css/tables.css">
    <link rel="stylesheet" href="./css/cust-all.css">
  </head>
  <body>

    <?php include 'includes/navigation.php';?>

    <div class="container">
      <?php

      if(isset($_GET['locationAll'])) {
        $location_id = (int)$_GET['locationAll'];

        $m_count_q = mysqli_query($db, "SELECT COUNT(*) AS miner_count FROM miners WHERE location_id = $location_id;");
        $p_count_q = mysqli_query($db, "SELECT COUNT(*) AS psu_count FROM power_supplies WHERE location_id = $location_id;");
        $cb_count_q = mysqli_query($db, "SELECT COUNT(*) AS cb_count FROM control_boards WHERE location_id = $location_id;");
        $fan_count_q = mysqli_query($db, "SELECT COUNT(*) AS fan_count FROM fans WHERE location_id = $location_id;");

        $m_count = mysqli_fetch_assoc($m_count_q)['miner_count'];
        $p_count = mysqli_fetch_assoc($p_count_q)['psu_count'];
        $cb_count = mysqli_fetch_assoc($cb_count_q)['cb_count'];
        $fan_count = mysqli_fetch_assoc($fan_count_q)['fan_count'];

        $g_name = mysqli_query($db, "SELECT location_name FROM locations WHERE location_id = $location_id;");
        $f_name = mysqli_fetch_assoc($g_name);
        $c_name = $f_name['location_name'];

      ?>
      <table><h1><?php echo $c_name;?></h1>
      <thead>
          <tr>
            <th>Action</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <tr>
          <td><a href="location-miner?id=<?php echo $location_id;?>">View Location Miners (<?php echo $m_count;?>)</a></td>
            <td>View all miners for a specific location.</td>
          </tr>
          <tr>
            <td><a href="location-psu?id=<?php echo $location_id;?>">View Location Power Supplies (<?php echo $p_count;?>)</a></td>
            <td>View all power supplies for a specific location.</td>
          </tr>
          <tr>
            <td><a href="location-cb?id=<?php echo $location_id;?>">View Location Control Boards (<?php echo $cb_count;?>)</a></td>
            <td>View all control boards for a specific location.</td>
          </tr>
          <tr>
            <td><a href="location-fan?id=<?php echo $location_id;?>">View Location Fans (<?php echo $fan_count;?>) </a></td>
            <td>View all fans for a specific location.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </body>
</html>
<?php
} else {
    echo "<h1>Location not provided!</h1>";
}
?>
