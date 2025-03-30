<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'database.php';

header('Content-Type: application/json');

try {
    if (!isset($_GET['watch_id'])) {
        throw new Exception('Watch ID not provided');
    }

    $watch_id = (int)$_GET['watch_id'];
    $query = "SELECT * FROM watches WHERE watch_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $watch_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $watch = $result->fetch_assoc();

        // Handle the watch_image field
        if (!empty($watch['watch_image'])) {
            if (strpos($watch['watch_image'], 'src/') === 0) {
                // Local file path
                $watch['watch_image'] = $watch['watch_image'];
            } else {
                // Binary image data (BLOB)
                $watch['watch_image'] = 'data:image/jpeg;base64,' . base64_encode($watch['watch_image']);
            }
        }

        echo json_encode([
            'success' => true,
            'watch' => $watch
        ]);
    } else {
        throw new Exception('Watch not found');
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>