<?php 

require 'includes/db.php'; 

session_start();
if(!isset($_SESSION['username'])){
  header("Location: login");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="images/warehouse.png">
    <link rel="stylesheet" href="./css/navbar.css">
    <link rel="stylesheet" href="./css/mtables.css">
    <title>CryptoVault - Add miners</title>
</head>
<body>

    <?php include 'includes/navigation.php';?>

    <?php

    $loc_string_id = "";
    $loc_string_name = "";
    $cust_string_id = "";
    $cust_string_name = "";

    $get_loc = "SELECT * FROM locations";
    $run_gl = mysqli_query($db, $get_loc);
    $total_rows_loc = mysqli_num_rows($run_gl);
    $f_count_loc = 0;

    while($fetch = mysqli_fetch_assoc($run_gl)){
        $loc_string_id .= $fetch['location_id'];
        $loc_string_name .= $fetch['location_name'];
        
        if (++$f_count_loc < $total_rows_loc) {
            $loc_string_id .= ",";
            $loc_string_name .= ",";
        }
    }

    $get_cust = "SELECT * FROM customers";
    $run_gc = mysqli_query($db, $get_cust);
    $total_rows_cust = mysqli_num_rows($run_gc);
    $f_count_cust = 0;

    while($fetch_cust = mysqli_fetch_assoc($run_gc)){
        $cust_string_id .= $fetch_cust['customer_id'];
        $cust_string_name .= $fetch_cust['customer_name'];
        
        if (++$f_count_cust < $total_rows_cust) {
            $cust_string_id .= ",";
            $cust_string_name .= ",";
        }
    }


?>
    <!-- location options (hidden from user) INSERT PHP in <p> text -->
    <p class="hidden" id="location-options"><?php echo $loc_string_name;?></p>
    <p class="hidden" id="location-options"><?php echo $customer_id;?></p>


    <!-- customer options (hidden from user) INSERT PHP in <p> text -->
    
    <p class="hidden" id="customer-options"><?php echo $cust_string_name;?></p>
    <p class="hidden" id="customer-options"><?php echo $cust_string_id;?></p>

    <h2>Insert multiple miners</h2>

    <form class="container" onsubmit="return confirmSubmit();" method="POST" action="./functions/add-miners-data.php">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Label</th>
                        <th>Serial Number</th>
                        <th>Model</th>
                        <th>PSU Model</th>
                        <th>PSU SN</th>
                        <th>Condition</th>
                        <th>Location</th>
                        <th>Customer Name</th>
                        <th>Operations</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tbody class="select-all">
                    <tr>
                        <th>Select For All <small>(Change all columns at once)</small></th>
                        <td></td>
                        <td><input type="text" id="select-all-model"></td>
                        <td><input type="text" id="select-all-psumodel"></td>
                        <td></td>
                                      <td><input type="text" id="select-all-condition"></td>

                        <td><select id="select-all-location"></select></td>
                        <td><select id="select-all-customer"></select></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="display: flex; justify-content: center; align-items: flex-end;">
            <button type="button" id="add-btn">+ Add Item</button>
            <input type="submit" name="submit" style="width: auto; height: 50px; margin: 0 10px">
        </div>
    </form>
    

    <script src="./js/add.js"></script>
</body>
</html>

