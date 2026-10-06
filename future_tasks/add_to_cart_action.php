<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

$product_id = filter_var($_GET['add_cart'] ?? '', FILTER_VALIDATE_INT);
$ip = get_ip();
$customer_id = $_SESSION['customer_id'] ?? null;
$qty = 1; // Default quantity

if (!$product_id) {
    // If invalid product ID, go back
    $referer = $_SERVER['HTTP_REFERER'] ?? '../index.php';
    redirect($referer);
}

$controller = new CartController();

if ($controller->isInCart($product_id, $ip)) {
    // Already in cart
    echo "<script>alert('This item is already in your cart. Go to cart to update quantity.'); window.location.href = '" . ($_SERVER['HTTP_REFERER'] ?? '../views/cart.php') . "';</script>";
    exit();
} else {
    // Add to cart
    $controller->addToCart($product_id, $qty, $ip, $customer_id);
    $referer = $_SERVER['HTTP_REFERER'] ?? '../views/cart.php';
    redirect($referer);
}
?>
