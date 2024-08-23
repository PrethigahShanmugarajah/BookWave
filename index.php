<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookWave - E-Library Management System</title>
    <meta name="description" content="BookWave - Your ultimate destination for managing and discovering books online.">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <div id="container">
        <!-- Header Section -->
        <header id="header-box">
            <h1>Welcome to BookWave</h1>
            <p>Your ultimate destination for managing and discovering books online.</p>
            <div id="greeting"></div>
        </header>

        <!-- Main Content Section -->
        <main>
            <section id="content-box">
                <h2>About BookWave</h2>
                <p>BookWave is a comprehensive e-library management system designed to streamline and enhance the management of book collections, making it both simple and efficient. Whether you are a library administrator overseeing a large collection or a book enthusiast managing a personal library, BookWave offers a user-friendly interface that simplifies every aspect of the process. With BookWave, you can easily manage your book collections, keeping track of each book's details, availability, and location within the library. The system also allows you to monitor and track borrowings, ensuring that books are returned on time and that users can access the resources they need without hassle. In addition to these core functions, BookWave is designed with flexibility in mind, providing robust tools for organizing books by categories, authors, or genres, and allowing users to search and filter the collection with ease. Whether you need to update a book's information, check the status of a borrowed book, or generate reports on library usage, BookWave provides the tools you need to manage your library efficiently and effectively.</p>

                <h2>Features</h2>
                <ul>
                    <li><strong>Member Registration:</strong> Sign up to become a member and manage your profile.</li>
                    <li><strong>Book Management:</strong> Add, edit, view, and delete book details with ease.</li>
                    <li><strong>Borrowing Records:</strong> Track book borrowings and returns.</li>
                    <li><strong>Comment System:</strong> Leave comments on books and read feedback from others.</li>
                    <li><strong>Reports:</strong> Generate and view reports related to book activities and more.</li>
                </ul>

                <h2>Get Started</h2>
                <p>Ready to explore BookWave? <a href="register.php">Register now</a> or <a href="login.php">log in</a> to access all features.</p>
                <a href="register.php" class="btn">Member Register</a>
                <a href="login.php" class="btn">Member Log In</a>
                <a href="adminlogin.php" class="btn">Admin Login</a>
                <a href="admin_register.php" class="btn">Admin Register</a>
            </section>
        </main>

        <!-- Footer Section -->
        <footer id="footer-box">
            <p>&copy; 2024 BookWave. All rights reserved.</p>
            <p>Contact us: <a href="mailto:support@bookwave.com">support@bookwave.com</a></p>
        </footer>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth Scroll for internal links
            const links = document.querySelectorAll('a[href^="#"]');
            links.forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const targetElement = document.getElementById(targetId);
                    if (targetElement) {
                        targetElement.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            });

            // Dynamic Greeting based on Time of Day
            const greetingElement = document.createElement('div');
            greetingElement.id = 'greeting';
            document.querySelector('#header-box').appendChild(greetingElement);

            const now = new Date();
            const hours = now.getHours();
            let greetingMessage;

            if (hours < 12) {
                greetingMessage = 'Good Morning!';
            } else if (hours < 18) {
                greetingMessage = 'Good Afternoon!';
            } else {
                greetingMessage = 'Good Evening!';
            }

            greetingElement.textContent = greetingMessage;
        });
    </script>
</body>
</html>