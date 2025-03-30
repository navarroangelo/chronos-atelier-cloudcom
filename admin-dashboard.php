<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'database.php';

function isValidAdminIP($ip, $allowedOctets) {
    $firstOctet = explode('.', $ip)[0]; // Get the first octet of user's IP
    return in_array($firstOctet, $allowedOctets);
}

$adminIP = $_SERVER['REMOTE_ADDR']; // Get the user's IP
$allowedOctets = [146, 155, 91];

if (isValidAdminIP($adminIP, $allowedOctets) == false) {
    http_response_code(403);
    header("Location: invalid.php");
    exit();
}

if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}


// Handle watch deletion
if (isset($_POST['delete_watch'])) {
    $watch_id = $_POST['watch_id'];
    $delete_query = "DELETE FROM watches WHERE watch_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $watch_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Watch deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting watch: " . $conn->error;
    }
    header("Location: admin-dashboard.php?section=watches");
    exit;
}

// Handle watch updates
if (isset($_POST['update_watch'])) {
    $watch_id = $_POST['watch_id'];
    $name = $_POST['watch_name'];
    $brand = $_POST['watch_brand'];
    $price = $_POST['watch_price'];
    $year = $_POST['watch_year'];
    $description = $_POST['watch_description'];
    
    $update_query = "UPDATE watches SET watch_name = ?, watch_brand = ?, watch_price = ?, watch_year = ?, watch_description = ? WHERE watch_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssdssi", $name, $brand, $price, $year, $description, $watch_id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Watch updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating watch: " . $conn->error;
    }
    header("Location: admin-dashboard.php?section=watches");
    exit;
}

// Get data for different sections
$current_section = isset($_GET['section']) ? $_GET['section'] : 'users';

// Users data
$users = [];
$users_query = "SELECT id, username, password, role, created_at FROM user_data";
$users_result = $conn->query($users_query);
if ($users_result) {
    while ($row = $users_result->fetch_assoc()) {
        $created_at = new DateTime($row['created_at'], new DateTimeZone('UTC'));
        $created_at->setTimezone(new DateTimeZone('Asia/Manila'));
        $row['created_at'] = $created_at->format('Y-m-d H:i:s');
        $users[] = $row;
    }
}

// Audit logs data
$audit_logs = [];
$logs_query = "SELECT id, username, action, action_timestamp FROM user_data WHERE action IS NOT NULL";
$logs_result = $conn->query($logs_query);
if ($logs_result) {
    while ($row = $logs_result->fetch_assoc()) {
        $action_timestamp = new DateTime($row['action_timestamp'], new DateTimeZone('UTC'));
        $action_timestamp->setTimezone(new DateTimeZone('Asia/Manila'));
        $row['action_timestamp'] = $action_timestamp->format('Y-m-d H:i:s');
        $audit_logs[] = $row;
    }
}

// Analytics data
$analytics = [];
$analytics_query = "SELECT id, username, ip_address, os_version, browser, location, processor FROM user_data";
$analytics_result = $conn->query($analytics_query);
if ($analytics_result) {
    while ($row = $analytics_result->fetch_assoc()) {
        $analytics[] = $row;
    }
}

$contacts = [];
if ($current_section === 'contact') {
    $contact_query = "SELECT username, contact_number, email, concern, message FROM user_data WHERE contact_number IS NOT NULL AND email IS NOT NULL";
    $contact_result = $conn->query($contact_query);
    if ($contact_result) {
        while ($row = $contact_result->fetch_assoc()) {
            $contacts[] = $row;
        }
    }
}

// Watches data - now including description
$watches = [];
$watches_query = "SELECT watch_id, watch_name, watch_brand, watch_price, watch_year, watch_description FROM watches";
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
    <link rel="stylesheet" href="src/assets/style/admin-styles.css">

