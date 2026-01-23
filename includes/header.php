<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . " - IT Ticketing System" : "IT Ticketing System"; ?></title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="assets/css/main.css">
    <?php if (isset($extraCSS)): ?>
        <?php foreach ($extraCSS as $css): ?>
            <link rel="stylesheet" href="assets/css/<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <header>
        <div class="container header-container">
            <div class="logo">
                <h1><a href="homepage.php">IT Ticketing System</a></h1>
            </div>
            <nav>
                <ul>
                    <li><a href="homepage.php">Home</a></li>
                    <li><a href="createticket.php">Submit a Ticket</a></li>
                    <li><a href="trackmyticket.php">Track Your Ticket</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li><a href="admin.php">Manage Tickets</a></li>
                            <li><a href="manage_users.php">Manage Users</a></li>
                        <?php endif; ?>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
