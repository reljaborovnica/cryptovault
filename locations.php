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
    <title>CryptoVault - Locations</title>
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
              <th>Location</th>
              <th>Remove</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $get_al = "SELECT * FROM locations ORDER BY location_name";
            $q_gal = mysqli_query($db, $get_al);

            while($fetch_q_gal = mysqli_fetch_assoc($q_gal)){
              $location_id = $fetch_q_gal['location_id'];
              $location_name = $fetch_q_gal['location_name'];
            ?>
            <tr>
              <td><a href="location?locationAll=<?php echo $location_id;?>"> <?php echo $location_name; ?></td>
              <td class="operations">
                <?php
                $check_miners_query = mysqli_query($db, "SELECT COUNT(*) AS total_miners FROM miners WHERE location_id = '$location_id'");
                $check_psus_query = mysqli_query($db, "SELECT COUNT(*) AS total_psus FROM power_supplies WHERE location_id = '$location_id'");
                $check_cbs_query = mysqli_query($db, "SELECT COUNT(*) AS total_cbs FROM control_boards WHERE location_id = '$location_id'");
                $check_fans_query = mysqli_query($db, "SELECT COUNT(*) AS total_fans FROM fans WHERE location_id = '$location_id'");

                $miners_count = mysqli_fetch_assoc($check_miners_query)['total_miners'];
                $psus_count = mysqli_fetch_assoc($check_psus_query)['total_psus'];
                $cbs_count = mysqli_fetch_assoc($check_cbs_query)['total_cbs'];
                $fans_count = mysqli_fetch_assoc($check_fans_query)['total_fans'];

                if ($miners_count > 0 || $psus_count > 0 || $cbs_count > 0 || $fans_count > 0 ) {
                  echo "<span style='color: red;'></span><b>Unable to delete: This location has items currently in use or in storage</b>";
                } else {
                  echo "<a href='locations?del=$location_id'>❌</a>";
                }
                ?>
              </td>
            </tr>
            <?php };?>
          </tbody>
        </table>
        <div id="c-b"><a href = "add-location"><h2><center>Add a new location</center></h2></a>
      </div>
    </div>

  </body>
</html>

<?php

if(isset($_GET['del']) && !empty($_GET['del'])){
  $c_id = mysqli_real_escape_string($db, $_GET['del']);
  try {
    $check_miners_query = mysqli_query($db, "SELECT COUNT(*) AS total_miners FROM miners WHERE location_id = '$c_id'");
    $miners_count = mysqli_fetch_assoc($check_miners_query)['total_miners'];
    
      if ($miners_count > 0) {
        throw new Exception("Customer has associated miners");
      }
    
    $delete_customer = mysqli_query($db, "DELETE FROM locations WHERE location_id = '$c_id'");
    echo "<center>Location deleted successfully.</center>";
    header("Location: locations");

  } catch (Exception $e) {
      echo "<center>Could not delete location: " . $e->getMessage() . "</center>";
  }
}

?>
