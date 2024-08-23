<?php
session_start();
include 'database.php'; // Include your database connection

// Check if the user is logged in as an admin
if (!isset($_SESSION['Admin_ID'])) {
    header("Location: adminlogin.php");
    exit();
}

// Fetch books from the database
$stmt = $db->prepare("SELECT Book_ID, Book_Title, Author, Genre, Keywords, Publish_Date, Book_PDF FROM books");
$stmt->execute();
$result = $stmt->get_result();

$stmt->close();
$db->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book List - BookWave</title>
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
            <section id="book-list">
                <h2>Existing Books</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Genre</th>
                            <th>Keywords</th>
                            <th>Publish Date</th>
                            <th>PDF</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['Book_Title']); ?></td>
                                <td><?php echo htmlspecialchars($row['Author']); ?></td>
                                <td><?php echo htmlspecialchars($row['Genre']); ?></td>
                                <td><?php echo htmlspecialchars($row['Keywords']); ?></td>
                                <td><?php echo htmlspecialchars($row['Publish_Date']); ?></td>
                                <td>
                                    <?php if ($row['Book_PDF']): ?>
                                        <a href="<?php echo htmlspecialchars($row['Book_PDF']); ?>" target="_blank">View PDF</a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="manage_books.php?edit=<?php echo $row['Book_ID']; ?>">Edit</a>
                                    <a href="manage_books.php?delete=<?php echo $row['Book_ID']; ?>" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
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