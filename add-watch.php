<?php
session_start();
include 'database.php';

// if (!isset($_SESSION['admin_logged_in'])) {
//     header("Location: admin-login.php");
//     exit;
// }

$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $name = trim($_POST["name"]);
    $brand = trim($_POST["brand"]);
    $price = floatval($_POST["price"]);
    $year = isset($_POST["year"]) ? intval($_POST["year"]) : null;
    $description = trim($_POST["description"]);
    
    // Validate required fields
    if (empty($name) || empty($brand) || empty($price)) {
        $error = "Name, brand, and price are required fields.";
    } else {
        // Handle image upload
        $image = null;
        $image_error = null;
        
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
            // Check if file is an image
            $file_info = getimagesize($_FILES["image"]["tmp_name"]);
            if ($file_info === false) {
                $image_error = "Uploaded file is not an image.";
            } else {
                // Read image file content
                $image = file_get_contents($_FILES["image"]["tmp_name"]);
                if ($image === false) {
                    $image_error = "Failed to read image file.";
                }
            }
        } elseif (isset($_FILES["image"]) && $_FILES["image"]["error"] != UPLOAD_ERR_NO_FILE) {
            $image_error = "Error uploading file: " . $_FILES["image"]["error"];
        }
        
        if ($image_error) {
            $error = $image_error;
        } else {
            try {
                // Prepare statement with proper parameter types
                $stmt = $conn->prepare("INSERT INTO watches (watch_name, watch_brand, watch_price, watch_year, watch_description, watch_image) VALUES (?, ?, ?, ?, ?, ?)");
                
                if ($stmt === false) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }
                
                // Bind parameters - 's' for string, 'd' for double, 'i' for integer, 'b' for blob
                $bound = $stmt->bind_param("ssdiss", $name, $brand, $price, $year, $description, $image);
                
                if ($bound === false) {
                    throw new Exception("Bind failed: " . $stmt->error);
                }
                
                $executed = $stmt->execute();
                
                if ($executed === false) {
                    throw new Exception("Execute failed: " . $stmt->error);
                }
                
                if ($stmt->affected_rows > 0) {
                    header("Location: admin-dashboard.php?section=watches");
                    exit;
                } else {
                    $error = "No rows were inserted.";
                }
            } catch (Exception $e) {
                $error = "Database error: " . $e->getMessage();
            } finally {
                if (isset($stmt)) {
                    $stmt->close();
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Watch - Chronos Atelier</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            padding: 2rem;
        }
        
        .form-container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        
        h1 {
            color: #1a1a2e;
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        input, textarea, select {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        button {
            padding: 0.8rem 1.5rem;
            background-color: #1a1a2e;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }
        
        .error {
            color: #e74c3c;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Add New Watch</h1>
        
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <label for="name">Watch Name</label>
            <input type="text" id="name" name="name" required>
            
            <label for="brand">Brand</label>
            <input type="text" id="brand" name="brand" required>
            
            <label for="price">Price</label>
            <input type="number" id="price" name="price" step="0.01" required>
            
            <label for="year">Year</label>
            <input type="number" id="year" name="year" min="1900" max="<?= date('Y') ?>">
            
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4"></textarea>
            
            <label for="image">Image</label>
            <input type="file" id="image" name="image" accept="image/*">
            
            <button type="submit">Add Watch</button>
            <button type="button" onclick="window.location.href='admin-dashboard.php?section=watches'">Cancel</button>
        </form>
    </div>
</body>
</html>