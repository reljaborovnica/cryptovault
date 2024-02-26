<?php

require 'includes/db.php'; 
require 'functions/export.php';

session_start();
if(!isset($_SESSION['username'])){
  header("Location: login.php");
  exit();
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>CryptoVault - PSU</title>
    <link rel="icon" type="image/x-icon" href="images/warehouse.png">
    <link rel="stylesheet" href="/cryptovault/css/navbar.css"/>
    <link rel="stylesheet" href="/cryptovault/css/tables.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  </head>
  <body>

    <?php include 'includes/navigation.php';?>

    <div class="container">
      <div class="table-container">
        <?php
          if(isset($_GET['search'])){
            $search = mysqli_real_escape_string($db, $_GET['search']);
            $get_psu_data =
                "

                SELECT 
                  ps.psu_id, ps.psu_model, ps.psu_sn, ps.ticket, l.location_name, l.location_id, c.customer_name, c.customer_id, ps.psu_condition
                FROM power_supplies ps
                  JOIN locations l ON ps.location_id = l.location_id
                  JOIN customers c ON ps.customer_id = c.customer_id 
                WHERE 
                  ps.psu_sn LIKE '%$search%' OR ps.psu_model LIKE '%$search%' OR ps.psu_condition LIKE '%$search%';

                ";

            $psu_result = mysqli_query($db, $get_psu_data);

            $count = mysqli_num_rows($psu_result);

            if($count > 0){
              ?>
              <table>
          <thead>
            <tr>
              <th>Serial Number</th>
              <th>Model</th>
              <th>Condition</th>
              <th>Location</th>
              <th>Customer Name</th>
              <th>Ticket</th>
              <th>Operations</th>
            </tr>
          </thead>
          <tbody>
            <?php
              while($psu_row = mysqli_fetch_assoc($psu_result)){
                $psu_id = (int)$psu_row['psu_id'];
                $psu_model = $psu_row['psu_model'];
                $psu_sn = $psu_row['psu_sn'];
                $psu_ticket = $psu_row['ticket'];
                $psu_location_id = $psu_row['location_id'];
                $psu_location = $psu_row['location_name'];
                $psu_customer_id = $psu_row['customer_id'];
                $psu_customer = $psu_row['customer_name'];
                $psu_condition = $psu_row['psu_condition'];
              ?>
              <form method="POST" action="">
              <tr>
                <td><input type="text" name="psu-sn" value="<?php echo htmlspecialchars($psu_sn); ?>"></td>
                <td><input type="text" name="psu-model" value="<?php echo htmlspecialchars($psu_model); ?>"></td>
                <td><input type="text"  name="psu-condition" value="<?php echo htmlspecialchars($psu_condition);?>"></td>


                                    <td>
                                        <select name="locationChange">
                                          <?php
                                          $location_query = "SELECT location_id, location_name FROM locations";
                                          $location_result = mysqli_query($db, $location_query);
                                          while($location_row = mysqli_fetch_assoc($location_result)){
                                            $location_id = $location_row['location_id'];
                                            $location_name = $location_row['location_name'];
                                            $loc_selected = ($psu_location_id == $location_id) ? 'selected' : '';
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

                                              // Check if the customer is the current owner of the psu
                                              $selected = ($psu_customer_id == $customer_id) ? 'selected' : '';

                                              echo "<option value='$customer_id' $selected>" . htmlspecialchars($customer_name) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td><input type="text" style="width: 90px;" name="psu-ticket" value="<?php echo htmlspecialchars($psu_ticket);?>"></td>
                 <td class="operations">
                  <button type="submit" name="update" style="background-color: transparent; border: none; cursor: pointer;" value="<?php echo $psu_id; ?>">✔️</button>
                  <a href="psu?delete=<?php echo $psu_id;?>" class="myLink">❌</a>
                </td>
              </tr>
              </form>
              <?php } ?>
            </tbody>
          </table>
        <?php
        } else {
          echo "<H1><center>No power supplies found</center></H1>";
        }
      }
      ?>
    </div>
  </div>
<?php
    if(empty($search) && $count > 0){
    ?>

    <div style="text-align: center;">

      <div style="text-align: center;">
        <div style="display: inline-block;">
          <form method="POST">
            <button type="submit" name="export" style="font-family: 'Roboto'; background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 5px;">Export to Excel</button>
          </form>
        </div>
        <div style="display: inline-block; margin-left: 10px;">
          <div style="text-align: center;">
            <button id="updateAllPsus" style="font-family: 'Roboto'; background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 5px;">Update All Fields</button>
          </div>
        </div>

      <?php
        } 
      ?>  


    </div>

    <script src="./js/updateAllPFields.js"></script> 
    <script src="./js/confirmation.js"></script>
  </body>
</html>

<?php
//Delete data
if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $d_id = (int)$_GET['delete'];
    $delete_query = mysqli_query($db, "DELETE FROM power_supplies WHERE psu_id = $d_id");
    if(!$delete_query){
        echo "<h1><center>Could not delete PSU!</center></h1>";
    } else {
        $previous_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
        header("Location: $previous_page");
        exit;
    }
}

//Update data
if(isset($_POST['update'])){
    $psu_id = (int)$_POST['update'];
    $psu_sn_up = mysqli_real_escape_string($db, $_POST['psu-sn']);
    $psu_model_up = mysqli_real_escape_string($db, $_POST['psu-model']);
    $psu_ticket_up = mysqli_real_escape_string($db, $_POST['psu-ticket']);
    $psu_location_up = mysqli_real_escape_string($db, $_POST['locationChange']);
    $psu_customer_name_up = mysqli_real_escape_string($db, $_POST['customerChange']);
    $psu_condition = mysqli_real_escape_string($db, $_POST['psu-condition']);

    $update_query = 
            "

            UPDATE power_supplies
            SET
                psu_model = '$psu_model_up',
                psu_sn = '$psu_sn_up',
                location_id = '$psu_location_up',
                customer_id = '$psu_customer_name_up',
                ticket = '$psu_ticket_up',
                psu_condition = '$psu_condition'
            WHERE
                psu_id = $psu_id;

            ";

    $run_uq = mysqli_query($db, $update_query);
    if(!$run_uq){
        echo "<h1><center>Error: " . mysqli_error($db) . "</center></h1>"; 
    } else {
        // header("Location: ". $_SERVER['PHP_SELF']);
        echo "<meta http-equiv='refresh' content='0'>";

      exit;

    }
}

$columns = ['psu_model', 'psu_sn', 'psu_condition','location_name', 'customer_name', 'ticket'];
$get_psu_data_xlsx =
              "

                SELECT 
                  ps.psu_model, ps.psu_sn, ps.ticket, l.location_name, c.customer_name, ps.psu_condition
                FROM power_supplies ps
                  JOIN locations l ON ps.location_id = l.location_id
                  JOIN customers c ON ps.customer_id = c.customer_id;
              ";

$gpdx_result = mysqli_query($db, $get_psu_data_xlsx);
$data = [];
while($psu_row = mysqli_fetch_assoc($gpdx_result)){
  $data[] = $psu_row;
}

if(isset($_POST['export'])){
  $psu_file = 'exports/' . 'PSUS_' . date('Y-m-d') . '.xlsx';
  exportTableData($data, $columns, $psu_file);
?>

<br><center><a href="<?php echo $psu_file; ?>">Download exported file</a></center>
<?php
}


?>
