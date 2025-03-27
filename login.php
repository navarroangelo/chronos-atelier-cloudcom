<?php
session_start();
include 'database.php';
include 'user-details.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM user_data WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($password === $row["password"]) {
            $_SESSION["username"] = $username;

            list($ip_address, $os_version, $browser, $processor, $location) = getUserDetails();

            $stmt = $conn->prepare("UPDATE user_data SET action='login', action_timestamp=NOW(), ip_address=?, os_version=?, browser=?, processor=?, location=? WHERE username=?");
            $stmt->bind_param("ssssss", $ip_address, $os_version, $browser, $processor, $location, $username);
            $stmt->execute();

            echo "<p style='color:green;'>Login successful! Welcome, " . htmlspecialchars($username) . ".</p>";
        } else {
            echo "<p style='color:red;'>Invalid password.</p>";
        }
    } else {
        echo "<p style='color:red;'>User not found.</p>";
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
    <title>Login - Chronos Atelier</title>
</head>
<body>
    <h2>Login to Chronos Atelier</h2>
    <form action="" method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>