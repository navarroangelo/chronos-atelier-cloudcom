<?php
    $db_server = "<endpoint>";
    $db_user = "<master user>";
    $db_password = "password";
    $db_name = "dbname of rds";

    try {
        $conn = new mysqli($db_server, $db_user, $db_password, $db_name);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    } 
    catch (Exception $e) {
        die("Could not connect: " . $e->getMessage());
    }
?>