<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/cart.php');
}

$controller = new CartController();
$ip = get_ip();

if (isset($_POST['remove']) && is_array($_POST['remove'])) {
    foreach ($_POST['remove'] as $p_id) {
        $id = filter_var($p_id, FILTER_VALIDATE_INT);
        if ($id) {
            $controller->removeFromCart($id, $ip);
        }
    }
}

redirect('../views/cart.php');
?>
