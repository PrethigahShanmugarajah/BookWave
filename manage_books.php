<?php
session_start();
include 'database.php'; // Include your database connection

// Check if the user is logged in as an admin
if (!isset($_SESSION['Admin_ID'])) {
    header("Location: adminlogin.php");
    exit();
}

// Handle book deletion
if (isset($_GET['delete'])) {
    $book_id = $_GET['delete'];
    $stmt = $db->prepare("DELETE FROM books WHERE Book_ID = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_books.php");
    exit();
}

// Handle book update
if (isset($_POST['update'])) {
    $book_id = $_POST['book_id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $keywords = $_POST['keywords'];
    $publish_date = $_POST['publish_date'];
    $pdf_path = $_POST['pdf_path']; // Keep the existing PDF path

    // Handle PDF upload
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0) {
        $file_name = $_FILES['pdf']['name'];
        $file_tmp = $_FILES['pdf']['tmp_name'];
        $file_size = $_FILES['pdf']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Check file extension and size
        if ($file_ext != 'pdf') {
            echo "Only PDF files are allowed.";
            exit();
        }
        if ($file_size > 100000000) { // 100MB limit
            echo "File size exceeds 100MB.";
            exit();
        }
        
        $upload_dir = 'uploads/';
        $file_path = $upload_dir . basename($file_name);
        
        // Ensure the upload directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if (!move_uploaded_file($file_tmp, $file_path)) {
            echo "Error uploading file.";
            exit();
        }
        $pdf_path = $file_path; // Update PDF path
    }

    $stmt = $db->prepare("UPDATE books SET Book_Title = ?, Author = ?, Genre = ?, Keywords = ?, Publish_Date = ?, Book_PDF = ? WHERE Book_ID = ?");
    $stmt->bind_param("ssssssi", $title, $author, $genre, $keywords, $publish_date, $pdf_path, $book_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_books.php");
    exit();
}

// Handle book addition
if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $keywords = $_POST['keywords'];
    $publish_date = $_POST['publish_date'];

    // Handle PDF upload
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0) {
        $file_name = $_FILES['pdf']['name'];
        $file_tmp = $_FILES['pdf']['tmp_name'];
        $file_size = $_FILES['pdf']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Check file extension and size
        if ($file_ext != 'pdf') {
            echo "Only PDF files are allowed.";
            exit();
        }
        if ($file_size > 100000000) { // 100MB limit
            echo "File size exceeds 100MB.";
            exit();
        }
        
        $upload_dir = 'uploads/';
        $file_path = $upload_dir . basename($file_name);
        
        // Ensure the upload directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if (!move_uploaded_file($file_tmp, $file_path)) {
            echo "Error uploading file.";
            exit();
        }
    } else {
        $file_path = null; // No file uploaded
    }

    $stmt = $db->prepare("INSERT INTO books (Book_Title, Author, Genre, Keywords, Publish_Date, Book_PDF) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $title, $author, $genre, $keywords, $publish_date, $file_path);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_books.php");
    exit();
}

// Retrieve all books
$result = $db->query("SELECT * FROM books");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books - BookWave</title>
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
                <li><a href="adminlogout.php">Logout</a></li>
            </ul>
        </section>
    </header>

    <main id="content">
        <section id="manage-books">
            <h2>Manage Books</h2>
            
            <!-- Add Book Form -->
            <form action="manage_books.php" method="post" enctype="multipart/form-data">
                <h3>Add New Book</h3>
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
                
                <label for="author">Author:</label>
                <input type="text" id="author" name="author" required>
                
                <label for="genre">Genre:</label>
                <select id="genre" name="genre" required>
                    <option value="">Select</option>
                    <option value="Fiction">Fiction</option>
                    <option value="Non-Fiction">Non-Fiction</option>
                    <option value="Science">Science</option>
                    <option value="Biography">Biography</option>
                    <option value="History">History</option>
                    <option value="Mystery">Mystery</option>
                    <option value="Programming Language">Programming Language</option>
                    <!-- Add more genres as needed -->
                </select>
                
                <label for="keywords">Keywords:</label>
                <input type="text" id="keywords" name="keywords">
                
                <label for="publish_date">Publish Date:</label>
                <input type="date" id="publish_date" name="publish_date">
                
                <label for="pdf">Upload PDF:</label>
                <input type="file" id="pdf" name="pdf" accept=".pdf">
                
                <button type="submit" name="add">Add Book</button>
            </form>

            
        </section>
    </main>

    <footer>
        <div id="footer-box">
            <p>Copyright &copy; 2024 BookWave. All rights reserved.</p>
        </div>
    </footer>
</div>    
</body>
</html>