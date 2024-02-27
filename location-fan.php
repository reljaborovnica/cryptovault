<?php 

require 'includes/db.php'; 
require 'functions/export.php';

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
  <title>CryptoVault - Fans</title>
  <link rel="icon" type="image/x-icon" href="images/warehouse.png">
  <link rel="stylesheet" href="./css/navbar.css"/>
  <link rel="stylesheet" href="./css/tables.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    
<?php include 'includes/navigation.php';?>

  <div class="container">
   <div class="table-container">
        <?php
        if(isset($_GET['id'])){
            $search = (int)$_GET['id'];
            $get_cb_data = 
              "
                SELECT 
                  f.fan_id, f.fan_model, f.fan_qty, l.location_name, l.location_id, c.customer_id ,c.customer_name
                FROM fans f
                  JOIN locations l ON f.location_id = l.location_id
                  JOIN customers c ON f.customer_id = c.customer_id 
                WHERE 
                  l.location_id = $search;
              ";

            $fan_result = mysqli_query($db, $get_cb_data);
            $count = mysqli_num_rows($fan_result);

            if($count > 0){
                ?>
                <table>
                    <thead>
                    <tr>
                        <th>Model</th>
                        <th>Quantity</th>
                        <th>Location</th>
                        <th>Customer Name</th>
                        <th>Operations</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while($fan_row = mysqli_fetch_assoc($fan_result)){
                        $fan_id = (int)$fan_row['fan_id'];
                        $fan_model = $fan_row['fan_model'];
                        $fan_quantity = $fan_row['fan_qty'];
                        $fan_customer_id = $fan_row['customer_id'];
                        $fan_customer_name = $fan_row['customer_name'];
                        $fan_location_id = $fan_row['location_id'];
                        $fan_location = $fan_row['location_name'];
                        ?>
                        <tr>
                            <form method="POST" action="">
                                <td><input type="text" name="fan-model" value="<?php echo htmlspecialchars($fan_model); ?>"></td>
                                <td><input type="text" name="fan-qty" value="<?php echo htmlspecialchars($fan_quantity); ?>"></td>
                                <td>
                                    <select name="locationChange">
                                      <?php
                                      $location_query = "SELECT location_id, location_name FROM locations";
                                      $location_result = mysqli_query($db, $location_query);
                                      while($location_row = mysqli_fetch_assoc($location_result)){
                                        $location_id = $location_row['location_id'];
                                        $location_name = $location_row['location_name'];
                                        $loc_selected = ($fan_location_id == $location_id) ? 'selected' : '';
                                        echo "<option value='$location_id' $loc_selected>" . htmlspecialchars($location_name) . "</option>";
                                      }
                                      ?>
                          
                                    </select>
                                </td>
                                <td>
                                    <select name="customerChange">
                                        <?php
                                        $customer_query = "SELECT customer_id, customer_name FROM customers";
                                        $customer_result = mysqli_query($db, $customer_query);

                                        while ($customer_row = mysqli_fetch_assoc($customer_result)) {
                                          $customer_id = $customer_row['customer_id'];
                                          $customer_name = $customer_row['customer_name'];

                                          // Check if the customer is the current owner of the cb
                                          $selected = ($fan_customer_id == $customer_id) ? 'selected' : '';

                                          echo "<option value='$customer_id' $selected>" . htmlspecialchars($customer_name) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td class="operations">
                                    <button type="submit" name="update" style="background-color: transparent; border: none; cursor: pointer;" value="<?php echo $fan_id; ?>">✔️</button>
                                    <a href="location-fan?delete=<?php echo $fan_id;?>" class="myLink">❌</a>
                                </td>
                            </form>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php
            } else {
                echo "<H1><center>Location does not contain any fans currently</center></H1>";
            }
        }
        ?>
    </div>
  </div>
  <?php
if(!empty($search) && $count > 0){
?>

    <div style="text-align: center;">
      <form method="POST">
        <button type="submit" name="export" style="font-family: 'Roboto'; background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 5px;">Export to Excel</button>
      </form>
    </div>

<?php
  } 
?>  
  <script src="./js/confirmation.js"></script>
  <script src="./js/additions.js"></script>

</body>
</html>

<?php
if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $d_id = (int)$_GET['delete'];
    $delete_query = mysqli_query($db, "DELETE FROM fans WHERE fan_id = '$d_id'");
    if(!$delete_query){
        echo "<h1><center>Could not delete unit!</center></h1>";
    } else {
        $previous_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
        header("Location: $previous_page");
        exit;
    }
}

if(isset($_POST['update'])){
    $fan_id = (int)$_POST['update'];
    $fan_qty_up = mysqli_real_escape_string($db, $_POST['fan-qty']);
    $fan_model_up = mysqli_real_escape_string($db, $_POST['fan-model']);
    $fan_location_up = mysqli_real_escape_string($db, $_POST['locationChange']);
    $fan_customer_name_up = mysqli_real_escape_string($db, $_POST['customerChange']);

    $update_query =
     "
            UPDATE fans
            SET
                fan_model = '$fan_model_up',
                fan_qty = '$fan_qty_up',
                location_id = '$fan_location_up',
                customer_id = '$fan_customer_name_up'
            WHERE
                fan_id = $fan_id;
    ";

    $run_uq = mysqli_query($db, $update_query);
    if(!$run_uq){
        echo "<h1><center>Error: " . mysqli_error($db) . "</center></h1>"; 
    } else {
        //header("Location: ". $_SERVER['PHP_SELF']);
        echo "<meta http-equiv='refresh' content='0'>";
        exit;
    }
}

//Export XLSX

$columns = ['fan_model', 'fan_qty', 'location_name', 'customer_name'];
$get_fans_data_xlsx =
              "

                SELECT 
                  f.fan_model, f.fan_qty, l.location_name, c.customer_name
                FROM fans f
                  JOIN locations l ON f.location_id = l.location_id
                  JOIN customers c ON f.customer_id = c.customer_id 
                WHERE 
                  l.location_id = $search;

              ";

$gfdx_result = mysqli_query($db, $get_fans_data_xlsx);
$data = [];
while($fan_row = mysqli_fetch_assoc($gfdx_result)){
  $data[] = $fan_row;
}

if(isset($_POST['export'])){
  $fan_file = 'exports/' . $data[0]['location_name'] . '_Fans_' . date('Y-m-d') . '.xlsx';
  exportTableData($data, $columns, $fan_file);
?>

<br><center><a href="<?php echo $fan_file; ?>">Download exported file</a></center>
<?php
}
?>
