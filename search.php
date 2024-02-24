<?php 

require 'includes/db.php'; 

session_start();
if(!isset($_SESSION['username'])){
  header("Location: login.php");
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

  </body>
</html>

<?php

  if(isset($_GET['search-btn'])){
    $search_term = $_GET['search'];
    header("Location: miner.php?search=$search_term");
    exit;
  }

  if(isset($_GET['search-btn-psu'])){
    $search_psu_term = $_GET['search-psu'];
    header("Location: psu.php?search=$search_psu_term");
    exit;
  }

  if(isset($_GET['search-btn-cb'])){
    $search_cb_term = $_GET['search-psu'];
    header("Location: cb.php?search=$search_cb_term");
    exit;
  }

?>
