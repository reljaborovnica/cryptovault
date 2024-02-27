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
    <title>CryptoVault - Home</title>
    <link rel="icon" type="image/x-icon" href="images/warehouse.png">
    <link rel="stylesheet" href="./css/navbar.css"/>
    <link rel="stylesheet" href="./css/tables.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        body {
            background-color: #222;
            color: #ffffffdb;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .table-container {
            width: 80%;
            margin-bottom: 20px;
            margin-right: 20px;
            background-color: #333;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table-container h2 {
            background-color: #555;
            color: #fff;
            padding: 10px;
            margin: 0;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #444;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #333;
            color: #fff;
        }
        tr.clickable {
          cursor: pointer;
        }
    </style>
</head>
<body>

<?php include 'includes/navigation.php';?>
    
<div class="container">
    <?php $date = date('l, F j, Y');?>
    <h1>Welcome back, <?php echo strtoupper($_SESSION['username']) . ' Team';?>! It's <?php echo $date;?>. Have a great day!</h1>
        <div class="table-container">
        <h2>Inventory Summary</h2>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 

                $total_miners = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as total FROM miners"))['total'];
                $total_psus = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as total FROM power_supplies"))['total'];
                $total_cbs = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as total FROM control_boards"))['total'];
                $total_fans = mysqli_fetch_assoc(mysqli_query($db, "SELECT SUM(fan_qty) as total FROM fans"))['total'];
                $total_customers = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as total FROM customers"))['total'];
                $total_locations = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as total FROM locations"))['total'];
                ?>

                <tr class="clickable" onclick="window.location='miner?search='">
                  <td>Miners</td>
                  <td><?php echo $total_miners;?></td>
                </tr>
                <tr class="clickable" onclick="window.location='psu?search='">
                  <td>Power Supplies</td>
                  <td><?php echo $total_psus;?></td>
                </tr>
                <tr class="clickable" onclick="window.location='cb?search='">
                  <td>Control Boards</td>
                  <td><?php echo $total_cbs;?></td>
                </tr>
                <tr>
                  <td>Fans</td>
                  <td><?php echo $total_fans;?></td>
                </tr>
                <tr class="clickable" onclick="window.location='customers'">
                  <td>Customers</td>
                  <td><?php echo $total_customers;?></td>
                </tr>
                <tr class="clickable" onclick="window.location='locations'">
                  <td>Locations</td>
                  <td><?php echo $total_locations;?></td>
                </tr>

              
            </tbody>
        </table>
    </div>
    <div class="table-container">

        <h2>Latest added miners - Top 10 </h2>
        <table>
            <thead>
                <tr>
                    <th>Label</th>
                    <th>Serial Number</th>
                    <th>Model</th>
                    <th>PSU Model</th>
                    <th>PSU SN</th>
                    <th>Customer Name</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $get_lat_miners = 
                "
                SELECT m.miner_id, m.miner_label, m.miner_model, m.miner_sn, m.psu_model, m.psu_sn, c.customer_name
                FROM miners m 
                JOIN customers c ON m.customer_id = c.customer_id 
                ORDER BY m.miner_id DESC
                LIMIT 10
            ";

                $q_glm = mysqli_query($db, $get_lat_miners);

                while($fetch_glm = mysqli_fetch_assoc($q_glm)){
                    $miner_id = $fetch_glm['miner_id'];
                    $miner_label = $fetch_glm['miner_label'];
                    $miner_model = $fetch_glm['miner_model'];
                    $miner_sn = $fetch_glm['miner_sn'];
                    $miner_psu_model = $fetch_glm['psu_model'];
                    $miner_psu_sn = $fetch_glm['psu_sn'];
                    $customer_name = $fetch_glm['customer_name'];
                ?>
                <tr class="clickable" onclick="window.location='miner/search/<?php echo $miner_sn;?>'">
                    <td><?php echo htmlspecialchars($miner_label);?></td>
                    <td><?php echo htmlspecialchars($miner_sn);?></td>
                    <td><?php echo htmlspecialchars($miner_model);?></td>
                    <td><?php echo htmlspecialchars($miner_psu_model);?></td>
                    <td><?php echo htmlspecialchars($miner_psu_sn);?></td>
                    <td><?php echo htmlspecialchars($customer_name);?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="table-container">
        <h2>Latest added power supplies - Top 10</h2>
        <table>
            <thead>
                <tr>
                    <th>Serial Number</th>
                    <th>Model</th>
                    <th>Customer Name</th>
                </tr>
            </thead>
            <tbody>
                <?php 
            $get_lat_psus = 
            "
                SELECT ps.psu_id, ps.psu_model, ps.psu_sn, c.customer_name
                FROM power_supplies ps 
                JOIN customers c ON ps.customer_id = c.customer_id 
                ORDER BY ps.psu_id DESC
                LIMIT 10;
            ";

                $q_glp = mysqli_query($db, $get_lat_psus);

                while($fetch_glp = mysqli_fetch_assoc($q_glp)){
                    $psu_id = $fetch_glp['psu_id'];
                    $psu_model = $fetch_glp['psu_model'];
                    $psu_sn = $fetch_glp['psu_sn'];
                    $customer_name = $fetch_glp['customer_name'];
                ?>
                <tr class="clickable" onclick="window.location='psu/search/<?php echo $psu_sn;?>'">
                    <td><a href="psu/search/<?php echo $psu_sn;?>"><?php echo $psu_sn;?></td>
                    <td><a href="psu/search/<?php echo $psu_sn;?>"><?php echo $psu_model;?></td>
                    <td><a href="psu/search/<?php echo $psu_sn;?>"><?php echo $customer_name;?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="table-container">
        <h2>Latest added control boards - Top 10</h2>
        <table>
            <thead>
                <tr>
                    <th>Serial Number</th>
                    <th>Model</th>
                    <th>Customer Name</th>
                </tr>
            </thead>
            <tbody>
                <?php 
            $get_lat_cbs = 
            "
                SELECT cb.cb_id, cb.cb_model, cb.cb_sn, c.customer_name
                FROM control_boards cb 
                JOIN customers c ON cb.customer_id = c.customer_id 
                ORDER BY cb.cb_id DESC
                LIMIT 10;
            ";

                $q_glc = mysqli_query($db, $get_lat_cbs);

                while($fetch_glc = mysqli_fetch_assoc($q_glc)){
                    $cb_id = $fetch_glc['cb_id'];
                    $cb_model = $fetch_glc['cb_model'];
                    $cb_sn = $fetch_glc['cb_sn'];
                    $customer_name = $fetch_glc['customer_name'];
                ?>
                <tr class="clickable" onclick="window.location='cb/search/<?php echo $cb_sn;?>'">
                    <td><a href="cb/search/<?php echo $cb_sn;?>"><?php echo $cb_sn;?></td>
                    <td><a href="cb/search/<?php echo $cb_sn;?>"><?php echo $cb_model;?></td>
                    <td><a href="cb/search/<?php echo $cb_sn;?>"><?php echo $customer_name;?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
    <script src="./js/additions.js"></script>

</body>
</html>
