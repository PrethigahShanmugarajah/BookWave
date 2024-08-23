<?php
session_start();
include 'database.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['Member_ID'])) {
    header("Location: login.php");
    exit();
}

// Get the book ID from the URL parameter
$book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;

// Fetch the book details from the database
$stmt = $db->prepare("SELECT Book_Title, Author, Publish_Date, Genre, Keywords, Book_PDF FROM books WHERE Book_ID = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($book_title, $author, $publish_date, $genre, $keywords, $book_pdf);

// Check if the book exists
if ($stmt->num_rows === 0) {
    echo "Book not found.";
    exit();
}

$stmt->fetch();
$stmt->close();

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = htmlspecialchars($_POST['comment']);
    $member_id = $_SESSION['Member_ID'];
    
    if ($comment) {
        $stmt = $db->prepare("INSERT INTO comments (Book_ID, Member_ID, Comment) VALUES (?, ?, ?)");
        $stmt->bind_param('iis', $book_id, $member_id, $comment);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch comments for the book
$stmt = $db->prepare("SELECT c.Comment, m.Member_Name, c.Created_At FROM comments c JOIN members m ON c.Member_ID = m.Member_ID WHERE c.Book_ID = ?");
$stmt->bind_param('i', $book_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($comment, $member_name, $created_at);

$comments = [];
while ($stmt->fetch()) {
    $comments[] = [
        'Comment' => $comment,
        'Member_Name' => $member_name,
        'Created_At' => $created_at
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
    <title>Book Details - BookWave</title>
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
                    <li><a href="dashboard.php">Home</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="viewBooks.php">View Books</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </section>
        </header>

        <main id="content">
            <section id="book-details">
                <h2>Book Details</h2>
                <h3><?php echo htmlspecialchars($book_title); ?></h3>
                <p><strong>Author:</strong> <?php echo htmlspecialchars($author); ?></p>
                <p><strong>Published Date:</strong> <?php echo htmlspecialchars($publish_date); ?></p>
                <p><strong>Genre:</strong> <?php echo htmlspecialchars($genre); ?></p>
                <p><strong>Keywords:</strong> <?php echo htmlspecialchars($keywords); ?></p>
                <?php if ($book_pdf): ?>
                    <p><a href="<?php echo htmlspecialchars($book_pdf); ?>" target="_blank">View PDF</a></p>
                <?php endif; ?>

                <!-- Comment Form -->
                <h3>Add a Comment</h3>
                <form action="" method="post">
                    <textarea name="comment" rows="4" cols="50" placeholder="Write your comment here..." required></textarea><br>
                    <input type="submit" value="Submit Comment">
                </form>

                <!-- Display Comments -->
                <h3>Comments</h3>
                <?php if (count($comments) > 0): ?>
                    <ul>
                        <?php foreach ($comments as $comment): ?>
                            <li>
                                <strong><?php echo htmlspecialchars($comment['Member_Name']); ?></strong> 
                                <em><?php echo htmlspecialchars($comment['Created_At']); ?></em>
                                <p><?php echo htmlspecialchars($comment['Comment']); ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No comments yet.</p>
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