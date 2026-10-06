<?php
require_once __DIR__ . '/../core/core.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit('Method not allowed.');
}

require_once __DIR__ . '/../controllers/ProductController.php';

$id       = filter_var($_POST['product_id'] ?? '', FILTER_VALIDATE_INT);
$cat      = filter_var($_POST['product_cat'] ?? '', FILTER_VALIDATE_INT);
$brand    = filter_var($_POST['product_brand'] ?? '', FILTER_VALIDATE_INT);
$title    = trim(strip_tags($_POST['product_title'] ?? ''));
$price    = filter_var($_POST['product_price'] ?? '', FILTER_VALIDATE_FLOAT);
$desc     = trim(strip_tags($_POST['product_desc'] ?? ''));
$keywords = trim(strip_tags($_POST['product_keywords'] ?? ''));
$existing_image = $_POST['existing_image'] ?? '';

if (!$id || !$cat || !$brand || empty($title) || $price === false || empty($desc)) {
    $_SESSION['error'] = 'Please fill out all required fields with valid data.';
    redirect('../views/admin/product.php?edit_id=' . $id);
}

$filename = $existing_image;

// Check if a new image was uploaded
if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($_FILES['product_image']['type'], $allowed)) {
        $_SESSION['error'] = 'Invalid image type. Only JPG, PNG, GIF, and WEBP are allowed.';
        redirect('../views/admin/product.php?edit_id=' . $id);
    }

    $new_filename = uniqid() . '_' . basename($_FILES['product_image']['name']);
    $upload_path = __DIR__ . '/../images/products/' . $new_filename;

    if (!is_dir(__DIR__ . '/../images/products/')) {
        mkdir(__DIR__ . '/../images/products/', 0777, true);
    }

    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $upload_path)) {
        $filename = $new_filename;
    } else {
        $_SESSION['error'] = 'Failed to upload new image.';
        redirect('../views/admin/product.php?edit_id=' . $id);
    }
}

$controller = new ProductController();
$success = $controller->updateProduct($id, $cat, $brand, $title, $price, $desc, $filename, $keywords);

if ($success) {
    $_SESSION['success'] = 'Product updated successfully.';
    redirect('../views/admin/product.php');
} else {
    $_SESSION['error'] = 'Failed to update product.';
    redirect('../views/admin/product.php?edit_id=' . $id);
}
?>
