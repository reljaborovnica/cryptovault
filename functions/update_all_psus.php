<?php

require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        isset($_POST['psuId']) &&
        isset($_POST['psuSn']) &&
        isset($_POST['psuModel']) &&
        isset($_POST['psuCondition']) &&
        isset($_POST['psuTicket']) &&
        isset($_POST['locationId']) &&
        isset($_POST['customerId'])
    ) {
        $psuId = (int)$_POST['psuId'];
        $psuSn = mysqli_real_escape_string($db, $_POST['psuSn']);
        $psuModel = mysqli_real_escape_string($db, $_POST['psuModel']);
        $psuCondition = mysqli_real_escape_string($db, $_POST['psuCondition']);
        $psuTicket = mysqli_real_escape_string($db, $_POST['psuTicket']);
        $locationId = (int)$_POST['locationId'];
        $customerId = (int)$_POST['customerId'];

        $update_query =
            "
            UPDATE power_supplies
            SET
                psu_sn = '$psuSn',
                psu_model = '$psuModel',
                psu_sn = '$psuSn',
                location_id = $locationId,
                customer_id = $customerId,
                psu_condition = '$psuCondition',
                ticket = '$psuTicket'
            WHERE
                psu_id = $psuId;
            ";

        $run_uq = mysqli_query($db, $update_query);

        if (!$run_uq) {
            // Handle database error
            $response = array("status" => "error", "message" => "Database error: " . mysqli_error($db));
            echo json_encode($response);
        } else {
            // Send success response
            $response = array("status" => "success", "message" => "Fields updated successfully");
              echo "<script>alert('Updated successfully!');</script>";

            echo json_encode($response);
        }
    } else {
        // Required fields are missing
        $response = array("status" => "error", "message" => "Missing required fields");
        echo json_encode($response);
    }
} else {
    // Request is not made via POST method
    $response = array("status" => "error", "message" => "Invalid request method");
    echo json_encode($response);
}

?>
