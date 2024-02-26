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
  <title>CryptoVault - Miners</title>
  <link rel="icon" type="image/x-icon" href="images/warehouse.png">
  <link rel="stylesheet" href="/cryptovault/css/navbar.css">
  <link rel="stylesheet" href="/cryptovault/css/tables.css">
</head>

<body>

<?php include 'includes/navigation.php';?>

<div class="container">
    <div class="table-container">

        <?php
        if(isset($_GET['id'])){
            $search = (int)$_GET['id'];
            $get_miner_data =
              "

                SELECT 
                  m.miner_id, m.miner_sn, m.miner_model, m.customer_id , m.miner_label, m.psu_model, m.psu_sn, l.location_name, l.location_id, c.customer_id ,c.customer_name, m.miner_condition
                FROM miners m
                  JOIN locations l ON m.location_id = l.location_id
                  JOIN customers c ON m.customer_id = c.customer_id 
                WHERE 
                  c.customer_id = $search;

              ";

            $miner_result = mysqli_query($db, $get_miner_data);

            $count = mysqli_num_rows($miner_result);

            if($count > 0){
                ?>
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
                    <tbody>
                    <?php
                    while($miner_row = mysqli_fetch_assoc($miner_result)){
                        $miner_id = (int)$miner_row['miner_id'];
                        $miner_label = $miner_row['miner_label'];
                        $miner_sn = $miner_row['miner_sn'];
                        $miner_model = $miner_row['miner_model'];
                        $miner_customer_id = $miner_row['customer_id'];
                        $miner_customer_name = $miner_row['customer_name'];
                        $miner_psu_model = $miner_row['psu_model'];
                        $miner_psu_sn = $miner_row['psu_sn'];
                        $miner_location_id = $miner_row['location_id'];
                        $miner_location = $miner_row['location_name'];
                        $miner_condition = $miner_row['miner_condition'];
                        ?>
                        <tr>
                            <form method="POST" action="">
                                <td><input type="text" name="miner-label" value="<?php echo htmlspecialchars($miner_label); ?>"></td>
                                <td><input type="text" name="miner-sn" value="<?php echo htmlspecialchars($miner_sn); ?>"></td>
                                <td><input type="text" name="miner-model" value="<?php echo htmlspecialchars($miner_model); ?>"></td>
                                <td><input type="text" name="miner-psu-model" value="<?php echo htmlspecialchars($miner_psu_model); ?>"></td>
                                <td><input type="text" name="miner-psu-sn" value="<?php echo htmlspecialchars($miner_psu_sn); ?>"></td>
                                <td><input type="text" name="miner-condition" value="<?php echo htmlspecialchars($miner_condition); ?>"></td>
                                <td>
                                    <select name="locationChange">
                                      <?php
                                      $location_query = "SELECT location_id, location_name FROM locations";
                                      $location_result = mysqli_query($db, $location_query);
                                      while($location_row = mysqli_fetch_assoc($location_result)){
                                        $location_id = $location_row['location_id'];
                                        $location_name = $location_row['location_name'];
                                        $loc_selected = ($miner_location_id == $location_id) ? 'selected' : '';
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

                                          // Check if the customer is the current owner of the miner
                                          $selected = ($miner_customer_id == $customer_id) ? 'selected' : '';

                                          echo "<option value='$customer_id' $selected>" . htmlspecialchars($customer_name) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td class="operations">
                                    <button type="submit" name="update" style="background-color: transparent; border: none; cursor: pointer;" value="<?php echo $miner_id; ?>">✔️</button>
                                    <a href="customer-miner?delete=<?php echo $miner_id;?>">❌</a>
                                </td>
                            </form>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php
            } else {
                echo "<H1><center>The customer doesn't own any miners</center></H1>";
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
</body>
</html>

<?php
if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $d_id = (int)$_GET['delete'];
    $delete_query = mysqli_query($db, "DELETE FROM miners WHERE miner_id = $d_id;");
    if(!$delete_query){
        echo "<h1><center>Could not delete unit!</center></h1>";
    } else {
        $previous_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
        header("Location: $previous_page");
        exit;
    }
}

if(isset($_POST['update'])){
    $miner_id = (int)$_POST['update'];
    $miner_label_up = mysqli_real_escape_string($db, $_POST['miner-label']);
    $miner_sn_up = mysqli_real_escape_string($db, $_POST['miner-sn']);
    $miner_model_up = mysqli_real_escape_string($db, $_POST['miner-model']);
    $miner_psu_model_up = mysqli_real_escape_string($db, $_POST['miner-psu-model']);
    $miner_psu_sn_up = mysqli_real_escape_string($db, $_POST['miner-psu-sn']);
    $miner_location_up = mysqli_real_escape_string($db, $_POST['locationChange']);
    $miner_customer_name_up = mysqli_real_escape_string($db, $_POST['customerChange']);
    $miner_condition = mysqli_real_escape_string($db, $_POST['miner-condition']);

    $update_query = "
            UPDATE miners 
            SET
                miner_label = '$miner_label_up',
                miner_sn = '$miner_sn_up',
                miner_model = '$miner_model_up',
                psu_model = '$miner_psu_model_up',
                psu_sn = '$miner_psu_sn_up',
                miner_condition = '$miner_condition',
                location_id = '$miner_location_up',
                customer_id = '$miner_customer_name_up'
            WHERE
                miner_id = $miner_id;
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

//Export to XLSX function

$columns = ['miner_label', 'miner_sn', 'miner_model', 'psu_model', 'psu_sn', 'miner_condition', 'location_name', 'customer_name'];
$get_miner_data_xlsx =
              "

                SELECT 
                  m.miner_label, m.miner_sn, m.miner_model, m.psu_model, m.psu_sn, m.miner_condition, l.location_name, c.customer_name
                FROM miners m
                  JOIN locations l ON m.location_id = l.location_id
                  JOIN customers c ON m.customer_id = c.customer_id 
                WHERE 
                  c.customer_id = $search;

              ";

$gmdx_result = mysqli_query($db, $get_miner_data_xlsx);
$data = [];
while($miner_row = mysqli_fetch_assoc($gmdx_result)){
  $data[] = $miner_row;
}

if(isset($_POST['export'])){
  $miner_file = 'exports/' . $data[0]['customer_name'] . '_Miners_' . date('Y-m-d') . '.xlsx';
  exportTableData($data, $columns, $miner_file);
?>

<br><center><a href="<?php echo $miner_file; ?>">Download exported file</a></center>
<?php
}

?>
