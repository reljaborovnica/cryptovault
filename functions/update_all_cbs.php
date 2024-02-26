<?php

require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        isset($_POST['cbId']) &&
        isset($_POST['cbSn']) &&
        isset($_POST['cbModel']) &&
        isset($_POST['cbCondition']) &&
        isset($_POST['cbTicket']) &&
        isset($_POST['locationId']) &&
        isset($_POST['customerId'])
    ) {
        $cbId = (int)$_POST['cbId'];
        $cbSn = mysqli_real_escape_string($db, $_POST['cbSn']);
        $cbModel = mysqli_real_escape_string($db, $_POST['cbModel']);
        $cbCondition = mysqli_real_escape_string($db, $_POST['cbCondition']);
        $cbTicket = mysqli_real_escape_string($db, $_POST['cbTicket']);
        $locationId = (int)$_POST['locationId'];
        $customerId = (int)$_POST['customerId'];

        $update_query =
            "
            UPDATE control_boards
            SET
                cb_sn = '$cbSn',
                cb_model = '$cbModel',
                cb_sn = '$cbSn',
                location_id = $locationId,
                customer_id = $customerId,
                cb_condition = '$cbCondition',
                ticket = '$cbTicket'
            WHERE
                cb_id = $cbId;
            ";

        $run_uq = mysqli_query($db, $update_query);

        if (!$run_uq) {
            // Handle database error
            $response = array("status" => "error", "message" => "Database error: " . mysqli_error($db));
            echo json_encode($response);
        } else {
            // Send success response
            $response = array("status" => "success", "message" => "Fields updated successfully");
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
