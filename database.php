<?php
    $db_server = "chronosatelierdb.c18c2qg08jbh.ap-southeast-1.rds.amazonaws.com";
    $db_user = "cardsadmin";
    $db_password = "Chronosatelierdb2024";
    $db_name = "chronos_atelier";


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
