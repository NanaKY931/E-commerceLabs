<?php
require_once __DIR__ . '/../core/core.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit('Method not allowed.');
}

require_once __DIR__ . '/../controllers/ProductController.php';

$id = filter_var($_POST['brand_id'] ?? '', FILTER_VALIDATE_INT);
$name = trim(strip_tags($_POST['brand_name'] ?? ''));

if (!$id || empty($name)) {
    $_SESSION['error'] = 'Invalid brand details.';
    redirect('../views/admin/brand.php');
}

$controller = new ProductController();
$success = $controller->updateBrand($id, $name);

if ($success) {
    $_SESSION['success'] = 'Brand updated successfully.';
} else {
    $_SESSION['error'] = 'Failed to update brand.';
}
redirect('../views/admin/brand.php');
?>
