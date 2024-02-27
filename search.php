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
  <title>CryptoVault - Search</title>
  <link rel="icon" type="image/x-icon" href="images/warehouse.png">
  <link rel="stylesheet" href="./css/navbar.css">
  <link rel="stylesheet" href="./css/form.css">
  <link rel="stylesheet" href="./tables.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  </head>
  <body>

    <?php include 'includes/navigation.php';?>

    <div class="container">
      <div class="form-container">
        <h2>Inventory Search</h2>


        <form action="miner.php" method="GET">
          <input type="text" id="search" name="search" placeholder="Search for a Miner(s) by SN, Label, Model or Condition " required>
          <input type="submit" value="Search" name="search-btn">
        </form>

        <form action="psu.php" method="GET">
          <input type="text" id="search" name="search" placeholder="Search for a PSU by SN, Model or Condition " required>
          <input type="submit" value="Search" name="search-btn-psu">
        </form>

        <form action="cb.php" method="GET">
          <input type="text" id="search" name="search" placeholder="Search for a CB by SN, Model or Condition " required>
          <input type="submit" value="Search" name="search-btn-cb">
        </form>
      </div>
    </div>  
    <script src="./js/additions.js"></script>
  </body>
</html>

