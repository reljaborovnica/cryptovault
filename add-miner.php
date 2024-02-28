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
    <title>CryptoVault - Add Miner</title>
    <link rel="icon" type="image/x-icon" href="images/warehouse.png">
    <link rel="stylesheet" href="./css/navbar.css"/>
    <link rel="stylesheet" href="./css/form.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  </head>
  <body>

    <?php include 'includes/navigation.php';?>

    <div class="container">
      <div class="form-container">
        <h2>Add Miner</h2>
        <form method="POST" action="">
          <label for="labele">Label:</label>
          <input type="text" id="model" name="labele" required>

          <label for="serial">Serial Number:</label>
          <input type="text" id="serial" name="serial" required>

          <label for="model">Model:</label>
          <input type="text" id="model" name="model" required>

          <label for="psu-model">PSU Model:</label>
          <input type="text" id="model" name="psu-model" required>

          <label for="psu-sn">PSU Serial Number:</label>
          <input type="text" id="model" name="psu-sn" required>

          <label for="miner-condition">Condition:</label>
          <input type="text" id="model" name="miner-condition" required>

          <label id="search" for="locationSelect">Select a Location:</label>
          <select id="locationSelect" name="location" required>
            <option disabled selected value="customer-add" id="customerAdd">--Location--</option>
              <?php
                $get_location = "SELECT location_id, location_name FROM locations ORDER BY location_name ASC";
                $q_gc = mysqli_query($db, $get_location);
                while($fetch_gc = mysqli_fetch_assoc($q_gc)){
                  $location_name = $fetch_gc['location_name'];
                  $location_id = $fetch_gc['location_id'];
              ?>
              <option value="<?php echo $location_id;?>"><?php echo htmlspecialchars($location_name); ?></option>
            <?php };?>
          </select>

          <label id="search" for="customerSelect">Select a Customer:</label>
            <select id="customerSelect" name="customer" required>
              <option disabled selected id="customerAdd">--Select Customer--</option>
              <?php
                $get_customer = "SELECT customer_id, customer_name FROM customers ORDER BY customer_name ASC";
                $q_gcu = mysqli_query($db, $get_customer);
                while($fetch_gcu = mysqli_fetch_assoc($q_gcu)){
                  $customer_name = $fetch_gcu['customer_name'];
                  $customer_id = $fetch_gcu['customer_id'];
              ?>
              <option value="<?php echo $customer_id;?>"><?php echo htmlspecialchars($customer_name); ?></option>
              <?php };?>
          </select>

          <input type="submit" value="Add miner" name='add-miner' style="font-family: 'Montserrat', sans-serif; font-weight: 600;">
        </form>
      </div>
      <div class="etc"><br><br>

        <a href="add-miners" style="text-decoration: none; color: #ffffffdb; padding: 12px 24px; background-color: #88ac81; border-radius: 2px; border: 3px solid #63885c; font-weight: bold; text-transform: uppercase; transition: background-color 0.3s ease;">Add Multiple Miners</a>

      </div>
    </div><br>
          <script src="./js/additions.js"></script>

  </body>
</html>
<?php

if(isset($_POST['add-miner']) && !empty($_POST['labele']) && !empty($_POST['serial']) && !empty($_POST['model']) && !empty($_POST['psu-model']) && !empty($_POST['psu-sn']) && !empty($_POST['location']) && !empty($_POST['customer']) && !empty($_POST['miner-condition'])){
 
    $miner_label = mysqli_real_escape_string($db, $_POST['labele']);
    $miner_sn = mysqli_real_escape_string($db, $_POST['serial']);
    $miner_model = mysqli_real_escape_string($db, $_POST['model']);
    $miner_psu_model = mysqli_real_escape_string($db, $_POST['psu-model']);
    $miner_psu_sn = mysqli_real_escape_string($db, $_POST['psu-sn']);
    $miner_location = mysqli_real_escape_string($db, $_POST['location']);
    $miner_customer = mysqli_real_escape_string($db, $_POST['customer']);
    $miner_condition = mysqli_real_escape_string($db, $_POST['miner-condition']);

    $insert_miner_data = "
          INSERT INTO miners(miner_label, miner_sn, miner_model, psu_model, psu_sn, location_id, customer_id, miner_condition)
          VALUES('$miner_label', '$miner_sn', '$miner_model', '$miner_psu_model', '$miner_psu_sn', '$miner_location', '$miner_customer', '$miner_condition');


          " ;

    $q_imd = mysqli_query($db, $insert_miner_data);

    echo "<center><br>Successfully added!</center>";

  }

?>
