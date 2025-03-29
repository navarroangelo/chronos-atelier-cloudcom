<?php
session_start();
include 'database.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];


    $stmt = $conn->prepare("UPDATE user_data SET action = 'logout', action_timestamp = NOW() WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }
}


session_unset();
session_destroy();


header("Location: login.php");
exit();
?>
