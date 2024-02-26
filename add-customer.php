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
    <title>CryptoVault - Add Customer</title>
    <link rel="icon" type="image/x-icon" href="images/warehouse.png">
    <link rel="stylesheet" href="./css/navbar.css">
    <link rel="stylesheet" href="./css/form.css">
  </head>
  <body>

    <?php include 'includes/navigation.php';?>

    <div class="container">
      <div class="form-container">
        <h2>New Customer</h2>

        <form method = "POST">

          <label for="customer">Customer Name:</label>
          <input type="text" id="customer" name="new-customer" required>

          <input type="submit" value="Submit" name="add-cust-button">
        </form>

        <?php

        if(isset($_POST['add-cust-button'])){
          if(!empty($_POST['new-customer'])){
            $customer_name_new = mysqli_real_escape_string($db, $_POST['new-customer']);
            $customer_name_new = htmlspecialchars($customer_name_new);
            $insert_nc = mysqli_query($db, "INSERT INTO customers(customer_name) VALUES('$customer_name_new')");
            if($insert_nc){
              echo "<h1><center>Customer added!</center></h1>";
            }else{
              echo "Could not add customer!";
            }
          }
        }

        ?>

      </div>
    </div>
  </body>
</html>

