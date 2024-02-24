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
    <title>CryptoVault - Add Fan</title>
    <link rel="icon" type="image/x-icon" href="images/warehouse.png">
    <link rel="stylesheet" href="./css/navbar.css"/>
    <link rel="stylesheet" href="./css/form.css"/>
</head>
<body>

    <?php include 'includes/navigation.php';?>

<div class="container">
    <div class="form-container">
        <h2>Add Fan</h2>
        <form method="POST" action="">
            <label for="model">Model:</label>
            <input type="text" id="model" name="model" required>

            <label for="qty">Quantity:</label>
            <input type="text" id="qty" name="qty" required>

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

            <input type="submit" value="Submit" name="add-fan">
        </form>
    </div>
</div>
</body>
</html>

<?php
if(isset($_POST['add-fan']) && !empty($_POST['qty']) && !empty($_POST['model']) && !empty($_POST['location']) && !empty($_POST['customer'])){
    $fan_model = mysqli_real_escape_string($db, $_POST['model']);
    $fan_qty = (int)$_POST['qty'];
    $fan_location = mysqli_real_escape_string($db, $_POST['location']);
    $fan_customer = mysqli_real_escape_string($db, $_POST['customer']);

    $insert_fan = "INSERT INTO fans(fan_model, location_id, customer_id, fan_qty) VALUES ('$fan_model', '$fan_location', '$fan_customer', '$fan_qty')";
    $q_inp = mysqli_query($db, $insert_fan);
    if($q_inp){
        echo "<center><br>Successfully added!</center>";
    } else {
        echo "Could not add fan!";
    }
}
?>

