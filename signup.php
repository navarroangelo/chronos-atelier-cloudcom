<?php
session_start();
include 'database.php';
include 'user-details.php';

$username_error = "";
$signup_success = false;
$errors = [];

// Handle AJAX request for username check
if (isset($_GET['username_check'])) {
    $username = $_GET['username_check'];
    $stmt = $conn->prepare("SELECT * FROM user_data WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    echo ($result->num_rows > 0) ? "taken" : "available";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    
    // Check if username is taken
    $stmt = $conn->prepare("SELECT * FROM user_data WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $username_error = "Username already exists. Please choose another.";
    }

    // Password validation
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain at least one uppercase letter.";
    }
    if (!preg_match('/\d/', $password)) {
        $errors[] = "Password must contain at least one number.";
    }
    if (!preg_match('/[\W]/', $password)) {
        $errors[] = "Password must contain at least one special character.";
    }

    if (empty($errors) && empty($username_error)) {
        // Get user details
        list($ip_address, $os_version, $browser, $processor, $location ,$user_agent) = getUserDetails();
        
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO user_data (username, password, ip_address, os_version, browser, processor, location, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $username, $hashed_password, $ip_address, $os_version, $browser, $processor, $location, $user_agent);

        if ($stmt->execute()) {
            $signup_success = true;
        } else {
            $errors[] = "Database error: " . $stmt->error;
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
    <link rel="stylesheet" href="src/assets/style/signup-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="signup-container">
        <div class="brand-header">
            <h1>SIGN-UP TO </h1>
            <h2>CHRONOS ATELIER</h2>
            <p>Create Your Account</p>
        </div>
        
        <?php if ($signup_success): ?>
            <div class="message success">
                Signup successful! Redirecting to login...
            </div>
            <script>
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 2000);
            </script>
        <?php else: ?>
            <form class="signup-form" id="signupForm" action="" method="POST">
                <div class="form-group">
                    <input type="text" id="username" name="username" placeholder="Username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                    <p id="usernameError" class="error" style="display: <?php echo !empty($username_error) ? 'block' : 'none'; ?>;"> <?php echo $username_error; ?> </p>
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <p id="lengthError" class="error">• Password must contain at least 8 characters.</p>
                    <p id="uppercaseError" class="error">• Password must contain at least 1 Uppercase Letter.</p>
                    <p id="numberError" class="error">• Password must contain at least 1 Number.</p>
                    <p id="specialCharError" class="error">•Password must contain at least 1 Special Character.</p>
                    <p id="validMessage" class="valid">✓ Password meets all requirements</p>
                </div>
                <input type="hidden" id="processorInput" name="processor" />
                <button type="submit">Sign Up</button>
            </form>
        <?php endif; ?>
    </div>

    <script>
        // Real-time username check with AJAX
        document.getElementById("username").addEventListener("input", function() {
            const username = this.value;
            const usernameError = document.getElementById("usernameError");
            if (username.length > 0) {
                fetch(`signup.php?username_check=${username}`)
                    .then(response => response.text())
                    .then(data => {
                        if (data === "taken") {
                            usernameError.style.display = "block";
                            usernameError.textContent = "Username already exists. Please choose another.";
                        } else {
                            usernameError.style.display = "none";
                        }
                    });
            } else {
                usernameError.style.display = "none";
            }
        });

        // Password real-time validation
        document.getElementById("password").addEventListener("input", function() {
            const password = this.value;
            const lengthError = document.getElementById("lengthError");
            const uppercaseError = document.getElementById("uppercaseError");
            const numberError = document.getElementById("numberError");
            const specialCharError = document.getElementById("specialCharError");
            const validMessage = document.getElementById("validMessage");

            [lengthError, uppercaseError, numberError, specialCharError, validMessage].forEach(el => {
                if (el) el.style.display = "none";
            });

            const hasLength = password.length >= 8;
            const hasUppercase = /[A-Z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSpecialChar = /[\W]/.test(password);

            if (!hasLength) lengthError.style.display = "block";
            if (!hasUppercase) uppercaseError.style.display = "block";
            if (!hasNumber) numberError.style.display = "block";
            if (!hasSpecialChar) specialCharError.style.display = "block";
            
            if (hasLength && hasUppercase && hasNumber && hasSpecialChar) {
                validMessage.style.display = "block";
            }
        });

        // Processor/GPU detection using WebGL
        function getProcessorDetails() {
            var canvas = document.createElement('canvas');
            var gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
            var debugInfo = gl.getExtension('WEBGL_debug_renderer_info');
            
            if (debugInfo) {
                var renderer = gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL);
                var vendor = gl.getParameter(debugInfo.UNMASKED_VENDOR_WEBGL);
                // Return detected processor/GPU info
                return `${renderer}`;
            } else {
                return "Processor/Graphics info not available";
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            var processorDetails = getProcessorDetails();
            document.getElementById('processorInput').value = processorDetails;
        });
    </script>
</body>
</html>
