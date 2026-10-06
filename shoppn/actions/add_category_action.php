<?php
require_once __DIR__ . '/../core/core.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit('Method not allowed.');
}

require_once __DIR__ . '/../controllers/ProductController.php';

$name = trim(strip_tags($_POST['cat_name'] ?? ''));

if (empty($name)) {
    $_SESSION['error'] = 'Category name is required.';
    redirect('../views/admin/category.php');
}

$controller = new ProductController();
$success = $controller->addCategory($name);

if ($success) {
    $_SESSION['success'] = 'Category added.';
} else {
    $_SESSION['error'] = 'Failed to add category.';
}
redirect('../views/admin/category.php');
?>
