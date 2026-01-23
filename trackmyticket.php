<?php
include("config/database.php");
session_start();

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$pageTitle = "Track Your Ticket";
$extraCSS = ['trackmyticket.css'];
include('includes/header.php');
?>

<main>
    <section id="track-ticket">
        <div class="container">
            <h2>Track Your Ticket</h2>
            <form action="trackmyticket.php" method="post">
                <label for="user-name">Enter your name to track:</label>
                <input type="text" id="user-name" name="user-name" value="<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>" required>
                <button type="submit" class="cta-button">Track Ticket</button>
            </form>

            <div id="ticket-status" style="margin-top: 40px;">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $user_name = filter_input(INPUT_POST, "user-name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                if (empty($user_name)) {
                    echo "<p>Please enter your name.</p>";
                } else {
                    $sql = "SELECT mt.id, mt.`id ticket`, mt.`created by`, mt.`created for`, mt.`issue type`, mt.`description`, mt.`progress`, mt.`ticket urgency`
                            FROM `manage tickets` mt
                            WHERE mt.`created by` = ?";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('s', $user_name);

                    if ($stmt->execute()) {
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            echo "<div class='table-responsive'>";
                            echo "<table>";
                            echo "<thead><tr><th>ID</th><th>Ticket ID</th><th>Created By</th><th>Created For</th><th>Type</th><th>Description</th><th>Progress</th><th>Urgency</th></tr></thead>";
                            echo "<tbody>";
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . htmlspecialchars($row['id']) . "</td>
                                        <td>" . htmlspecialchars($row['id ticket']) . "</td>
                                        <td>" . htmlspecialchars($row['created by']) . "</td>
                                        <td>" . htmlspecialchars($row['created for']) . "</td>
                                        <td>" . htmlspecialchars($row['issue type']) . "</td>
                                        <td>" . htmlspecialchars($row['description']) . "</td>
                                        <td><span class='badge' data-status='".strtolower(str_replace(' ', '-', $row['progress']))."'>" . htmlspecialchars($row['progress']) . "</span></td>
                                        <td>" . htmlspecialchars($row['ticket urgency']) . "</td>
                                      </tr>";
                            }
                            echo "</tbody></table></div>";
                        } else {
                            echo "<p>No tickets found for this user.</p>";
                        }
                    } else {
                        echo "<p>Error retrieving tickets: " . $stmt->error . "</p>";
                    }
                    $stmt->close();
                }
            }
            ?>
            </div>
        </div>
    </section>
</main>



<?php 
include('includes/footer.php'); 
mysqli_close($conn);
?>
