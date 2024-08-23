<?php
session_start();
include 'database.php'; // Include your database connection

// Check if the user is logged in as an admin
if (!isset($_SESSION['Admin_ID'])) {
    header("Location: adminlogin.php");
    exit();
}

// Handle admin deletion
if (isset($_GET['delete'])) {
    $admin_id = $_GET['delete'];
    $stmt = $db->prepare("DELETE FROM admins WHERE Admin_ID = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Admin deleted successfully!');</script>";
    header("Refresh: 1; url=manage_admins.php");
    exit();
}

// Retrieve all admins
$result = $db->query("SELECT * FROM admins");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admins - BookWave</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        th {
            cursor: pointer;
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
        <section id="manage-admins">
            <h2>Manage Admins</h2>
            
            <!-- Admin List -->
            <table id="admin-table">
                <thead>
                    <tr>
                        <th onclick="sortTable(0)">Name</th>
                        <th onclick="sortTable(1)">Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['Admin_Name']); ?></td>
                            <td><?php echo htmlspecialchars($row['Admin_Email']); ?></td>
                            <td>
                                <a href="manage_admins.php?delete=<?php echo $row['Admin_ID']; ?>" onclick="return confirm('Are you sure you want to delete this admin?');">Delete</a>
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
        </div>
    </footer>
</div>

<script>
    // Function to sort the table
    function sortTable(n) {
        const table = document.getElementById("admin-table");
        let rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        switching = true;
        dir = "asc"; // Set the sorting direction to ascending

        while (switching) {
            switching = false;
            rows = table.rows;

            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];

                if (dir === "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir === "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }
            }

            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount++;
            } else {
                if (switchcount === 0 && dir === "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    }

    // Confirmation alert for admin deletion
    document.addEventListener('DOMContentLoaded', function() {
        const deleteLinks = document.querySelectorAll('a[onclick*="confirm"]');
        deleteLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const confirmation = confirm('Are you sure you want to delete this admin?');
                if (!confirmation) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
</body>
</html>