<?php
    // Example database connection settings
    // Rename this file to 'database.php' and update with your local credentials

    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "ticket";

    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

    if (!$conn) {
        // Log the error instead of echoing it to avoid breaking redirects
        error_log("Database connection failed: " . mysqli_connect_error());
    }
?>
