<?php
session_start();
include 'database.php'; // Include your database connection

// Initialize error and success messages
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Validate form data
    if (empty($username) || empty($password)) {
        $error = 'Both fields are required.';
    } else {
        // Prepare and execute query to check username
        $stmt = $db->prepare("SELECT Member_ID, Member_Password FROM members WHERE Member_Name = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows == 0) {
            $error = 'Username not found.';
        } else {
            $stmt->bind_result($member_id, $hashed_password);
            $stmt->fetch();
            
            // Verify password
            if (password_verify($password, $hashed_password)) {
                // Set session variables and redirect to dashboard or home page
                $_SESSION['Member_ID'] = $member_id;
                $_SESSION['Member_Name'] = $username;
                header("Location: dashboard.php"); // Change to your desired page
                exit();
            } else {
                $error = 'Incorrect password.';
            }
        }
        
        $stmt->close();
    }
    
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BookWave</title>
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
        <section id="login">
            <h2>Login</h2>
            
            <?php if ($error): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <div class="show-password-container">
                    <input type="checkbox" id="show-password">
                    <label for="show-password">Show Password</label>
                </div>

                <button type="submit">Log In</button>
            </form>
            
            <p>Don't have an account? <a href="register.php">Register</a></p>
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
