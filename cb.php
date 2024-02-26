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
<html>
  <head>
    <title>CryptoVault - CB</title>
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
            $get_cb_data =
                "

                SELECT 
                  cb.cb_id, cb.cb_model, cb.cb_sn, cb.ticket, l.location_name, l.location_id, c.customer_name, c.customer_id, cb.cb_condition
                FROM control_boards cb
                  JOIN locations l ON cb.location_id = l.location_id
                  JOIN customers c ON cb.customer_id = c.customer_id 
                WHERE 
                  cb.cb_sn LIKE '%$search%' OR cb.cb_model LIKE '%$search%' OR cb.cb_condition LIKE '%$search%';

                ";

            $cb_result = mysqli_query($db, $get_cb_data);

            $count = mysqli_num_rows($cb_result);

            if($count > 0){
              ?>
              <table>
          <thead>
            <tr>
              <th>Serial Number</th>
              <th>Model</th>
              <th>Location</th>
              <th>Customer Name</th>
              <th>Condition</th>
              <th>Ticket</th>
              <th>Operations</th>
            </tr>
          </thead>
          <tbody>
            <?php
              while($cb_row = mysqli_fetch_assoc($cb_result)){
                $cb_id = (int)$cb_row['cb_id'];
                $cb_model = $cb_row['cb_model'];
                $cb_sn = $cb_row['cb_sn'];
                $cb_ticket = $cb_row['ticket'];
                $cb_location_id = $cb_row['location_id'];
                $cb_location = $cb_row['location_name'];
                $cb_customer_id = $cb_row['customer_id'];
                $cb_customer = $cb_row['customer_name'];
                $cb_condition = $cb_row['cb_condition'];
              ?>
              <form method="POST" action="">
              <tr>
                <td><input type="text" name="cb-sn" value="<?php echo htmlspecialchars($cb_sn); ?>"></td>
                <td><input type="text" name="cb-model" value="<?php echo htmlspecialchars($cb_model); ?>"></td>

                                    <td>
                                        <select name="locationChange">
                                          <?php
                                          $location_query = "SELECT location_id, location_name FROM locations";
                                          $location_result = mysqli_query($db, $location_query);
                                          while($location_row = mysqli_fetch_assoc($location_result)){
                                            $location_id = $location_row['location_id'];
                                            $location_name = $location_row['location_name'];
                                            $loc_selected = ($cb_location_id == $location_id) ? 'selected' : '';
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
                                              $selected = ($cb_customer_id == $customer_id) ? 'selected' : '';

                                              echo "<option value='$customer_id' $selected>" . htmlspecialchars($customer_name) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td><input type="text"  name="cb-condition" value="<?php echo htmlspecialchars($cb_condition);?>"></td>

                                    <td><input type="text" style="width: 90px;" name="cb-ticket" value="<?php echo htmlspecialchars($cb_ticket);?>"></td>
                 <td class="operations">
                  <button type="submit" name="update" style="background-color: transparent; border: none; cursor: pointer;" value="<?php echo $cb_id; ?>">✔️</button>
                  <a href="cb?delete=<?php echo $cb_id;?>" class="myLink">❌</a>
                </td>
              </tr>
              </form>
              <?php } ?>
            </tbody>
          </table>
        <?php
        } else {
          echo "<H1><center>No control boards found</center></H1>";
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
            <button id="updateAllCbs" style="font-family: 'Roboto'; background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 5px;">Update All Fields</button>
          </div>
        </div>

      <?php
        } 
      ?>  

  <script src="./js/updateAllCFields.js"></script>
  <script src="./js/confirmation.js"></script>

</body>
</html>


<?php

//Update date
if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $d_id = (int)$_GET['delete'];
    $delete_query = mysqli_query($db, "DELETE FROM control_boards WHERE cb_id = $d_id");
    if(!$delete_query){
        echo "<h1><center>Could not delete CB!</center></h1>";
    } else {
        $previous_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
        header("Location: $previous_page");
        exit;
    }
} 

//Update data
if(isset($_POST['update'])){
    $cb_id = (int)$_POST['update'];
    $cb_sn_up = mysqli_real_escape_string($db, $_POST['cb-sn']);
    $cb_model_up = mysqli_real_escape_string($db, $_POST['cb-model']);
    $cb_ticket_up = mysqli_real_escape_string($db, $_POST['cb-ticket']);
    $cb_location_up = mysqli_real_escape_string($db, $_POST['locationChange']);
    $cb_customer_name_up = mysqli_real_escape_string($db, $_POST['customerChange']);
    $cb_condition = mysqli_real_escape_string($db, $_POST['cb-condition']);

    $update_query = 
            "

            UPDATE control_boards
            SET
                cb_model = '$cb_model_up',
                cb_sn = '$cb_sn_up',
                location_id = '$cb_location_up',
                customer_id = '$cb_customer_name_up',
                ticket = '$cb_ticket_up',
                cb_condition = '$cb_condition'
            WHERE
                cb_id = $cb_id;

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

//Export XLSX
$columns = ['cb_sn', 'cb_model', 'ticket', 'location_name', 'customer_name'];
$get_cbs_data_xlsx =
              "

                SELECT 
                  cb.cb_sn, cb.cb_model, cb.ticket, l.location_name, c.customer_name
                FROM control_boards cb
                  JOIN locations l ON cb.location_id = l.location_id
                  JOIN customers c ON cb.customer_id = c.customer_id;

              ";

$gcdx_result = mysqli_query($db, $get_cbs_data_xlsx);
$data = [];
while($cb_row = mysqli_fetch_assoc($gcdx_result)){
  $data[] = $cb_row;
}

if(isset($_POST['export'])){
  $cb_file =  'exports/' . 'CBs_'  . date('Y-m-d') . '.xlsx';
  exportTableData($data, $columns, $cb_file);
?>

<br><center><a href="<?php echo $cb_file; ?>">Download exported file</a></center>
<?php
}

?>
