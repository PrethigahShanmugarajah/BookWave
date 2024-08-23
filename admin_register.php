<?php
session_start();
include 'database.php'; // Include your database connection

// Initialize error and success messages
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Validate form data
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'All fields are required.';
    } else {
        // Check password complexity
        $hasUpperCase = preg_match('/[A-Z]/', $password);
        $hasLowerCase = preg_match('/[a-z]/', $password);
        $hasNumber = preg_match('/\d/', $password);
        $hasSymbol = preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password);

        if (!$hasUpperCase || !$hasLowerCase || !$hasNumber || !$hasSymbol) {
            $error = 'Password must include uppercase, lowercase, numbers, and symbols.';
        } else {
            // Check if email already exists
            $stmt = $db->prepare("SELECT Admin_ID FROM admins WHERE Admin_Email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                $error = 'Email is already registered.';
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert new admin into the database
                $stmt = $db->prepare("INSERT INTO admins (Admin_Name, Admin_Email, Admin_Password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $email, $hashed_password);
                if ($stmt->execute()) {
                    $success = 'Admin account created successfully.';
                } else {
                    $error = 'An error occurred. Please try again.';
                }
                $stmt->close();
            }
        }
        
        $db->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register - BookWave</title>
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
        <section id="admin-register">
            <h2>Register Admin</h2>
            
            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>
            
            <form id="register-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit">Register</button>
            </form>
            
            <p>Already have an admin account? <a href="adminlogin.php">Log in</a></p>
            <p>Back to <a href="index.php">Home</a></p>
        </section>
    </main>
    
    <footer>
        <div id="footer-box">
            <p>Copyright &copy; 2024 BookWave. All rights reserved.</p>
            <p>Contact us: <a href="mailto:support@bookwave.com">support@bookwave.com</a></p>
        </div>
    </footer>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('register-form');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const errorParagraph = document.querySelector('.error');
    const successParagraph = document.querySelector('.success');

    form.addEventListener('submit', function(event) {
        let valid = true;
        let errorMessage = '';

        // Clear previous error messages
        errorParagraph.textContent = '';
        successParagraph.textContent = '';

        // Check if fields are empty
        if (nameInput.value.trim() === '') {
            valid = false;
            errorMessage += 'Full Name is required. ';
        }
        if (emailInput.value.trim() === '') {
            valid = false;
            errorMessage += 'Email is required. ';
        }
        if (passwordInput.value.trim() === '') {
            valid = false;
            errorMessage += 'Password is required. ';
        }

        // Check password complexity
        const password = passwordInput.value;
        const hasUpperCase = /[A-Z]/.test(password);
        const hasLowerCase = /[a-z]/.test(password);
        const hasNumber = /\d/.test(password);
        const hasSymbol = /[!@#$%^&*(),.?":{}|<>]/.test(password);

        if (!hasUpperCase || !hasLowerCase || !hasNumber || !hasSymbol) {
            valid = false;
            errorMessage += 'Password must include uppercase, lowercase, numbers, and symbols. ';
        }

        if (!valid) {
            errorParagraph.textContent = errorMessage;
            event.preventDefault(); // Prevent form submission
        }
    });

    // Password Strength Indicator
    const passwordStrength = document.createElement('div');
    passwordStrength.id = 'password-strength';
    passwordInput.insertAdjacentElement('afterend', passwordStrength);

    passwordInput.addEventListener('input', function() {
        const strength = checkPasswordStrength(passwordInput.value);
        passwordStrength.textContent = `Password Strength: ${strength}`;
        passwordStrength.style.color = getStrengthColor(strength);
    });

    function checkPasswordStrength(password) {
        if (password.length < 6) return 'Weak';
        if (password.length < 12) return 'Moderate';
        return 'Strong';
    }

    function getStrengthColor(strength) {
        switch (strength) {
            case 'Weak': return 'red';
            case 'Moderate': return 'orange';
            case 'Strong': return 'green';
            default: return 'black';
        }
    }
});
</script>
</body>
</html>