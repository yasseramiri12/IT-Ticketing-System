<?php
// Include your database connection file
include("config/database.php");

// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
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

                // Direct comparison of passwords
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

$pageTitle = "Login";
$extraCSS = ['login.css'];
include('includes/header.php');
?>

<main>
    <section id="login">
        <div class="container">
            <h2>Login</h2>
            <?php if (!empty($login_error)): ?>
                <p style="color: red; background: #fee; padding: 10px; border-radius: 5px;"><?php echo htmlspecialchars($login_error); ?></p>
            <?php endif; ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit" class="cta-button">Login</button>
            </form>

        </div>
    </section>
</main>

<?php include('includes/footer.php'); ?>
