<?php 

include 'includes/db.php'; 

session_start();
if(!isset($_SESSION['username'])){
  header("Location: login");
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  </head>
  <body>

    <?php include 'includes/navigation.php';?>

    <div class="container">
      <div class="form-container">

        <h2>Add Power Supply</h2>
        <form method = "POST" action = "">
          <label for="serial">Serial Number:</label>
          <input type="text" id="serial" name="serial" required>

          <label for="model">Model:</label>
          <input type="text" id="model" name="model" required>

          <label for="psu-condition">Condition:</label>
          <input type="text" id="psu-condition" name="psu-condition" required>

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


          <input type="submit" value="Add power supply" name="add-psu" style="font-family: Montserrat, sans-serif; font-weight: 600;">
        </form>
      </div><br>
      <div class="etc"><br><br>

        <a href="add-psus" style="text-decoration: none; color: #ffffffdb; padding: 12px 24px; background-color: #88ac81; border-radius: 2px; border: 3px solid #63885c; font-weight: bold; text-transform: uppercase; transition: background-color 0.3s ease;">Add Multiple Power Supplies</a>

      </div>
    </div>
    <br>
    <script src="./js/additions.js"></script>
  </body>
</html>

<?php

  if(isset($_POST['add-psu']) && !empty($_POST['serial']) && !empty($_POST['model']) && !empty($_POST['location']) && !empty($_POST['customer']) && !empty($_POST['psu-condition'])){

      $psu_sn = mysqli_real_escape_string($db, $_POST['serial']);
      $psu_model = mysqli_real_escape_string($db, $_POST['model']);
      $psu_location = mysqli_real_escape_string($db, $_POST['location']);
      $psu_customer = mysqli_real_escape_string($db, $_POST['customer']);
      $psu_condition = mysqli_real_escape_string($db, $_POST['psu-condition']);

      $insert_psu = "INSERT INTO power_supplies(psu_model, psu_sn, location_id, customer_id, psu_condition) VALUES('$psu_model', '$psu_sn', '$psu_location', '$psu_customer', '$psu_condition');";

      $q_inp = mysqli_query($db, $insert_psu);

      echo "<center><br>Successfully added!</center>";


  }
?>
