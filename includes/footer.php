    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> IT Ticketing System. All Rights Reserved.</p>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="assets/js/script.js"></script>
    <?php if (isset($extraJS)): ?>
        <?php foreach ($extraJS as $js): ?>
            <script src="assets/js/<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
