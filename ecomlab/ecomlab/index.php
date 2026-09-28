<?php
// index.php — the entry point / homepage of the app.
// Starts the session and shows context-aware content:
//   - guests see a welcome screen with Register / Login buttons
//   - logged-in users see a welcome message and a link to Customers
require_once "core/core.php";

$pageTitle = "EcomLab Home";
$navRoot   = "";   // we are already at the root, so no ../ prefix needed
$cssRoot   = "";
include "view/layout/header.php";
?>

    <div class="home-welcome">
        <?php if (is_logged_in()): ?>
            <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['customer_name']); ?>!</h1>
            <p>You are logged in as <strong><?php echo htmlspecialchars($_SESSION['customer_email']); ?></strong>.</p>
            <div class="btn-group">
                <a href="view/customers.php" class="btn-primary">View All Customers</a>
                <a href="actions/logout_action.php" class="btn-secondary">Logout</a>
            </div>
        <?php else: ?>
            <h1>Welcome to EcomLab</h1>
            <p>A simple PHP MVC demonstration of customer registration and login.</p>
            <div class="btn-group">
                <a href="view/register.php" class="btn-primary">Register</a>
                <a href="view/login.php" class="btn-secondary">Login</a>
            </div>
        <?php endif; ?>
    </div>

<?php include "view/layout/footer.php"; ?>
