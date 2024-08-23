<?php
session_start();
include 'database.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['Member_ID'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $record_id = $_GET['id'];
    $member_id = $_SESSION['Member_ID'];

    // Update the return status of the book
    $stmt = $db->prepare("UPDATE borrowing_records SET Returned = TRUE, Return_Date = NOW() WHERE Borrowing_Record_ID = ? AND Member_ID = ?");
    $stmt->bind_param("ii", $record_id, $member_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to borrowedBooks.php with a success message
    header("Location: borrowedBooks.php?success=1");
    exit();
} else {
    // Redirect to borrowedBooks.php with an error message if no ID is provided
    header("Location: borrowedBooks.php?error=1");
    exit();
}
?>
