<?php
session_start();
include 'database.php';

$successMessage = ""; 
$errorMessage = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION["username"])) {
        $errorMessage = "Error: No user logged in.";
    } else {
        $logged_in_username = $_SESSION["username"]; 
        $contact_number = isset($_POST["contact"]) ? trim($_POST["contact"]) : "";
        $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
        $concern = isset($_POST["concern"]) ? trim($_POST["concern"]) : "";
        $message = isset($_POST["message"]) ? trim($_POST["message"]) : "";

        if (!empty($contact_number) && !empty($email) && !empty($concern) && !empty($message)) {
            if (!$conn) {
                $errorMessage = "Database connection failed: " . mysqli_connect_error();
            } else {
                // Check if the user exists
                $check_stmt = $conn->prepare("SELECT username FROM user_data WHERE username = ?");
                if (!$check_stmt) {
                    $errorMessage = "Prepare failed: " . $conn->error;
                } else {
                    $check_stmt->bind_param("s", $logged_in_username);
                    $check_stmt->execute();
                    $check_stmt->store_result();

                    if ($check_stmt->num_rows > 0) {
                        // Update existing user record
                        $stmt = $conn->prepare("UPDATE user_data SET email = ?, contact_number = ?, concern = ?, message = ? WHERE username = ?");
                        if (!$stmt) {
                            $errorMessage = "Prepare failed: " . $conn->error;
                        } else {
                            $stmt->bind_param("sssss", $email, $contact_number, $concern, $message, $logged_in_username);

                            if ($stmt->execute()) {
                                $successMessage = "Your message has been sent successfully and stored in our database."; 
                            } else {
                                $errorMessage = "Error: Unable to send message. Please try again.";
                            }

                            $stmt->close();
                        }
                    } else {
                        $errorMessage = "Error: No record found for logged-in user.";
                    }

                    $check_stmt->close();
                }
                $conn->close();
            }
        } else {
            $errorMessage = "Error: All fields are required.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Chronos Atelier</title>
    <link rel="stylesheet" href="src/assets/style/contact-styles.css?v=">
    <link rel="stylesheet" href="src/assets/style/navbar-styles.css">
    <link rel="stylesheet" href="src/assets/style/footer-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    <!-- Navbar Include-->
    <?php include 'navbar.php'; ?> 

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero__overlay"></div> 
        <img src="src/assets/images/CONTACT-US-HERO.webp" alt="Hero Watch" class="hero__image">
        <div class="hero__content">
            <h1>Contact Us</h1>
            <p class="header-subtext">Time is valuable, so is your experience.</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-container">
        <div class="contact-form">
            <h2>Start your Timeless Journey</h2>
            <form action="contact.php" method="POST" id="contactForm">
                <div class="input-group">
                    <label for="contact">Contact Number</label>
                    <input type="tel" id="contact" name="contact" required>
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="input-group">
                    <label for="concern">Concern</label>
                    <select id="concern" class="concern" name="concern" required>
                        <option value="" disabled selected>Select a concern</option>
                        <option value="Product Inquiry">Product Inquiry</option>
                        <option value="Order Status">Order Status</option>
                        <option value="Customization Request">Customization Request</option>
                        <option value="Warranty & Repairs">Warranty & Repairs</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>

                <button type="submit" class="submit-btn">Send Message</button>

                <!-- Success or Error Message Display -->
                <?php if (!empty($successMessage)): ?>
                    <p class="success-message"><?php echo htmlspecialchars($successMessage); ?></p>
                <?php elseif (!empty($errorMessage)): ?>
                    <p class="error-message"><?php echo htmlspecialchars($errorMessage); ?></p>
                <?php endif; ?>
            </form>
        </div>

        <!-- Google Maps -->
        <div class="contact-map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.835434509349!2d144.95592831531682!3d-37.81720997975171!2m3!1f0!2f0!3f0!2m3!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad65d5dfb1d0f1d%3A0x5045675218cce6e2!2sLuxury%20Watch%20Store!5e0!3m2!1sen!2sus!4v1617325251269!5m2!1sen!2sus"
                width="100%" height="550" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const successMessage = document.querySelector(".success-message");
            const errorMessage = document.querySelector(".error-message");

            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.display = "none";
                }, 5000); // Hide success message after 5 seconds
            }

            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.display = "none";
                }, 5000); // Hide error message after 5 seconds
            }
        });
    </script>

    <!-- Footer Include -->
    <?php include 'footer.php'; ?>
</body>
</html>