<?php
session_start();
include 'database.php';
include 'user-details.php';

$username_error = "";
$password_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username)) {
        $username_error = "Username is required.";
    } elseif (empty($password)) {
        $password_error = "Password is required.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password, role FROM user_data WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $user_id = $row["id"];
            $role = $row["role"] ?? "user"; 

            if (password_verify($password, $row["password"])) {
             
                $_SESSION["user_id"] = $user_id;
                $_SESSION["username"] = $username;
                $_SESSION["role"] = $role;

           
                $updateStmt = $conn->prepare("UPDATE user_data SET action = 'login', action_timestamp = NOW() WHERE id = ?");
                if ($updateStmt) {
                    $updateStmt->bind_param("i", $user_id);
                    $updateStmt->execute();
                    $updateStmt->close();
                }

                session_write_close();

               
                if ($role === "admin") {
                    header("Location: admin-dashboard.php");
                    exit();
                } else {
                    header("Location: index.php");
                    exit();
                }
            } else {
                $password_error = "Invalid password.";
            }
        } else {
            $username_error = "User not found.";
        }

        $stmt->close();
    }
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
</head>
<body>
<div class="login-container">
    <div class="brand-header">
        <h1>LOGIN</h1>
        <h2>CHRONOS ATELIER</h2>
        <p>Time to Explore</p>
    </div>
    
    <form class="login-form" action="" method="POST">
        <input type="text" name="username" placeholder="Username" required value="<?php echo htmlspecialchars($username ?? ''); ?>">
        <?php if (!empty($username_error)): ?>
            <p class="error-message"><?php echo htmlspecialchars($username_error); ?></p>
        <?php endif; ?>
        
        <input type="password" name="password" placeholder="Password" required>
        <?php if (!empty($password_error)): ?>
            <p class="error-message"><?php echo htmlspecialchars($password_error); ?></p>
        <?php endif; ?>

        <button type="submit">Login</button>
    </form>

    <div class="signup-link">
        <p>Don't Have an Account? <a class="link" href="signup.php">Sign Up Now!</a></p>
    </div>
</div>

</body>
</html>
