<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppn - E-Commerce</title>
    <!-- Assuming style.css will be created in css/ -->
    <link rel="stylesheet" href="<?= ROOT_URL ?>css/style.css">
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <div class="logo">
                <a href="<?= ROOT_URL ?>index.php">Shoppn</a>
            </div>
            
            <div class="search-bar">
                <form action="<?= ROOT_URL ?>views/search_results.php" method="GET">
                    <input type="text" name="user_query" placeholder="Search products...">
                    <button type="submit">Search</button>
                </form>
            </div>
            
            <ul class="nav-links">
                <!-- Cart Summary Placeholder for Task 11 -->
                <li><a href="<?= ROOT_URL ?>views/cart.php">Cart (0)</a></li>
                
                <?php if (is_logged_in()): ?>
                    <li class="welcome-text">Welcome, <?= htmlspecialchars($_SESSION['customer_name'] ?? 'User') ?></li>
                    <li><a href="<?= ROOT_URL ?>views/account/my_account.php">My Account</a></li>
                    
                    <?php if (is_admin()): ?>
                        <li><a href="<?= ROOT_URL ?>views/admin/brand.php">Brand</a></li>
                        <li><a href="<?= ROOT_URL ?>views/admin/category.php">Category</a></li>
                        <li><a href="<?= ROOT_URL ?>views/admin/product.php">Products</a></li>
                    <?php endif; ?>
                    
                    <li><a href="<?= ROOT_URL ?>logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="<?= ROOT_URL ?>views/login.php">Login</a></li>
                    <li><a href="<?= ROOT_URL ?>views/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <div class="main-container">
