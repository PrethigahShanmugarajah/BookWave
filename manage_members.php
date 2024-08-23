<?php
session_start();
include 'database.php'; // Include your database connection

// Check if the user is logged in as an admin
if (!isset($_SESSION['Admin_ID'])) {
    header("Location: adminlogin.php");
    exit();
}

// Handle member deletion
if (isset($_GET['delete'])) {
    $member_id = $_GET['delete'];
    $stmt = $db->prepare("DELETE FROM members WHERE Member_ID = ?");
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_members.php");
    exit();
}

// Handle member update
if (isset($_POST['update'])) {
    $member_id = $_POST['member_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $stmt = $db->prepare("UPDATE members SET Member_Name = ?, Member_Email = ? WHERE Member_ID = ?");
    $stmt->bind_param("ssi", $name, $email, $member_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_members.php");
    exit();
}

// Retrieve all members
$result = $db->query("SELECT * FROM members");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Members - BookWave</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script>
        // Confirm deletion of a member
        function confirmDeletion(memberId) {
            if (confirm('Are you sure you want to delete this member?')) {
                window.location.href = 'manage_members.php?delete=' + memberId;
            }
        }

        // Form validation
        function validateForm() {
            var name = document.getElementById('name').value.trim();
            var email = document.getElementById('email').value.trim();
            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

            if (name === '') {
                alert('Name is required');
                return false;
            }

            if (email === '') {
                alert('Email is required');
                return false;
            }

            if (!emailPattern.test(email)) {
                alert('Please enter a valid email address');
                return false;
            }

            return true;
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
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="manage_books.php">Manage Books</a></li>
                <li><a href="list_books.php">Existing Books</a></li>
                <li><a href="manage_members.php">Manage Members</a></li>
                <li><a href="adminlogout.php">Logout</a></li>
            </ul>
        </section>
    </header>

    <main id="content">
        <section id="manage-members">
            <h2>Manage Members</h2>
            
            <!-- Add/Edit Member Form -->
            <?php if (isset($_GET['edit'])): ?>
                <?php
                $member_id = $_GET['edit'];
                $stmt = $db->prepare("SELECT * FROM members WHERE Member_ID = ?");
                $stmt->bind_param("i", $member_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $member = $result->fetch_assoc();
                ?>
                <form action="manage_members.php" method="post" onsubmit="return validateForm()">
                    <h3>Edit Member</h3>
                    <input type="hidden" name="member_id" value="<?php echo htmlspecialchars($member['Member_ID']); ?>">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($member['Member_Name']); ?>" required>
                    
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($member['Member_Email']); ?>" required>
                    
                    <button type="submit" name="update">Update Member</button>
                </form>
            <?php else: ?>
                <!-- Add New Member Form -->
                <!-- (If you want to add new members, include this section) -->
                <!--
                <form action="manage_members.php" method="post" onsubmit="return validateForm()">
                    <h3>Add New Member</h3>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                    
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" required>
                    
                    <button type="submit" name="add">Add Member</button>
                </form>
                -->
            <?php endif; ?>

            <!-- Member List -->
            <h3>Existing Members</h3>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Registration Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['Member_Name']); ?></td>
                            <td><?php echo htmlspecialchars($row['Member_Email']); ?></td>
                            <td><?php echo htmlspecialchars($row['Registration_Date']); ?></td>
                            <td>
                                <a href="manage_members.php?edit=<?php echo $row['Member_ID']; ?>">Edit</a>
                                <a href="javascript:void(0);" onclick="confirmDeletion(<?php echo $row['Member_ID']; ?>);">Delete</a>
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
</body>
</html>