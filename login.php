<?php
// Include your database connection file
include("config/database.php");

// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Connection to the database
$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$login_error = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user inputs
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Validate user inputs
    if (empty($username)) {
        $login_error = "Please enter your username.";
    } elseif (empty($password)) {
        $login_error = "Please enter your password.";
    } else {
        // Query the database for user credentials
        $sql = "SELECT id, password, role FROM users WHERE user = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($user_id, $stored_password, $role);
                $stmt->fetch();

                // Direct comparison of passwords (consider using password_hash/verify in production)
                if ($password === $stored_password) {
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['id'] = $user_id;
                    $_SESSION['id_client'] = $user_id;
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $role;

                    // Redirect based on role
                    if ($role === 'admin') {
                        header("Location: admin.php");
                        exit();
                    } else {
                        header("Location: createticket.php");
                        exit();
                    }
                } else {
                    $login_error = "Invalid username or password.";
                }
            } else {
                $login_error = "Invalid username or password.";
            }
            $stmt->close();
        } else {
            $login_error = "Database error. Please try again later.";
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IT Ticketing System</title>
    <link rel="stylesheet" href="assets/css/login.css">
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
        <section id="login">
            <div class="container">
                <h2>Login</h2>
                <?php if (!empty($login_error)): ?>
                    <p style="color: red;"><?php echo htmlspecialchars($login_error); ?></p>
                <?php endif; ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>

                    <button type="submit">Login</button>
                </form>
                <p>Don't have an account? <a href="#">Sign up here</a></p>
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

