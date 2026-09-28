<!--
    Shared navigation header — included at the top of every view.
    It reads $_SESSION to decide which links to show:
      - Guest:     Register | Login
      - Logged-in: Welcome [name] | Customers | Logout
-->
<?php
// core.php must already have been included by the page that includes
// this header, so is_logged_in() is available here without another require.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Ecom Lab'; ?></title>
    <link rel="stylesheet" href="<?php echo isset($cssRoot) ? $cssRoot : '../'; ?>css/style.css">
</head>
<body>

<header>
    <nav>
        <a href="<?php echo isset($navRoot) ? $navRoot : '../'; ?>index.php"><strong>EcomLab</strong></a>

        <span class="nav-links">
        <?php if (is_logged_in()): ?>
            <!-- Shown to any authenticated user -->
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['customer_name']); ?></span>
            <a href="<?php echo isset($navRoot) ? $navRoot : '../'; ?>view/customers.php">Customers</a>
            <a href="<?php echo isset($navRoot) ? $navRoot : '../'; ?>actions/logout_action.php">Logout</a>
        <?php else: ?>
            <!-- Shown to guests -->
            <a href="<?php echo isset($navRoot) ? $navRoot : '../'; ?>view/register.php">Register</a>
            <a href="<?php echo isset($navRoot) ? $navRoot : '../'; ?>view/login.php">Login</a>
        <?php endif; ?>
        </span>
    </nav>
</header>

<main>
