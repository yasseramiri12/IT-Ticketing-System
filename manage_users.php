<?php
include("config/database.php");
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Establish database connection
$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle role updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $role = $_POST['role'];

    $sql_update_role = "UPDATE users SET role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql_update_role);
    $stmt->bind_param("si", $role, $user_id);

    if ($stmt->execute()) {
        echo "User role updated successfully.";
    } else {
        echo "Error updating user role: " . $stmt->error . " (" . $stmt->errno . ")";
    }

    $stmt->close();
}

// Check if a delete request is made
if (isset($_GET['delete_user_id'])) {
    $user_id = $_GET['delete_user_id'];

    // Prepare the DELETE statement
    $sql_delete = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $user_id);

    // Execute the query
    if ($stmt->execute()) {
        echo "User deleted successfully.";
    } else {
        echo "Error deleting user: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch users from the users table
$sql = "SELECT id, user, role FROM users";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - IT Ticketing System</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <div class="container">
            <h1>Manage Users - IT Ticketing System</h1>
            <nav>
                <ul>
                    <li><a href="homepage.php">Home</a></li>
                    <li><a href="admin.php">Manage Tickets</a></li>
                    <li><a href="login.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="admin-section">
            <div class="container">
                <h2>Manage Users</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['user']}</td>
                                    <td>
                                        <form action='' method='post' style='display:inline;' class='update-role-form'>
                                            <input type='hidden' name='user_id' value='{$row['id']}'>
                                            <select name='role'>
                                                <option value='admin' " . ($row['role'] == 'admin' ? 'selected' : '') . ">Admin</option>
                                                <option value='client' " . ($row['role'] == 'client' ? 'selected' : '') . ">Client</option>
                                            </select>
                                            <button type='submit'>Update</button>
                                        </form>
                                    </td>
                                    <td><a href='?delete_user_id={$row['id']}' class='delete-user'>Delete</a></td>
                                  </tr>";
                        }
                    } else {
                        echo "Error fetching users: " . mysqli_error($conn);
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 IT Ticketing System. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        $(document).ready(function() {
            $('.update-role-form').on('submit', function(e) {
                e.preventDefault(); // Prevent the form from submitting normally
                var form = $(this);
                $.ajax({
                    type: 'POST',
                    url: 'manage_users.php', // Send to the same page
                    data: form.serialize(), // Serialize form data
                    success: function(response) {
                        alert(response); // Display success message
                    },
                    error: function() {
                        alert('Error updating user role.'); // Display error message
                    }
                });
            });

            $('.delete-user').on('click', function(e) {
                if (!confirm('Are you sure you want to delete this user?')) {
                    e.preventDefault(); // Prevent the deletion if cancelled
                }
            });
        });
    </script>
</body>
</html>
<?php
mysqli_close($conn);
?>
