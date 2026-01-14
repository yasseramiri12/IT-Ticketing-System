
<?php
    include("database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Ticketing System</title>
    <link rel="stylesheet" href="createticket.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>IT Ticketing System</h1>
            <nav>
                <ul>
                    <li><a href="homepage.php">Home</a></li>
                    <li><a href="#ticket-form">Submit a Ticket</a></li>
                    <li><a href="trackmyticket.php">Track Your Ticket</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>

        <section id="ticket-form">
            <div class="container">
                <h2>Submit a Ticket</h2>
                <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                    <label for="name">Created by:</label>
                    <input type="text" id="name" name="name" required>

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

                    <input type="hidden" name="client-id" value="1">

                    <button type="submit">Submit Ticket</button>
                </form>
            </div>
        </section>

        <section class="features">
            <div class="container">
                <div class="feature">
                    <h3>Easy Ticket Submission</h3>
                    <p>Submit tickets quickly and get immediate confirmation. Our team will review your request and get back to you promptly.</p>
                </div>
                <div class="feature">
                    <h3>Track Your Issues</h3>
                    <p>Easily track the status of your ticket and know when it will be resolved. Stay informed with real-time updates.</p>
                </div>
                <div class="feature">
                    <h3>Login to Your Dashboard</h3>
                    <p>Log in to view your ticket history and current ticket statuses. Manage all your IT support needs in one place.</p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 IT Ticketing System. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>

<?php
// Include your database connection file
include("database.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Connection to the database
$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user inputs
    $created_for = filter_input(INPUT_POST, "created-for", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $issue_type = filter_input(INPUT_POST, "issue-type", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $progress = "In Progress"; // Set default progress
    $ticket_urgency = filter_input(INPUT_POST, "ticket-urgency", FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Get urgency from form

    // Set a default urgency if it's not provided
    if (empty($ticket_urgency)) {
        $ticket_urgency = "medium"; // Default to 'medium' if no urgency level is provided
    }

    // Validate inputs
    if (empty($created_for)) {
        echo "Please enter who it was created for";
    } elseif (empty($email) || $email === false) {
        echo "Please enter a valid email";
    } elseif (empty($issue_type)) {
        echo "Please select an issue type";
    } elseif (empty($description)) {
        echo "Please provide a description of the issue";
    } else {
        // Ensure to use the id client from the session
        $id_client = $_SESSION['id_client']; // Get id client from the session

        // Use username for the created by column
        $created_by = $_SESSION['username']; // Get username from session

        // Start a transaction
        mysqli_begin_transaction($conn);

        try {
            // Insert the ticket into the it_ticket table
            $sql_it_ticket = "INSERT INTO it_ticket (`id client`, `created by`, `created for`, `email`, `issue type`, `issue description`) 
                              VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_it_ticket = $conn->prepare($sql_it_ticket);

            if ($stmt_it_ticket === false) {
                throw new Exception("Error preparing statement for it_ticket: " . $conn->error);
            }

            $stmt_it_ticket->bind_param('isssss', $id_client, $created_by, $created_for, $email, $issue_type, $description);
            $stmt_it_ticket->execute();

            // Get the last inserted id to use in manage tickets
            $last_id = $conn->insert_id;

            // Insert the ticket into the manage tickets table
            $sql_manage_tickets = "INSERT INTO `manage tickets` (`id ticket`, `created by`, `created for`, `issue type`, `progress`, `ticket urgency`, `description`) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt_manage_tickets = $conn->prepare($sql_manage_tickets);

            if ($stmt_manage_tickets === false) {
                throw new Exception("Error preparing statement for manage tickets: " . $conn->error);
            }

            $stmt_manage_tickets->bind_param('issssss', $last_id, $created_by, $created_for, $issue_type, $progress, $ticket_urgency, $description);
            $stmt_manage_tickets->execute();

            // Commit the transaction
            mysqli_commit($conn);

            echo "Your ticket has been submitted successfully.";
        } catch (Exception $e) {
            // Rollback the transaction in case of error
            mysqli_rollback($conn);
            echo "Error submitting ticket: " . $e->getMessage();
        } finally {
            // Close the prepared statements only if they were successfully initialized
            if (isset($stmt_it_ticket) && $stmt_it_ticket !== false) {
                $stmt_it_ticket->close();
            }
            if (isset($stmt_manage_tickets) && $stmt_manage_tickets !== false) {
                $stmt_manage_tickets->close();
            }
        }
    }
}

// Close the database connection
mysqli_close($conn);
?>
