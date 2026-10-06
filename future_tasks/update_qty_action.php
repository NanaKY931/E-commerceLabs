<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/cart.php');
}

$controller = new CartController();
$ip = get_ip();

if (isset($_POST['update']) && is_array($_POST['update']) && isset($_POST['qty']) && is_array($_POST['qty'])) {
    for ($i = 0; $i < count($_POST['update']); $i++) {
        $id = filter_var($_POST['update'][$i], FILTER_VALIDATE_INT);
        $qty = filter_var($_POST['qty'][$i], FILTER_VALIDATE_INT);
        
        if ($id && $qty !== false && $qty > 0) {
            $controller->updateQty($id, $qty, $ip);
        }
    }
}

redirect('../views/cart.php');
?>
