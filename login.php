<?php
session_start();
include 'database.php';

// Function to get user details (IP, OS, Browser)
function getUserDetails() {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown IP';

    // Get OS Version
    $os = 'Unknown OS';
    $osArray = ['Windows', 'Mac', 'Linux', 'Ubuntu', 'Android', 'iOS'];
    foreach ($osArray as $val) {
        if (stripos($_SERVER['HTTP_USER_AGENT'], $val) !== false) {
            $os = $val;
            break;
        }
    }

    // Get Browser
    $browser = 'Unknown Browser';
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false) $browser = 'Google Chrome';
    elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== false) $browser = 'Mozilla Firefox';
    elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== false) $browser = 'Safari';
    elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Edge') !== false) $browser = 'Microsoft Edge';

    return [$ip, $os, $browser];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Fetch user from database using prepared statement
    $stmt = $conn->prepare("SELECT * FROM user_data WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // ❌ TEMPORARY FIX: Directly Compare Password (Remove Later)
        if ($password === $row["password"]) {
            // Store user session
            $_SESSION["username"] = $username;

            // Get user details
            list($ip_address, $os_version, $browser) = getUserDetails();

            // Log user login activity
            $stmt = $conn->prepare("UPDATE user_data SET action='login', action_timestamp=NOW(), ip_address=?, os_version=?, browser=? WHERE username=?");
            $stmt->bind_param("ssss", $ip_address, $os_version, $browser, $username);
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
