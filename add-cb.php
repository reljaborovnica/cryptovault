<?php 
include 'includes/db.php'; 
session_start();
if(!isset($_SESSION['username'])){
  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>CryptoVault - Add PSU</title>
    <link rel="icon" type="image/x-icon" href="images/warehouse.png">
    <link rel="stylesheet" href="./css/navbar.css"/>
    <link rel="stylesheet" href="./css/form.css"/>
  </head>
  <body>

    <?php include 'includes/navigation.php';?>

    <div class="container">
      <div class="form-container">
        <h2>Add Control Board</h2>
        <form method = "POST" action = "">
          <label for="serial">Serial Number:</label>
          <input type="text" id="serial" name="serial" required>

          <label for="model">Model:</label>
          <input type="text" id="model" name="model" required>

          <label for="cb-condition">Condition:</label>
          <input type="text" id="cb-condition" name="cb-condition" required>

          <label id="search" for="locationSelect">Select a Location</label>
          <select id="locationSelect" name="location">
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



        <label id="search" for="customerSelect">Select a Customer</label>
        <select id="customerSelect" name="customer">
          <option disabled selected value="customer-add" id="customerAdd">--Customer--</option>
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


          <input type="submit" value="Submit" name="add-cb">
        </form>
      </div>
            <div class="etc"><br><br>

        <a href="add-cbs.php" style="text-decoration: none; color: #ffffff; padding: 12px 24px; background-color: #4CAF50; border-radius: 8px; border: 2px solid #4CAF50; font-weight: bold; text-transform: uppercase; transition: background-color 0.3s ease;">Add Multiple Control Boards</a>

      </div>
    </div>
  </body>
</html>

<?php

  if(isset($_POST['add-cb']) && !empty($_POST['serial']) && !empty($_POST['model']) && !empty($_POST['location']) && !empty($_POST['customer']) && !empty($_POST['cb-condition'])){

      $cb_sn = mysqli_real_escape_string($db, $_POST['serial']);
      $cb_model = mysqli_real_escape_string($db, $_POST['model']);
      $cb_location = mysqli_real_escape_string($db, $_POST['location']);
      $cb_customer = mysqli_real_escape_string($db, $_POST['customer']);
      $cb_condition = mysqli_real_escape_string($db, $_POST['cb-condition']);

      $insert_cb = "INSERT INTO control_boards(cb_model, cb_sn, location_id, customer_id, cb_condition ) VALUES('$cb_model', '$cb_sn', '$cb_location', '$cb_customer', '$cb_condition')";

      $q_inp = mysqli_query($db, $insert_cb);

      echo "<center><br>Successfully added!</center>";


  }

?>

