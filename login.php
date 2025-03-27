<?php
session_start();
include 'database.php';
include 'user-details.php';

$login_message = '';
$message_class = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM user_data WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Verify hashed password
        if (password_verify($password, $row["password"])) {
            $_SESSION["username"] = $username;

            list($ip_address, $os_version, $browser, $processor, $location) = getUserDetails();

            // Update user login details
            $stmt = $conn->prepare("UPDATE user_data SET action='login', action_timestamp=NOW(), ip_address=?, os_version=?, browser=?, processor=?, location=? WHERE username=?");
            $stmt->bind_param("ssssss", $ip_address, $os_version, $browser, $processor, $location, $username);
            $stmt->execute();

            // Redirect to index.php after successful login
            header("Location: index.php");
            exit();
        } else {
            $login_message = "Invalid password.";
            $message_class = "error";
        }
    } else {
        $login_message = "User not found.";
        $message_class = "error";
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
    <link rel="stylesheet" href="src/assets/style/login-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script>
        // Function to display error messages dynamically
        function showError(message, type) {
            let messageBox = document.querySelector('.message');
            messageBox.classList.add('active');
            messageBox.classList.remove('success', 'error');
            messageBox.classList.add(type);
            messageBox.innerHTML = message;
        }

        // Function to validate input as the user types
        function validateInput() {
            let username = document.querySelector('input[name="username"]').value;
            let password = document.querySelector('input[name="password"]').value;

            if (username && password) {
                <?php if ($message_class == 'error') { ?>
                    showError("<?php echo $login_message; ?>", "<?php echo $message_class; ?>");
                <?php } ?>
            } else {
                document.querySelector('.message').classList.remove('active');
            }
        }

        // Function to check username availability dynamically
        function checkUsername() {
            let username = document.querySelector('input[name="username"]').value;
            
            if (username) {
                <?php if ($message_class == 'error' && strpos($login_message, 'User not found') !== false) { ?>
                    showError("User not found", "error");
                <?php } ?>
            } else {
                document.querySelector('.message').classList.remove('active');
            }
        }
    </script>
</head>
<body>
    <!-- Login Container -->
    <div class="login-container">
        <div class="brand-header">
            <h1>WELCOME TO CHRONOS</h1>
            <h2>ATELIER</h2>
            <p>Time to Explore</p>
        </div>
        
        <form class="login-form" action="" method="POST">
            <input type="text" name="username" placeholder="Username" required onkeyup="checkUsername()">
            <input type="password" name="password" placeholder="Password" required onkeyup="validateInput()">
            <button type="submit">Login</button>
        </form>
        
        <!-- Message display area -->
        <div class="message">
            <?php echo $login_message ? $login_message : ''; ?>
        </div>
    </div>
</body>
</html>
