<?php
session_start();
include 'database.php'; // Include your database connection

// Check if the user is logged in as an admin
if (!isset($_SESSION['Admin_ID'])) {
    header("Location: adminlogin.php");
    exit();
}

// Retrieve admin details
$admin_id = $_SESSION['Admin_ID'];
$stmt = $db->prepare("SELECT Admin_Name FROM admins WHERE Admin_ID = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$stmt->bind_result($admin_name);
$stmt->fetch();
$stmt->close();
$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BookWave</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<div id="container">
    <header>
        <div id="header-box">
            <h1>BookWave E-Library Management System</h1>
        </div>
        <!-- Navigation Box -->
        <section id="nav-box">
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="manage_books.php">Manage Books</a></li>
                <li><a href="list_books.php">Existing Books</a></li>
                <li><a href="manage_members.php">Manage Members</a></li>
                <li><a href="adminlogout.php" id="logout-link">Logout</a></li>
            </ul>
        </section>
    </header>
    
    <main id="content">
        <section id="dashboard">
            <h2>Welcome, <?php echo htmlspecialchars($admin_name); ?>!</h2>
            
            <p>Welcome to the admin dashboard. From here, you can manage books, view and manage members, and track borrowing records.</p>
            
            <!-- Additional admin features can be added here -->
        </section>
    </main>
    
    <footer>
        <div id="footer-box">
            <p>&copy; 2024 BookWave. All rights reserved.</p>
            <p>Contact us: <a href="mailto:support@bookwave.com">support@bookwave.com</a></p>
        </div>
    </footer>
</div>

<script>
// Confirm before logging out
document.getElementById('logout-link').addEventListener('click', function(event) {
    var confirmLogout = confirm("Are you sure you want to log out?");
    if (!confirmLogout) {
        event.preventDefault(); // Prevent the link from being followed
    }
});

// Future JavaScript functionalities
document.addEventListener('DOMContentLoaded', function() {
    // You can add additional JavaScript functionalities here
});
</script>

</body>
</html>