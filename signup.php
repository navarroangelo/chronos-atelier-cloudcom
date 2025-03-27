<?php
session_start();
include 'database.php';
include 'user-details.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    list($ip_address, $os_version, $browser, $processor, $location) = getUserDetails();

    $stmt = $conn->prepare("SELECT * FROM user_data WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<p style='color:red;'>Username already exists.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO user_data (username, password, email, contact_number, ip_address, os_version, browser, processor, location) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $username, $password, $email, $contact_number, $ip_address, $os_version, $browser, $processor, $location);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Signup successful! You can now <a href='login.php'>login</a>.</p>";
        } else {
            echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Chronos Atelier</title>
</head>
<body>
    <h2>Signup for Chronos Atelier</h2>
    <form action="" method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Signup</button>
    </form>
</body>
</html>