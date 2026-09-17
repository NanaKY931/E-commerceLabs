<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppn - E-Commerce</title>
    <!-- Assuming style.css will be created in css/ -->
    <link rel="stylesheet" href="/shoppn/css/style.css">
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <div class="logo">
                <a href="/shoppn/index.php">Shoppn</a>
            </div>
            
            <div class="search-bar">
                <form action="/shoppn/views/search_results.php" method="GET">
                    <input type="text" name="user_query" placeholder="Search products...">
                    <button type="submit">Search</button>
                </form>
            </div>
            
            <ul class="nav-links">
                <!-- Cart Summary Placeholder for Task 11 -->
                <li><a href="/shoppn/views/cart.php">Cart (0)</a></li>
                
                <?php if (is_logged_in()): ?>
                    <li>Welcome, <?= htmlspecialchars($_SESSION['customer_name'] ?? 'User') ?></li>
                    <li><a href="/shoppn/views/account/my_account.php">My Account</a></li>
                    
                    <?php if (is_admin()): ?>
                        <li><a href="/shoppn/views/admin/brand.php">Admin</a></li>
                    <?php endif; ?>
                    
                    <li><a href="/shoppn/logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="/shoppn/views/login.php">Login</a></li>
                    <li><a href="/shoppn/views/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <div class="main-container">
