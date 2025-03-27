<?php
session_start();
include 'database.php';
include 'user-details.php';

// Check if admin is logged in
// if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
//     header("Location: admin-login.php");
//     exit;
// }

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin-login.php");
    exit;
}

// Get data for different sections
$current_section = isset($_GET['section']) ? $_GET['section'] : 'users';

// Users data
$users = [];
$users_query = "SELECT id, username, password, created_at FROM user_data";
$users_result = $conn->query($users_query);
if ($users_result) {
    while ($row = $users_result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Audit logs data
$audit_logs = [];
$logs_query = "SELECT id, username, action, action_timestamp FROM user_data WHERE action IS NOT NULL";
$logs_result = $conn->query($logs_query);
if ($logs_result) {
    while ($row = $logs_result->fetch_assoc()) {
        $audit_logs[] = $row;
    }
}

// Analytics data
$analytics = [];
$analytics_query = "SELECT id, username, ip_address, os_version, browser, location, processor, user_agent FROM user_data";
$analytics_result = $conn->query($analytics_query);
if ($analytics_result) {
    while ($row = $analytics_result->fetch_assoc()) {
        $analytics[] = $row;
    }
}

// Watches data
$watches = [];
$watches_query = "SELECT watch_id, watch_name, watch_brand, watch_price, watch_year FROM watches";
$watches_result = $conn->query($watches_query);
if ($watches_result) {
    while ($row = $watches_result->fetch_assoc()) {
        $watches[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chronos Atelier - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1a1a2e;
            --secondary: #16213e;
            --accent: #0f3460;
            --gold: #e6b31e;
            --light: #f9f9f9;
            --dark: #0a0a0a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: var(--dark);
        }

        .admin-header {
            background-color: var(--primary);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .admin-title {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .admin-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .search-box {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            border: 1px solid #ddd;
            min-width: 250px;
        }

        .logout-btn {
            background-color: var(--gold);
            color: var(--dark);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #f0c14b;
        }

        .admin-nav {
            background-color: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid #eee;
            display: flex;
            gap: 1rem;
        }

        .nav-btn {
            padding: 0.5rem 1rem;
            background-color: var(--secondary);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .nav-btn:hover, .nav-btn.active {
            background-color: var(--accent);
        }

        .admin-content {
            padding: 2rem;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .data-table th, .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .data-table th {
            background-color: var(--primary);
            color: white;
            font-weight: 500;
        }

        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .data-table tr:hover {
            background-color: #f9f9f9;
        }

        .action-btn {
            padding: 0.3rem 0.6rem;
            margin: 0 0.2rem;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.8rem;
        }

        .edit-btn {
            background-color: #4CAF50;
            color: white;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
        }

        .add-btn {
            background-color: var(--gold);
            color: var(--dark);
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <h1 class="admin-title">CHRONOS ADMIN PAGE</h1>
        <div class="admin-controls">
            <input type="text" class="search-box" placeholder="Search...">
            <button class="logout-btn" onclick="window.location.href='?logout=true'">Logout</button>
        </div>
    </header>

    <nav class="admin-nav">
        <button class="nav-btn <?= $current_section === 'users' ? 'active' : '' ?>" 
                onclick="window.location.href='?section=users'">USERS</button>
        <button class="nav-btn <?= $current_section === 'audit' ? 'active' : '' ?>" 
                onclick="window.location.href='?section=audit'">AUDIT LOGS</button>
        <button class="nav-btn <?= $current_section === 'analytics' ? 'active' : '' ?>" 
                onclick="window.location.href='?section=analytics'">ANALYTICS</button>
        <button class="nav-btn <?= $current_section === 'watches' ? 'active' : '' ?>" 
                onclick="window.location.href='?section=watches'">WATCHES</button>
    </nav>

    <main class="admin-content">
        <!-- Users Section -->
        <div id="users-section" class="<?= $current_section !== 'users' ? 'hidden' : '' ?>">
            <h2>Users Management</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>UserID</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Role</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td>••••••••</td>
                        <td>User</td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Audit Logs Section -->
        <div id="audit-section" class="<?= $current_section !== 'audit' ? 'hidden' : '' ?>">
            <h2>Audit Logs</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>UserID</th>
                        <th>Username</th>
                        <th>Action</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($audit_logs as $log): ?>
                    <tr>
                        <td><?= htmlspecialchars($log['id']) ?></td>
                        <td><?= htmlspecialchars($log['username']) ?></td>
                        <td><?= htmlspecialchars($log['action']) ?></td>
                        <td><?= htmlspecialchars($log['action_timestamp']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Analytics Section -->
        <div id="analytics-section" class="<?= $current_section !== 'analytics' ? 'hidden' : '' ?>">
            <h2>User Analytics</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>UserID</th>
                        <th>Username</th>
                        <th>IP Address</th>
                        <th>OS</th>
                        <th>Browser</th>
                        <th>Location</th>
                        <th>Processor</th>
                        <th>User Agent</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($analytics as $analytic): ?>
                    <tr>
                        <td><?= htmlspecialchars($analytic['id']) ?></td>
                        <td><?= htmlspecialchars($analytic['username']) ?></td>
                        <td><?= htmlspecialchars($analytic['ip_address']) ?></td>
                        <td><?= htmlspecialchars($analytic['os_version']) ?></td>
                        <td><?= htmlspecialchars($analytic['browser']) ?></td>
                        <td><?= htmlspecialchars($analytic['location']) ?></td>
                        <td><?= htmlspecialchars($analytic['processor']) ?></td>
                        <td><?= htmlspecialchars($analytic['user_agent']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Watches Section -->
        <div id="watches-section" class="<?= $current_section !== 'watches' ? 'hidden' : '' ?>">
            <h2>Watches Management</h2>
            <button class="add-btn" onclick="window.location.href='add-watch.php'">Add New Watch</button>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Watch ID</th>
                        <th>Name</th>
                        <th>Brand</th>
                        <th>Price</th>
                        <th>Year</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($watches as $watch): ?>
                    <tr>
                        <td><?= htmlspecialchars($watch['watch_id']) ?></td>
                        <td><?= htmlspecialchars($watch['watch_name']) ?></td>
                        <td><?= htmlspecialchars($watch['watch_brand']) ?></td>
                        <td>$<?= number_format($watch['watch_price'], 2) ?></td>
                        <td><?= htmlspecialchars($watch['watch_year']) ?></td>
                        <td>
                            <button class="action-btn edit-btn">Edit</button>
                            <button class="action-btn delete-btn">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        // Simple search functionality
        document.querySelector('.search-box').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const currentSection = document.querySelector('[id$="-section"]:not(.hidden)');
            const rows = currentSection.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    </script>
</body>
</html>