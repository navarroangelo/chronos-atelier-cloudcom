<?php
    $db_server = "chronosatelierdb.c18c2qg08jbh.ap-southeast-1.rds.amazonaws.com";
    $db_user = "cardsadmin";
    $db_password = "Chronosatelierdb2024";
    $db_name = "chronosatelierdb";

$conn = new mysqli($db_server, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        echo "Login successful!";
    } else {
        echo "Invalid credentials!";
    }
}
?>
<form method="post">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Login</button>
</form>
