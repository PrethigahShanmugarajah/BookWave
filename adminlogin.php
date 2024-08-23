<?php
session_start();
include 'database.php'; // Include your database connection

// Initialize error message
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Validate form data
    if (empty($username) || empty($password)) {
        $error = 'Both fields are required.';
    } else {
        // Check if username exists
        $stmt = $db->prepare("SELECT Admin_ID, Admin_Password FROM admins WHERE Admin_Name = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($admin_id, $hashed_password);
        $stmt->fetch();
        
        if ($admin_id && password_verify($password, $hashed_password)) {
            // Login successful
            $_SESSION['Admin_ID'] = $admin_id;
            header("Location: admin_dashboard.php"); // Redirect to admin dashboard or another page
            exit();
        } else {
            $error = 'Invalid username or password.';
        }
        
        $stmt->close();
        $db->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - BookWave</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<div id="container">
    <header>
        <div id="header-box">
            <h1>BookWave E-Library Management System</h1>
        </div>
    </header>
    
    <main id="content">
        <section id="admin-login">
            <h2>Admin Login</h2>
            
            <?php if ($error): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                
                <!-- Show Password Checkbox -->
                <div class="show-password-container">
                    <input type="checkbox" id="show-password">
                    <label for="show-password">Show Password</label>
                </div>
                
                <button type="submit">Log In</button>
            </form>
            
            <p>Don't have an admin account? <a href="admin_register.php">Register now</a></p>
            <p>Back to <a href="index.php">Home</a></p>
        </section>
    </main>
    
    <footer>
        <div id="footer-box">
            <p>Copyright &copy; 2024 BookWave. All rights reserved.</p>
            <p>Contact us: <a href="mailto:support@bookwave.com">support@bookwave.com</a></p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Client-Side Form Validation
            const form = document.querySelector('form');
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const errorParagraph = document.querySelector('.error');

            form.addEventListener('submit', function(event) {
                let valid = true;
                let errorMessage = '';

                // Clear previous error messages
                errorParagraph.textContent = '';

                // Check if fields are empty
                if (usernameInput.value.trim() === '') {
                    valid = false;
                    errorMessage += 'Username is required. ';
                }
                if (passwordInput.value.trim() === '') {
                    valid = false;
                    errorMessage += 'Password is required. ';
                }

                if (!valid) {
                    errorParagraph.textContent = errorMessage;
                    event.preventDefault(); // Prevent form submission
                }
            });

            // Show/Hide Password Functionality
            const showPasswordCheckbox = document.getElementById('show-password');
            
            showPasswordCheckbox.addEventListener('change', function() {
                if (showPasswordCheckbox.checked) {
                    passwordInput.type = 'text';
                } else {
                    passwordInput.type = 'password';
                }
            });

            // Auto-Focus on Username Field
            usernameInput.focus();
        });
    </script>
</div>    
</body>
</html>