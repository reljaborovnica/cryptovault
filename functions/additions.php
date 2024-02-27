<?php
include '../includes/db.php';


function u_online() {
    global $db;
    session_start(); 
    $session = session_id();
    $time = time();
    $time_out_s = 30;
    $time_out = $time - $time_out_s;

    $query = "SELECT * FROM users_online WHERE u_session = '$session'";
    $r_q = mysqli_query($db, $query);
    $count = mysqli_num_rows($r_q);

    if ($count == 0) {
        mysqli_query($db, "INSERT INTO users_online(u_session, u_time) VALUES('$session', '$time')");
    } else {
        mysqli_query($db, "UPDATE users_online SET u_time = '$time' WHERE u_session = '$session'");
    }

    $users_online = mysqli_query($db, "SELECT * FROM users_online WHERE u_time > '$time_out'");
    $count_user = mysqli_num_rows($users_online);
    return $count_user;
}

if (isset($_GET['onlineusers'])) {
    echo u_online();
}
?>

