<?php
session_start();
include 'database.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['Member_ID'])) {
    header("Location: login.php");
    exit();
}

// Initialize variables
$member_id = $_SESSION['Member_ID'];
$member_name = '';
$member_email = '';
$success = '';
$error = '';

// Fetch user data
$stmt = $db->prepare("SELECT Member_Name, Member_Email FROM members WHERE Member_ID = ?");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$stmt->bind_result($member_name, $member_email);
$stmt->fetch();
$stmt->close();

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $new_name = $_POST['name'];
    $new_email = $_POST['email'];
    $new_password = $_POST['password'];
    $current_password = $_POST['current_password'];
    
    // Validate form data
    if (empty($new_name) || empty($new_email) || empty($current_password)) {
        $error = 'All fields are required.';
    } else {
        // Fetch current password hash
        $stmt = $db->prepare("SELECT Member_Password FROM members WHERE Member_ID = ?");
        $stmt->bind_param("i", $member_id);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();
        
        // Verify current password
        if (!password_verify($current_password, $hashed_password)) {
            $error = 'Current password is incorrect.';
        } else {
            // Update username and email
            $stmt = $db->prepare("UPDATE members SET Member_Name = ?, Member_Email = ? WHERE Member_ID = ?");
            $stmt->bind_param("ssi", $new_name, $new_email, $member_id);
            if ($stmt->execute()) {
                // Update password if provided
                if (!empty($new_password)) {
                    $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("UPDATE members SET Member_Password = ? WHERE Member_ID = ?");
                    $stmt->bind_param("si", $hashed_new_password, $member_id);
                    $stmt->execute();
                }
                $success = 'Profile updated successfully.';
                // Refresh the page to reflect changes
                header("Refresh:0");
            } else {
                $error = 'Error occurred while updating profile. Please try again.';
            }
            $stmt->close();
        }
    }
    
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - BookWave</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script>
        // Validate form before submission
        function validateForm() {
            var name = document.getElementById('name').value.trim();
            var email = document.getElementById('email').value.trim();
            var currentPassword = document.getElementById('current_password').value.trim();
            var newPassword = document.getElementById('password').value.trim();
            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

            if (name === '' || email === '' || currentPassword === '') {
                alert('All fields are required.');
                return false;
            }

            if (!emailPattern.test(email)) {
                alert('Please enter a valid email address.');
                return false;
            }

            if (newPassword !== '' && newPassword.length < 8) {
                alert('New password must be at least 8 characters long.');
                return false;
            }

            return confirm('Are you sure you want to update your profile?');
        }

        // Display password strength
        function checkPasswordStrength() {
            var password = document.getElementById('password').value;
            var strengthIndicator = document.getElementById('password-strength');
            var strength = 'Weak';

            if (password.length >= 8) {
                strength = 'Moderate';
            }
            if (password.match(/[A-Z]/) && password.match(/[0-9]/) && password.length >= 8) {
                strength = 'Strong';
            }

            strengthIndicator.textContent = 'Password Strength: ' + strength;
        }
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
        <section id="profile">
            <h2>Profile</h2>
            
            <?php if ($success): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return validateForm()">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($member_name); ?>" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($member_email); ?>" required>
                
                <label for="current_password">Current Password:</label>
                <input type="password" id="current_password" name="current_password" required>
                
                <label for="password">New Password (Leave blank to keep current):</label>
                <input type="password" id="password" name="password" onkeyup="checkPasswordStrength()">
                <p id="password-strength"></p>
                
                <button type="submit">Update Profile</button>
            </form>
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