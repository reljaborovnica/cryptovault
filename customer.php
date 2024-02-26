<?php 

require 'includes/db.php'; 

session_start();
if(!isset($_SESSION['username'])){
  header("Location: login");
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CryptoVault - Customer</title>
    <link rel="icon" type="image/x-icon" href="images/warehouse.png">
    <link rel="stylesheet" href="./css/navbar.css">
    <link rel="stylesheet" href="./css/tables.css">
    <link rel="stylesheet" href="./css/cust-all.css">
  </head>
  <body>

    <?php include 'includes/navigation.php';?>

    <div class="container">
      <?php

      if(isset($_GET['customerAll'])) {
        $customer_id = (int)$_GET['customerAll'];

        $m_count_q = mysqli_query($db, "SELECT COUNT(*) AS miner_count FROM miners WHERE customer_id = $customer_id;");
        $p_count_q = mysqli_query($db, "SELECT COUNT(*) AS psu_count FROM power_supplies WHERE customer_id = $customer_id;");
        $cb_count_q = mysqli_query($db, "SELECT COUNT(*) AS cb_count FROM control_boards WHERE customer_id = $customer_id;");
        $fan_count_q = mysqli_query($db, "SELECT COUNT(*) AS fan_count FROM fans WHERE customer_id = $customer_id;");

        $m_count = mysqli_fetch_assoc($m_count_q)['miner_count'];
        $p_count = mysqli_fetch_assoc($p_count_q)['psu_count'];
        $cb_count = mysqli_fetch_assoc($cb_count_q)['cb_count'];
        $fan_count = mysqli_fetch_assoc($fan_count_q)['fan_count'];

        $g_name = mysqli_query($db, "SELECT customer_name FROM customers WHERE customer_id = $customer_id;");
        $f_name = mysqli_fetch_assoc($g_name);
        $c_name = $f_name['customer_name'];

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
          <td><a href="customer-miner?id=<?php echo $customer_id;?>">View Customer Miners (<?php echo $m_count;?>)</a></td>
            <td>View all miners for a specific customer.</td>
          </tr>
          <tr>
            <td><a href="customer-psu?id=<?php echo $customer_id;?>">View Customer Power Supplies (<?php echo $p_count;?>)</a></td>
            <td>View all power supplies for a specific customer.</td>
          </tr>
          <tr>
            <td><a href="customer-cb?id=<?php echo $customer_id;?>">View Customer Control Boards (<?php echo $cb_count;?>)</a></td>
            <td>View all control boards for a specific customer.</td>
          </tr>
          <tr>
            <td><a href="customer-fan?id=<?php echo $customer_id;?>">View Customer Fans (<?php echo $fan_count;?>) </a></td>
            <td>View all fans for a specific customer.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </body>
</html>
<?php
} else {
    echo "<h1>Customer name not provided!</h1>";
}
?>
