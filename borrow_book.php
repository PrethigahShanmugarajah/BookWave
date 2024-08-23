<?php
session_start();
include 'database.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['Member_ID'])) {
    header("Location: login.php");
    exit();
}

// Check if the book_id is set
if (!isset($_POST['book_id'])) {
    header("Location: viewBooks.php");
    exit();
}

$book_id = $_POST['book_id'];
$member_id = $_SESSION['Member_ID'];
$borrow_date = date('Y-m-d');

// Check if the book is available
$stmt = $db->prepare("SELECT Available FROM books WHERE Book_ID = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$stmt->bind_result($available);
$stmt->fetch();
$stmt->close();

if ($available) {
    // Insert the borrowing record
    $stmt = $db->prepare("INSERT INTO borrowing_records (Book_ID, Member_ID, Borrow_Date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $book_id, $member_id, $borrow_date);
    $stmt->execute();
    $stmt->close();

    // Mark the book as unavailable
    $stmt = $db->prepare("UPDATE books SET Available = FALSE WHERE Book_ID = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $stmt->close();

    header("Location: viewBooks.php?message=Book borrowed successfully.");
} else {
    header("Location: viewBooks.php?error=Book is not available.");
}

$db->close();
exit();
?>
