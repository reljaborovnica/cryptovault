<?php

require '../includes/db.php';

error_reporting(E_ALL); 
ini_set('display_errors', 1);

$fetch_all_location = "SELECT * FROM locations;";
$fetch_all_customers = "SELECT * FROM customers;";

$q_fal = mysqli_query($db, $fetch_all_location);
$q_fac = mysqli_query($db, $fetch_all_customers);

// Fetch locations and customers from the database and store them in associative arrays
$locations = [];
while ($row = mysqli_fetch_assoc($q_fal)) {
    $locations[$row['location_name']] = $row['location_id'];
}

$customers = [];
while ($row = mysqli_fetch_assoc($q_fac)) {
    $customers[$row['customer_name']] = $row['customer_id'];
}

// Function to map names to IDs dynamically
function mapNameToId($name, $nameMap, $defaultId) {
    if (isset($nameMap[$name])) {
        return $nameMap[$name];
    } else {
        return $defaultId;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    for ($i = 0; isset($_POST['serial-number-' . $i]); $i++) {
        $sn = mysqli_real_escape_string($db, $_POST['serial-number-' . $i]);
        $model = mysqli_real_escape_string($db, $_POST['model-' . $i]);
        $location = mysqli_real_escape_string($db, $_POST['location-' . $i]);
        $customer = mysqli_real_escape_string($db, $_POST['customer-name-' . $i]);
        $condition = mysqli_real_escape_string($db, $_POST['condition-' . $i]);

        // Map location and customer names to their corresponding IDs dynamically
        $locationId = mapNameToId($location, $locations, 3);
        $customerId = mapNameToId($customer, $customers, 4);

        $add_m_miners = "
            INSERT INTO power_supplies(psu_sn, psu_model, location_id, customer_id, psu_condition) 
            VALUES ('$sn', '$model', $locationId, $customerId, '$condition')
        ";

        if (!mysqli_query($db, $add_m_miners)) {
            die("Error adding miners: " . mysqli_error($db));
        }
    }

    header("Location: /cryptovault/index.php" );
    exit(); 
} else {
    echo "Form data not received!";
}
?>

