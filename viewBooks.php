<?php
session_start();
include 'database.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['Member_ID'])) {
    header("Location: login.php");
    exit();
}

// Fetch books from the database
$stmt = $db->prepare("SELECT Book_ID, Book_Title, Author, Publish_Date, Genre, Keywords FROM books");
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($book_id, $book_title, $author, $publish_date, $genre, $keywords);

// Fetch the number of rows
$book_count = $stmt->num_rows;

// Collect the data into an array
$books = [];
while ($stmt->fetch()) {
    $books[] = [
        'Book_ID' => $book_id,
        'Book_Title' => $book_title,
        'Author' => $author,
        'Publish_Date' => $publish_date,
        'Genre' => $genre,
        'Keywords' => $keywords
    ];
}

$stmt->close();
$db->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Books - BookWave</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script>
        // Function to dynamically filter the table based on the search input
        function searchBooks() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const table = document.querySelector('table tbody');
            const rows = table.querySelectorAll('tr');

            rows.forEach(row => {
                const title = row.cells[0].textContent.toLowerCase();
                const author = row.cells[1].textContent.toLowerCase();
                const genre = row.cells[3].textContent.toLowerCase();
                const keywords = row.cells[4].textContent.toLowerCase();

                const titleMatch = title.includes(input);
                const authorMatch = author.includes(input);
                const genreMatch = genre.includes(input);
                const keywordsMatch = keywords.includes(input);

                if (titleMatch || authorMatch || genreMatch || keywordsMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
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
            <section id="books">
                <h2>Available Books</h2>

                <!-- Search Input -->
                <label for="searchInput">Search:</label>
                <input type="text" id="searchInput" onkeyup="searchBooks()" placeholder="Search by title, author, genre, or keywords">

                <?php if (count($books) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Published Date</th>
                                <th>Genre</th>
                                <th>Keywords</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($books as $book): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($book['Book_Title']); ?></td>
                                    <td><?php echo htmlspecialchars($book['Author']); ?></td>
                                    <td><?php echo htmlspecialchars($book['Publish_Date']); ?></td>
                                    <td><?php echo htmlspecialchars($book['Genre']); ?></td>
                                    <td><?php echo htmlspecialchars($book['Keywords']); ?></td>
                                    <td>
                                        <a href="viewBookDetails.php?book_id=<?php echo $book['Book_ID']; ?>" class="view-button">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No books available at the moment.</p>
                <?php endif; ?>
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