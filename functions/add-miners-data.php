<?php

require '../includes/db.php';

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


function mapNameToId($name, $nameMap, $defaultId) {
    foreach ($nameMap as $storedName => $id) {
        // Remove trailing whitespace from both names
        $trimmedName = rtrim($name);
        $trimmedStoredName = rtrim($storedName);
        
        // Use case-insensitive comparison to match names
        if (strcasecmp($trimmedName, $trimmedStoredName) === 0) {
            return $id;
        }
    }
    // If no exact match is found, return the default ID
    return $defaultId;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    for ($i = 0; isset($_POST['label-' . $i]); $i++) {

        $label = mysqli_real_escape_string($db, $_POST['label-' . $i]);
        $sn = mysqli_real_escape_string($db, $_POST['serial-number-' . $i]);
        $model = mysqli_real_escape_string($db, $_POST['model-' . $i]);
        $psu_model = mysqli_real_escape_string($db, $_POST['psu-model-' . $i]);
        $psu_sn = mysqli_real_escape_string($db, $_POST['psu-sn-' . $i]);
        $location = mysqli_real_escape_string($db, $_POST['location-' . $i]);
        $customer = mysqli_real_escape_string($db, $_POST['customer-name-' . $i]);
        $condition = mysqli_real_escape_string($db, $_POST['condition-' . $i]);

        // Map location and customer names to their corresponding IDs dynamically
        $locationId = mapNameToId($location, $locations, 1);
        $customerId = mapNameToId($customer, $customers, 5);

        $add_m_miners = "
            INSERT INTO miners(miner_label, miner_sn, miner_model, psu_model, psu_sn, location_id, customer_id, miner_condition) 
            VALUES ('$label', '$sn', '$model', '$psu_model', '$psu_sn', $locationId, $customerId, '$condition')
        ";

       if (!mysqli_query($db, $add_m_miners)) {
           die("Error adding miners: " . mysqli_error($db));
    }
    }

    header("Location: /cryptovault/index" );
    exit(); 
} else {
    echo "Form data not received!";
}
?>
