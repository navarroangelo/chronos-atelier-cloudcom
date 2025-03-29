<?php
session_start();
include 'database.php';

if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $name = trim($_POST["name"]);
    $brand = trim($_POST["brand"]);
    $price = floatval($_POST["price"]);
    $year = isset($_POST["year"]) ? intval($_POST["year"]) : null;
    $description = trim($_POST["description"]);
    
   
    if (empty($name) || empty($brand) || empty($price)) {
        $error = "Name, brand, and price are required fields.";
    } else {
      
        $image = null;
        $image_error = null;
        
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
           
            $file_info = getimagesize($_FILES["image"]["tmp_name"]);
            if ($file_info === false) {
                $image_error = "Uploaded file is not an image.";
            } else {
              
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
               
                $stmt = $conn->prepare("INSERT INTO watches (watch_name, watch_brand, watch_price, watch_year, watch_description, watch_image) VALUES (?, ?, ?, ?, ?, ?)");
                
                if ($stmt === false) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }
                
               
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
    <link rel="stylesheet" href="src/assets/style/add-watch-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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