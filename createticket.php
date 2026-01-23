<?php
include("config/database.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $created_for = filter_input(INPUT_POST, "created-for", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $issue_type = filter_input(INPUT_POST, "issue-type", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $progress = "In Progress";
    $ticket_urgency = filter_input(INPUT_POST, "ticket-urgency", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: "medium";

    if (empty($created_for)) {
        $message = "Please enter who it was created for";
    } elseif (empty($email) || $email === false) {
        $message = "Please enter a valid email";
    } elseif (empty($issue_type)) {
        $message = "Please select an issue type";
    } elseif (empty($description)) {
        $message = "Please provide a description of the issue";
    } else {
        $id_client = $_SESSION['id_client'] ?? 0;
        $created_by = $_SESSION['username'] ?? 'Anonymous';

        mysqli_begin_transaction($conn);
        try {
            $sql_it_ticket = "INSERT INTO it_ticket (`id client`, `created by`, `created for`, `email`, `issue type`, `issue description`) 
                              VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_it_ticket = $conn->prepare($sql_it_ticket);
            $stmt_it_ticket->bind_param('isssss', $id_client, $created_by, $created_for, $email, $issue_type, $description);
            $stmt_it_ticket->execute();

            $last_id = $conn->insert_id;

            $sql_manage_tickets = "INSERT INTO `manage tickets` (`id ticket`, `created by`, `created for`, `issue type`, `progress`, `ticket urgency`, `description`) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt_manage_tickets = $conn->prepare($sql_manage_tickets);
            $stmt_manage_tickets->bind_param('issssss', $last_id, $created_by, $created_for, $issue_type, $progress, $ticket_urgency, $description);
            $stmt_manage_tickets->execute();

            mysqli_commit($conn);
            $message = "Your ticket has been submitted successfully.";
            $messageType = "success";
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $message = "Error submitting ticket: " . $e->getMessage();
            $messageType = "error";
        }
    }
}

mysqli_close($conn);

$pageTitle = "Submit a Ticket";
$extraCSS = ['createticket.css'];
include('includes/header.php');
?>

<main>
    <section id="ticket-form">
        <div class="container">
            <h2>Submit a Ticket</h2>
            
            <?php if ($message): ?>
                <div style="padding: 15px; margin-bottom: 20px; border-radius: 5px; background: <?php echo $messageType === 'success' ? '#dfd' : '#fdd'; ?>; color: <?php echo $messageType === 'success' ? '#272' : '#722'; ?>;">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="name">Created by:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>" readonly>

                <label for="created-for">Created For:</label>
                <input type="text" id="created-for" name="created-for" required placeholder="Enter name of the person">

                <label for="email">Your Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="issue-type">Issue Type:</label>
                <select id="issue-type" name="issue-type" required>
                    <option value="hardware">Hardware Issue</option>
                    <option value="software">Software Issue</option>
                    <option value="network">Network Issue</option>
                    <option value="other">Other</option>
                </select>

                <label for="description">Issue Description:</label>
                <textarea id="description" name="description" rows="6" required></textarea>

                <button type="submit" class="cta-button">Submit Ticket</button>
            </form>
        </div>
    </section>
</main>

<?php include('includes/footer.php'); ?>
