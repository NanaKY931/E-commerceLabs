<?php
require_once __DIR__ . '/../core/core.php';
?>
<?php include __DIR__ . '/layout/header.php'; ?>

<main class="failed-page">
    <div class="failed-container" style="padding: 20px; max-width: 600px; margin: 0 auto; text-align: center;">
        <h1 style="color: red;">Payment Failed</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <p style="color: red; margin: 20px 0;"><?= htmlspecialchars($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php else: ?>
            <p style="margin: 20px 0;">We could not process your payment at this time. Please try again.</p>
        <?php endif; ?>

        <div style="margin-top: 30px;">
            <a href="checkout.php" class="btn">Try Again</a>
            <a href="home.php" class="btn" style="margin-left: 10px;">Return to Shop</a>
        </div>
    </div>
</main>

<?php include __DIR__ . '/layout/footer.php'; ?>
