<?php
include("config/database.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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

$message = "";

// Handle role updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $role = $_POST['role'];
    $sql_update_role = "UPDATE users SET role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql_update_role);
    $stmt->bind_param("si", $role, $user_id);
    if ($stmt->execute()) { $message = "User role updated successfully."; }
    $stmt->close();
}

// Handle delete
if (isset($_GET['delete_user_id'])) {
    $user_id = $_GET['delete_user_id'];
    $sql_delete = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) { $message = "User deleted successfully."; }
    $stmt->close();
}

$sql = "SELECT id, user, role FROM users";
$result = mysqli_query($conn, $sql);

$pageTitle = "Manage Users";
$extraCSS = ['admin.css'];
include('includes/header.php');
?>

<main>
    <section class="admin-section">
        <div class="container">
            <h2>Manage Users</h2>

            <?php if ($message): ?>
                <div style="padding: 15px; margin-bottom: 20px; border-radius: 5px; background: #dfd; color: #272;">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
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
                        <?php if ($result): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['user']); ?></td>
                                    <td>
                                        <form action='' method='post' class="inline-form">
                                            <input type='hidden' name='user_id' value='<?php echo $row['id']; ?>'>
                                            <select name='role' onchange="this.form.submit()">
                                                <option value='admin' <?php echo $row['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                                <option value='client' <?php echo $row['role'] == 'client' ? 'selected' : ''; ?>>Client</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <a href='?delete_user_id=<?php echo $row['id']; ?>' onclick="return confirm('Are you sure?')" class="delete-link">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>



<?php 
include('includes/footer.php'); 
mysqli_close($conn);
?>
