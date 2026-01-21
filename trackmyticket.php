<?php
include("config/database.php");
session_start(); // Start the session

// Establish database connection
$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Ticket - IT Ticketing System</title>
    <link rel="stylesheet" href="assets/css/trackmyticket.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>IT Ticketing System</h1>
            <nav>
                <ul>
                    <li><a href="homepage.php">Home</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="createticket.php">Submit a Ticket</a></li>
                    <li><a href="trackmyticket.php">Track Your Ticket</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section id="track-ticket">
            <div class="container">
                <h2>Track Your Ticket</h2>
                <form action="trackmyticket.php" method="post">
                    <label for="user-name">Enter your name:</label>
                    <input type="text" id="user-name" name="user-name" required>
                    <button type="submit">Track Ticket</button>
                </form>

                <div id="ticket-status" style="margin-top: 20px;">
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Get the user's name from the form input
                    $user_name = filter_input(INPUT_POST, "user-name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                    if (empty($user_name)) {
                        echo "Please enter your name.";
                    } else {
                        // Query to fetch the tickets created by the entered user name
                        $sql = "SELECT mt.id, mt.`id ticket`, mt.`created by`, mt.`created for`, mt.`issue type`, mt.`description`, mt.`progress`, mt.`ticket urgency`, it.id as `it_ticket_id`
                                FROM `manage tickets` mt
                                JOIN `it_ticket` it ON mt.`id ticket` = it.id
                                WHERE mt.`created by` = ?";

                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('s', $user_name); // Use 's' for string

                        if ($stmt->execute()) {
                            $result = $stmt->get_result();

                            // Check if any records are returned
                            if ($result->num_rows > 0) {
                                // Display the results in a table
                                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                                echo "<tr><th>ID</th><th>ID Ticket</th><th>Created By</th><th>Created For</th><th>Issue Type</th><th>Issue Description</th><th>Progress</th><th>Urgency Level</th></tr>";
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>" . htmlspecialchars($row['id']) . "</td>
                                            <td>" . htmlspecialchars($row['id ticket']) . "</td>
                                            <td>" . htmlspecialchars($row['created by']) . "</td>
                                            <td>" . htmlspecialchars($row['created for']) . "</td>
                                            <td>" . htmlspecialchars($row['issue type']) . "</td>
                                            <td>" . htmlspecialchars($row['description']) . "</td>
                                            <td>" . htmlspecialchars($row['progress']) . "</td>
                                            <td>" . htmlspecialchars($row['ticket urgency']) . "</td>
                                          </tr>";
                                }
                                echo "</table>";
                            } else {
                                echo "No tickets found for this user.";
                            }
                        } else {
                            echo "Error retrieving tickets: " . $stmt->error;
                        }

                        $stmt->close();
                    }
                }
                ?>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 IT Ticketing System. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
?>
