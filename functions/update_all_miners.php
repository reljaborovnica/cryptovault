
<?php

require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        isset($_POST['minerId']) &&
        isset($_POST['minerLabel']) &&
        isset($_POST['minerSn']) &&
        isset($_POST['minerModel']) &&
        isset($_POST['minerPsuModel']) &&
        isset($_POST['minerPsuSn']) &&
        isset($_POST['minerCondition']) &&
        isset($_POST['locationId']) &&
        isset($_POST['customerId'])
    ) {
        $minerId = (int)$_POST['minerId'];
        $minerLabel = mysqli_real_escape_string($db, $_POST['minerLabel']);
        $minerSn = mysqli_real_escape_string($db, $_POST['minerSn']);
        $minerModel = mysqli_real_escape_string($db, $_POST['minerModel']);
        $minerPsuModel = mysqli_real_escape_string($db, $_POST['minerPsuModel']);
        $minerPsuSn = mysqli_real_escape_string($db, $_POST['minerPsuSn']);
        $minerCondition = mysqli_real_escape_string($db, $_POST['minerCondition']);
        $locationId = (int)$_POST['locationId'];
        $customerId = (int)$_POST['customerId'];

        $update_query =
            "
            UPDATE miners 
            SET
                miner_label = '$minerLabel',
                miner_sn = '$minerSn',
                miner_model = '$minerModel',
                psu_model = '$minerPsuModel',
                psu_sn = '$minerPsuSn',
                location_id = $locationId,
                customer_id = $customerId,
                miner_condition = '$minerCondition'
            WHERE
                miner_id = $minerId;
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
