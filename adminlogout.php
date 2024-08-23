<?php
session_start(); // Start the session

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login page after a short delay to show logout message
header("Refresh: 2; url=adminlogin.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out - BookWave</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        /* CSS for Countdown Timer */
        #countdown {
            font-size: 18px;
            color: #333;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div id="container">    
    <header>
        <div id="header-box">
            <h1>BookWave E-Library Management System</h1>
        </div>
    </header>
    
    <main id="content">
        <section id="logout">
            <h2>You have been logged out</h2>
            <p>You will be redirected to the login page shortly. If you are not redirected automatically, <a href="adminlogin.php">click here</a>.</p>
            <div id="countdown">Redirecting in <span id="timer">2</span> seconds...</div>
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
        // Countdown Timer
        let countdownElement = document.getElementById('timer');
        let countdownTime = 2; // Time in seconds

        const countdownInterval = setInterval(() => {
            countdownTime--;
            countdownElement.textContent = countdownTime;

            if (countdownTime <= 0) {
                clearInterval(countdownInterval);
                window.location.href = 'adminlogin.php'; // Ensure it redirects when the timer hits 0
            }
        }, 1000);

        // Optional: Scroll to top of the page
        window.scrollTo({ top: 0, behavior: 'smooth' });

        // Optional: Alert for successful logout
        //alert('You have been logged out successfully!');
    });
</script>
</body>
</html>