<?php 

require 'includes/db.php'; 

session_start();
if(!isset($_SESSION['username'])){
  header("Location: login");
  exit();
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>CryptoVault - Customers</title>
    <link rel="icon" type="image/x-icon" href="images/warehouse.png">
    <link rel="stylesheet" href="./css/navbar.css">
    <link rel="stylesheet" href="./css/tables.css">
  </head>
  <body>

    <?php include 'includes/navigation.php';?>

    <div class="container">
      <div class="table-container">
        
        <table>
          <thead>
            <tr>
              <th>Customer</th>
              <th>Remove</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $get_ac = "SELECT * FROM customers ORDER BY customer_name";
            $q_gac = mysqli_query($db, $get_ac);

            while($fetch_q_gac = mysqli_fetch_assoc($q_gac)){
              $customer_id = $fetch_q_gac['customer_id'];
              $customer_name = $fetch_q_gac['customer_name'];
            ?>
            <tr>
              <td><a href="customer?customerAll=<?php echo $customer_id;?>"> <?php echo $customer_name; ?></td>
              <td class="operations">
                <?php
                $check_miners_query = mysqli_query($db, "SELECT COUNT(*) AS total_miners FROM miners WHERE customer_id = '$customer_id'");
                $check_psus_query = mysqli_query($db, "SELECT COUNT(*) AS total_psus FROM power_supplies WHERE customer_id = '$customer_id'");
                $check_cbs_query = mysqli_query($db, "SELECT COUNT(*) AS total_cbs FROM control_boards WHERE customer_id = '$customer_id'");
                $check_fans_query = mysqli_query($db, "SELECT COUNT(*) AS total_fans FROM fans WHERE customer_id = '$customer_id'");

                $miners_count = mysqli_fetch_assoc($check_miners_query)['total_miners'];
                $psus_count = mysqli_fetch_assoc($check_psus_query)['total_psus'];
                $cbs_count = mysqli_fetch_assoc($check_cbs_query)['total_cbs'];
                $fans_count = mysqli_fetch_assoc($check_fans_query)['total_fans'];

                if ($miners_count > 0 || $psus_count > 0 || $cbs_count > 0 || $fans_count > 0 ) {
                  echo "<span style='color: red;'></span><b>Unable to delete: This customer has items currently in use or in storage</b>";
                } else {
                  echo "<a href='customers?del=$customer_id'>❌</a>";
                }
                ?>
              </td>
            </tr>
            <?php };?>
          </tbody>
        </table>
        <div id="c-b"><a href = "add-customer"><h2><center>Add a new customer</center></h2></a>
      </div>
    </div>

  </body>
</html>

<?php

if(isset($_GET['del']) && !empty($_GET['del'])){
  $c_id = $_GET['del'];
  try {
    $check_miners_query = mysqli_query($db, "SELECT COUNT(*) AS total_miners FROM miners WHERE customer_id = '$c_id'");
    $miners_count = mysqli_fetch_assoc($check_miners_query)['total_miners'];
    
      if ($miners_count > 0) {
        throw new Exception("Customer has associated miners");
      }
    
    $delete_customer = mysqli_query($db, "DELETE FROM customers WHERE customer_id = '$c_id'");
    echo "<center>Customer deleted successfully.</center>";
    header("Location: customers");

  } catch (Exception $e) {
      echo "<center>Could not delete customer: " . $e->getMessage() . "</center>";
  }
}

?>
