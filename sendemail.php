<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = "admin@example.com";
    $name = $_POST["fullname"];
    $from_email = $_POST["email"];
    $subject = "New message from: " . $name;
    $message = $_POST["message"];
    $headers = "From: " . $from_email;

    if (mail($to, $subject, $message, $headers)) {
        echo "Email Sent";
    } else {
        echo "Email sending failed";
    }
}
?>