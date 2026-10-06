<?php
require_once __DIR__ . '/../core/core.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit('Method not allowed.');
}

require_once __DIR__ . '/../controllers/ProductController.php';

$name = trim(strip_tags($_POST['brand_name'] ?? ''));

if (empty($name)) {
    $_SESSION['error'] = 'Brand name is required.';
    redirect('../views/admin/brand.php');
}

$controller = new ProductController();
$success = $controller->addBrand($name);

if ($success) {
    $_SESSION['success'] = 'Brand added.';
} else {
    $_SESSION['error'] = 'Failed to add brand.';
}
redirect('../views/admin/brand.php');
?>