</head>
<body>
    <header class="admin-header">
        <h1 class="admin-title">CHRONOS ADMIN PAGE</h1>
        <div class="admin-controls">
            <input type="text" class="search-box" placeholder="Search...">
            <button class="home-btn" onclick="window.location.href='index.php'">Visit Website</button>
            <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>

        </div>
    </header>

    <nav class="admin-nav">
        <button class="nav-btn <?= $current_section === 'users' ? 'active' : '' ?>" 
                onclick="window.location.href='?section=users'">USERS</button>
        <button class="nav-btn <?= $current_section === 'audit' ? 'active' : '' ?>" 
                onclick="window.location.href='?section=audit'">AUDIT LOGS</button>
        <button class="nav-btn <?= $current_section === 'analytics' ? 'active' : '' ?>" 
                onclick="window.location.href='?section=analytics'">ANALYTICS</button>
        <button class="nav-btn <?= $current_section === 'contact' ? 'active' : '' ?>" 
                onclick="window.location.href='?section=contact'">CONTACT</button>
        <button class="nav-btn <?= $current_section === 'watches' ? 'active' : '' ?>" 
                onclick="window.location.href='?section=watches'">WATCHES</button>
    </nav>

    <main class="admin-content">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Users Section -->
        <div id="users-section" class="<?= $current_section !== 'users' ? 'hidden' : '' ?>">
            <h2>Users Management</h2>
            <div class="filter-container">
                <label for="users-filter">Sort By:</label>
                <select id="users-filter" class="search-box" onchange="sortTable('users-table', this.value)">
                    <option value="ascending">Ascending (ID)</option>
                    <option value="descending">Descending (ID)</option>
                    <option value="alphabetical">Alphabetical (Username)</option>
                </select>
                <label for="users-column-filter">Filter By:</label>
                <select id="users-column-filter" class="search-box" onchange="filterTable('users-table', this.value, 'users-column-input')">
                    <option value="0">UserID</option>
                    <option value="1">Username</option>
                    <option value="2">Password</option>
                    <option value="3">Role</option>
                    <option value="4">Created At</option>
                </select>
                <input type="text" id="users-column-input" class="search-box"  placeholder="Filter value..." oninput="filterTable('users-table', document.getElementById('users-column-filter').value, 'users-column-input')">
            </div>
            <table id="users-table" class="data-table">
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
                        <td><?= htmlspecialchars(substr($user['password'], 0, 15)) . (strlen($user['password']) > 15 ? '...' : '') ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Audit Logs Section -->
        <div id="audit-section" class="<?= $current_section !== 'audit' ? 'hidden' : '' ?>">
            <h2>Audit Logs</h2>
            <div class="filter-container">
                <label for="audit-filter">Sort By:</label>
                <select id="audit-filter" class="search-box" onchange="sortTable('audit-table', this.value)">
                    <option value="ascending">Ascending (ID)</option>
                    <option value="descending">Descending (ID)</option>
                    <option value="alphabetical">Alphabetical (Username)</option>
                </select>
                <label for="audit-column-filter">Filter By:</label>
                <select id="audit-column-filter" class="search-box" onchange="filterTable('audit-table', this.value, 'audit-column-input')">
                    <option value="0">UserID</option>
                    <option value="1">Username</option>
                    <option value="2">Action</option>
                    <option value="3">Timestamp</option>
                </select>
                <input type="text" id="audit-column-input" class="search-box" placeholder="Filter value..." oninput="filterTable('audit-table', document.getElementById('audit-column-filter').value, 'audit-column-input')">
            </div>
            <table id="audit-table" class="data-table">
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
        <div class="filter-container">
            <label for="analytics-filter">Sort By:</label>
            <select id="analytics-filter" class="search-box" onchange="sortTable('analytics-table', this.value)">
                <option value="ascending">Ascending (ID)</option>
                <option value="descending">Descending (ID)</option>
                <option value="alphabetical">Alphabetical (Username)</option>
            </select>
            <label for="analytics-column-filter">Filter By:</label>
            <select id="analytics-column-filter" class="search-box"  onchange="filterTable('analytics-table', this.value, 'analytics-column-input')">
                <option value="0">UserID</option>
                <option value="1">Username</option>
                <option value="2">IP Address</option>
                <option value="3">OS</option>
                <option value="4">Browser</option>
                <option value="5">Location</option>
                <option value="6">Processor</option>
            </select>
            <input type="text" id="analytics-column-input" class="search-box" placeholder="Filter value..." oninput="filterTable('analytics-table', document.getElementById('analytics-column-filter').value, 'analytics-column-input')">
        </div>
        <table id="analytics-table" class="data-table">
            <thead>
                <tr>
                    <th>UserID</th>
                    <th>Username</th>
                    <th>IP Address</th>
                    <th>OS</th>
                    <th>Browser</th>
                    <th>Location</th>
                    <th>Processor</th>
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
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

            <!-- Contact Section -->
            <div id="contact-section" class="<?= $current_section !== 'contact' ? 'hidden' : '' ?>">
            <h2>Contact Messages</h2>
            <div class="filter-container">
                <label for="contact-column-filter">Filter By:</label>
                <select id="contact-column-filter" class="search-box" onchange="filterContactTable(this.value, 'contact-column-input')">
                    <option value="0">Username</option>
                    <option value="1">Contact Number</option>
                    <option value="2">Email</option>
                    <option value="3">Concern</option>
                    <option value="4">Message</option>
                </select>
                <input type="text" id="contact-column-input" class="search-box" placeholder="Filter value..." oninput="filterContactTable(document.getElementById('contact-column-filter').value, 'contact-column-input')">
            </div>
            <table id="contact-table" class="data-table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Contact Number</th>
                        <th>Email</th>
                        <th>Concern</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?= htmlspecialchars($contact['username']) ?></td>
                        <td><?= htmlspecialchars($contact['contact_number']) ?></td>
                        <td><?= htmlspecialchars($contact['email']) ?></td>
                        <td><?= htmlspecialchars($contact['concern']) ?></td>
                        <td><?= htmlspecialchars($contact['message']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- Watches Section -->
        <div id="watches-section" class="<?= $current_section !== 'watches' ? 'hidden' : '' ?>">
            <h2>Watches Management</h2>
            <div class="filter-container">
                <label for="watches-filter">Sort By:</label>
                <select id="watches-filter" class="search-box" onchange="sortWatchesTable(this.value)">
                    <option value="ascending">Ascending (Price)</option>
                    <option value="descending">Descending (Price)</option>
                    <option value="alphabetical">Alphabetical (Name)</option>
                </select>
                <label for="watches-column-filter">Filter By:</label>
                <select id="watches-column-filter" class="search-box" onchange="filterWatchesTable(this.value, 'watches-column-input')">
                    <option value="0">Watch ID</option>
                    <option value="1">Name</option>
                    <option value="2">Brand</option>
                    <option value="3">Price</option>
                    <option value="4">Year</option>
                    <option value="5">Description</option>
                </select>
                <input type="text" id="watches-column-input" class="search-box" placeholder="Filter value..." oninput="filterWatchesTable(document.getElementById('watches-column-filter').value, 'watches-column-input')">
            </div>
            <table id="watches-table" class="data-table">
                <thead>
                    <tr>
                        <th>Watch ID</th>
                        <th>Name</th>
                        <th>Brand</th>
                        <th>Price</th>
                        <th>Year</th>
                        <th>Description</th>
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
                        <td><?= htmlspecialchars(substr($watch['watch_description'], 0, 50)) . (strlen($watch['watch_description']) > 50 ? '...' : '') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <!-- Edit Watch Modal with Description Field -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Watch</h3>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <form id="editForm" method="POST" action="admin-dashboard.php?section=watches">
                <input type="hidden" name="watch_id" id="edit_watch_id">
                <input type="hidden" name="update_watch" value="1">
                
                <div class="form-group">
                    <label for="watch_name">Watch Name</label>
                    <input type="text" id="watch_name" name="watch_name" required>
                </div>
                
                <div class="form-group">
                    <label for="watch_brand">Brand</label>
                    <input type="text" id="watch_brand" name="watch_brand" required>
                </div>
                
                <div class="form-group">
                    <label for="watch_price">Price</label>
                    <input type="number" id="watch_price" name="watch_price" step="0.01" required>
                </div>
                
                <div class="form-group">
                    <label for="watch_year">Year</label>
                    <input type="number" id="watch_year" name="watch_year" required>
                </div>
                
                <div class="form-group">
                    <label for="watch_description">Description</label>
                    <textarea id="watch_description" name="watch_description" rows="4" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;"></textarea>
                </div>
                
                <div class="form-group">
                <label for="watch_image" style="text-align: center">Image</label>
                    <img id="watch_image_preview" src="" alt="Watch Image" style="display: none; max-width: 20%; height: auto; margin-bottom: 1.5rem; margin-left: 11rem;">
                </div>

                <div class="modal-footer">
                    <button type="button" class="action-btn cancel-btn" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="action-btn save-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Confirm Deletion</h3>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <p>Are you sure you want to delete this watch? This action cannot be undone.</p>
            <form id="deleteForm" method="POST" action="admin-dashboard.php?section=watches">
                <input type="hidden" name="watch_id" id="delete_watch_id">
                <input type="hidden" name="delete_watch" value="1">
                
                <div class="modal-footer">
                    <button type="button" class="action-btn cancel-btn" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="action-btn delete-btn">Delete</button>
                </div>
            </form>
        </div>
    </div>

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
        
        // Edit functionality
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const watchId = this.getAttribute('data-id');
                fetchWatchDetails(watchId);
            });
        });
        
        // Updated fetchWatchDetails function to include description
        function fetchWatchDetails(watchId) {
            fetch('get-watch-details.php?watch_id=' + watchId)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        document.getElementById('edit_watch_id').value = data.watch.watch_id;
                        document.getElementById('watch_name').value = data.watch.watch_name;
                        document.getElementById('watch_brand').value = data.watch.watch_brand;
                        document.getElementById('watch_price').value = data.watch.watch_price;
                        document.getElementById('watch_year').value = data.watch.watch_year;
                        document.getElementById('watch_description').value = data.watch.watch_description;
                        
                                        // Handle image preview
                const imagePreview = document.getElementById('watch_image_preview');
                if (data.watch.watch_image) {
                    imagePreview.src = data.watch.watch_image;
                    imagePreview.style.display = 'block';
                } else {
                    imagePreview.style.display = 'none';
                }

                        document.getElementById('editModal').style.display = 'flex';
                    } else {
                        alert('Error fetching watch details: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error fetching watch details. Check console for details.');
                });
        }
        
        function confirmDelete(watchId) {
            document.getElementById('delete_watch_id').value = watchId;
            document.getElementById('deleteModal').style.display = 'flex';
        }
        
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
            document.getElementById('deleteModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                closeModal();
            }
        });

        function sortTable(tableId, sortBy) {
            const table = document.getElementById(tableId);
            const rows = Array.from(table.querySelectorAll('tbody tr'));

            rows.sort((a, b) => {
                const getValue = (row, index) => row.children[index].textContent.trim().toLowerCase();

                switch (sortBy) {
                    case 'latest':
                        // Sort by the last column (e.g., Created At or Timestamp)
                        return new Date(getValue(b, 4)) - new Date(getValue(a, 4));
                    case 'alphabetical':
                        // Sort by the second column (e.g., Username)
                        return getValue(a, 1).localeCompare(getValue(b, 1));
                    case 'ascending':
                        // Sort by the first column (e.g., ID)
                        return parseInt(getValue(a, 0)) - parseInt(getValue(b, 0));
                    case 'descending':
                        // Sort by the first column (e.g., ID) in reverse
                        return parseInt(getValue(b, 0)) - parseInt(getValue(a, 0));
                    default:
                        return 0;
                }
            });

            // Reorder rows in the table
            const tbody = table.querySelector('tbody');
            rows.forEach(row => tbody.appendChild(row));
        }

        function filterTable(tableId, columnIndex, inputId) {
        const table = document.getElementById(tableId);
        const rows = table.querySelectorAll('tbody tr');
        const filterValue = document.getElementById(inputId).value.toLowerCase();
        

	 rows.forEach(row => {
        const cellValue = row.children[columnIndex].textContent.trim().toLowerCase();
        row.style.display = cellValue.includes(filterValue) ? '' : 'none';
    });

}
    // Sorting function specifically for the Watches table
