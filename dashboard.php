<?php
session_start();
include 'database.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['Member_ID'])) {
    header("Location: login.php");
    exit();
}

// Retrieve user details
$member_id = $_SESSION['Member_ID'];
$member_name = $_SESSION['Member_Name'];

// Fetch user data (optional)
$stmt = $db->prepare("SELECT Member_Email FROM members WHERE Member_ID = ?");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$stmt->bind_result($member_email);
$stmt->fetch();
$stmt->close();

$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - BookWave</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
      
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Display a personalized welcome message
        const memberName = "<?php echo htmlspecialchars($member_name); ?>";
        const welcomeMessage = `Welcome back, ${memberName}! We hope you find everything you need.`;

        // Show an alert with the personalized welcome message
        alert(welcomeMessage);

        // Optionally, you could also dynamically update content
        const welcomeMsgElement = document.getElementById('welcome-msg');
        if (welcomeMsgElement) {
            welcomeMsgElement.textContent = welcomeMessage;
        }
    });
    </script>
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
                <li><a href="dashboard.php">Home</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="viewBooks.php">View Books</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </section>
    </header>
    
    <main id="content">
        <section id="dashboard">
            <h2>Welcome, <?php echo htmlspecialchars($member_name); ?>!</h2>
            
            
            
            <p>Welcome to your dashboard! From here, you can manage your account, view available books, and track your borrowed books.</p>
            
            <!-- Additional content can be added here -->
        </section>
    </main>
    
    <footer>
        <div id="footer-box">
            <p>Copyright &copy; 2024 BookWave. All rights reserved.</p>
            <p>Contact us: <a href="mailto:support@bookwave.com">support@bookwave.com</a></p>
        </div>
    </footer>
</div>    
</body>
</html>