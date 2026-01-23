<?php
    session_start();
    $pageTitle = "Home";
    $extraCSS = ['styles.css'];
    include('includes/header.php');
?>

<main>
    <section class="hero">
        <div class="container">
            <h2>Welcome to the IT Support Center</h2>
            <p>Having an issue with your device or software? Submit a ticket and our team will assist you as soon as possible!</p>
            <a href="createticket.php" class="cta-button">Submit a Ticket</a>
        </div>
    </section>

    <section class="features">
        <div class="container features-container">
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

<?php include('includes/footer.php'); ?>
