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

// Handle ticket updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ticket_id']) && isset($_POST['progress'])) {
        $ticket_id = $_POST['ticket_id'];
        $progress = $_POST['progress'];
        $sql_update_progress = "UPDATE `manage tickets` SET `progress` = ? WHERE `id` = ?";
        $stmt = $conn->prepare($sql_update_progress);
        $stmt->bind_param("si", $progress, $ticket_id);
        if ($stmt->execute()) { $message = "Ticket progress updated successfully."; }
        $stmt->close();
    }

    if (isset($_POST['ticket_id']) && isset($_POST['urgency_level'])) {
        $ticket_id = $_POST['ticket_id'];
        $urgency_level = $_POST['urgency_level'];
        $sql_update_urgency = "UPDATE `manage tickets` SET `ticket urgency` = ? WHERE `id` = ?";
        $stmt = $conn->prepare($sql_update_urgency);
        $stmt->bind_param("si", $urgency_level, $ticket_id);
        if ($stmt->execute()) { $message = "Ticket urgency updated successfully."; }
        $stmt->close();
    }
}

// Handle delete
if (isset($_GET['delete_ticket_id'])) {
    $ticket_id = $_GET['delete_ticket_id'];
    $sql_delete = "DELETE FROM `manage tickets` WHERE `id ticket` = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $ticket_id);
    if ($stmt->execute()) { $message = "Ticket deleted successfully."; }
    $stmt->close();
}

$sql = "SELECT id, `id ticket`, `created by`, `created for`, `issue type`, `description`, `progress`, `ticket urgency` FROM `manage tickets`";
$result = mysqli_query($conn, $sql);

$pageTitle = "Admin Dashboard";
$extraCSS = ['admin.css'];
include('includes/header.php');
?>

<main>
    <section class="admin-section">
        <div class="container">
            <h2>Manage Tickets</h2>
            
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
                            <th>Created By</th>
                            <th>Created For</th>
                            <th>Issue Type</th>
                            <th>Description</th>
                            <th>Progress</th>
                            <th>Urgency</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['created by']); ?></td>
                                    <td><?php echo htmlspecialchars($row['created for']); ?></td>
                                    <td><?php echo htmlspecialchars($row['issue type']); ?></td>
                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                    <td>
                                        <form action='admin.php' method='post' class="inline-form">
                                            <input type='hidden' name='ticket_id' value='<?php echo $row['id']; ?>'>
                                            <select name='progress' onchange="this.form.submit()">
                                                <option value='In Progress' <?php echo $row['progress'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                                <option value='Completed' <?php echo $row['progress'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                                <option value='On Hold' <?php echo $row['progress'] == 'On Hold' ? 'selected' : ''; ?>>On Hold</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <form action='admin.php' method='post' class="inline-form">
                                            <input type='hidden' name='ticket_id' value='<?php echo $row['id']; ?>'>
                                            <select name='urgency_level' onchange="this.form.submit()">
                                                <option value='low' <?php echo $row['ticket urgency'] == 'low' ? 'selected' : ''; ?>>Low</option>
                                                <option value='medium' <?php echo $row['ticket urgency'] == 'medium' ? 'selected' : ''; ?>>Medium</option>
                                                <option value='high' <?php echo $row['ticket urgency'] == 'high' ? 'selected' : ''; ?>>High</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <a href='admin.php?delete_ticket_id=<?php echo $row['id ticket']; ?>' onclick="return confirm('Are you sure?')" class="delete-link">Delete</a>
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