function sortWatchesTable(sortBy) {
    const table = document.getElementById('watches-table');
    const rows = Array.from(table.querySelectorAll('tbody tr'));

    rows.sort((a, b) => {
        const getValue = (row, index) => row.children[index].textContent.trim();

        switch (sortBy) {
            case 'alphabetical':
                // Sort by the second column (Name)
                return getValue(a, 1).localeCompare(getValue(b, 1));
            case 'ascending':
                // Sort by the fourth column (Price) in ascending order
                return parseFloat(getValue(a, 3).replace(/[^0-9.-]+/g, '')) - parseFloat(getValue(b, 3).replace(/[^0-9.-]+/g, ''));
            case 'descending':
                // Sort by the fourth column (Price) in descending order
                return parseFloat(getValue(b, 3).replace(/[^0-9.-]+/g, '')) - parseFloat(getValue(a, 3).replace(/[^0-9.-]+/g, ''));
            default:
                return 0;
        }
    });

    // Reorder rows in the table
    const tbody = table.querySelector('tbody');
    rows.forEach(row => tbody.appendChild(row));
}

// Filtering function specifically for the Watches table
function filterWatchesTable(columnIndex, inputId) {
    const table = document.getElementById('watches-table');
    const rows = table.querySelectorAll('tbody tr');
    const filterValue = document.getElementById(inputId).value.toLowerCase();

    rows.forEach(row => {
        const cellValue = row.children[columnIndex].textContent.trim().toLowerCase();
        row.style.display = cellValue.includes(filterValue) ? '' : 'none';
    });
}


        // Filtering function specifically for the Contact table
        function filterContactTable(columnIndex, inputId) {
            const table = document.getElementById('contact-table');
            const rows = table.querySelectorAll('tbody tr');
            const filterValue = document.getElementById(inputId).value.toLowerCase();

            rows.forEach(row => {
                const cellValue = row.children[columnIndex].textContent.trim().toLowerCase();
                row.style.display = cellValue.includes(filterValue) ? '' : 'none';
            });
        }
                
    </script>
</body>
</html>