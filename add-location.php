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
    <title>CryptoVault - Add Location</title>
    <link rel="icon" type="image/x-icon" href="images/warehouse.png">
    <link rel="stylesheet" href="./css/navbar.css">
    <link rel="stylesheet" href="./css/form.css">
  </head>
  <body>

    <?php include 'includes/navigation.php';?>

    <div class="container">
      <div class="form-container">
        <h2>New Location</h2>

        <form method = "POST">

          <label for="location">Location Name:</label>
          <input type="text" id="location" name="new-location" required>

          <input type="submit" value="Submit" name="add-loc-button">
        </form>

        <?php

        if(isset($_POST['add-loc-button'])){
          if(!empty($_POST['new-location'])){
            $location_name_new = mysqli_real_escape_string($db, $_POST['new-location']);
            $location_name_new = htmlspecialchars($location_name_new);
            $insert_nl = mysqli_query($db, "INSERT INTO locations(location_name) VALUES('$location_name_new')");
            if($insert_nl){
              echo "<h1><center>Location added!</center></h1>";
            }else{
              echo "Could not add location!";
            }
          }
        }

        ?>

      </div>
    </div>
  </body>
</html>

