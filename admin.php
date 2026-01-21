<?php
    include("config/database.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Interface - IT Ticketing System</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Admin Interface - IT Ticketing System</h1>
            <nav>
                <ul>
                    <li><a href="homepage.php">Home</a></li>
                    <li><a href="manage_users.php">Manage users</a></li>
                    <li><a href="login.php">Logout</a></li>

                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="admin-section">
            <div class="container">
                <h2></h2>
                <table>
                    <thead>
                       
                    </thead>
                    <tbody>
                    <?php
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

// Handle ticket updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ticket_id']) && isset($_POST['progress'])) {
        // Update progress in the manage tickets table
        $ticket_id = $_POST['ticket_id'];
        $progress = $_POST['progress'];

        $sql_update_progress = "UPDATE `manage tickets` SET `progress` = ? WHERE `id` = ?";
        $stmt = $conn->prepare($sql_update_progress);
        $stmt->bind_param("si", $progress, $ticket_id);

        if ($stmt->execute()) {
            echo "Ticket progress updated successfully.";
        } else {
            echo "Error updating progress: " . $stmt->error;
        }

        $stmt->close();
    }

    if (isset($_POST['ticket_id']) && isset($_POST['urgency_level'])) {
        // Update urgency level in the manage tickets table
        $ticket_id = $_POST['ticket_id'];
        $urgency_level = $_POST['urgency_level'];

        $sql_update_urgency = "UPDATE `manage tickets` SET `ticket urgency` = ? WHERE `id` = ?";
        $stmt = $conn->prepare($sql_update_urgency);
        $stmt->bind_param("si", $urgency_level, $ticket_id);

        if ($stmt->execute()) {
            echo "Ticket urgency updated successfully.";
        } else {
            echo "Error updating urgency: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Check if a delete request is made
if (isset($_GET['delete_ticket_id'])) {
    $ticket_id = $_GET['delete_ticket_id'];

    // Prepare the DELETE statement
    $sql_delete = "DELETE FROM `manage tickets` WHERE `id ticket` = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $ticket_id);

    // Execute the query
    if ($stmt->execute()) {
        echo "Ticket deleted successfully.";
    } else {
        echo "Error deleting ticket: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch tickets from the manage tickets table
$sql = "SELECT id, `id ticket`, `created by`, `created for`, `issue type`, `description`, `progress`, `ticket urgency` 
        FROM `manage tickets`";

$result = mysqli_query($conn, $sql);

if ($result) {
    echo "<h2>Manage Tickets</h2>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Created By</th>
                    <th>Created For</th>
                    <th>Issue Type</th>
                    <th>Issue Description</th>
                    <th>Progress</th>
                    <th>Urgency Level</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['created by']}</td>
                <td>{$row['created for']}</td>
                <td>{$row['issue type']}</td>
                <td>{$row['description']}</td>
                <td>
                    <form action='admin.php' method='post' style='display:inline;'>
                        <input type='hidden' name='ticket_id' value='{$row['id']}'>
                        <select name='progress'>
                            <option value='In Progress' " . ($row['progress'] == 'In Progress' ? 'selected' : '') . ">In Progress</option>
                            <option value='Completed' " . ($row['progress'] == 'Completed' ? 'selected' : '') . ">Completed</option>
                            <option value='On Hold' " . ($row['progress'] == 'On Hold' ? 'selected' : '') . ">On Hold</option>
                        </select>
                        <button type='submit'>Update</button>
                    </form>
                </td>

                <td>
                    <form action='admin.php' method='post' style='display:inline;'>
                        <input type='hidden' name='ticket_id' value='{$row['id']}'>
                        <select name='urgency_level'>
                            <option value='low' " . ($row['ticket urgency'] == 'low' ? 'selected' : '') . ">Low</option>
                            <option value='medium' " . ($row['ticket urgency'] == 'medium' ? 'selected' : '') . ">Medium</option>
                            <option value='high' " . ($row['ticket urgency'] == 'high' ? 'selected' : '') . ">High</option>
                        </select>
                        <button type='submit'>Update</button>
                    </form>
                </td>
                <td>
                    <a href='edit_ticket.php?id={$row['id']}'>Edit</a>
                    <a href='admin.php?delete_ticket_id={$row['id ticket']}'>Delete</a>
                </td>
              </tr>";
    }

    echo "</tbody></table>";
} else {
    echo "Error fetching tickets: " . mysqli_error($conn);
}

// Close the connection
mysqli_close($conn);
?>




                        <tr>
</td>
                        </tr>
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
</body>
</html>
