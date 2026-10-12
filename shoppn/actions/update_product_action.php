<?php
require_once __DIR__ . '/../core/core.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(ROOT_URL . 'views/admin/product.php');
}

require_once __DIR__ . '/../controllers/ProductController.php';
$controller = new ProductController();

// --- Sanitise & validate inputs ---
$id       = filter_input(INPUT_POST, 'product_id',       FILTER_VALIDATE_INT);
$cat      = filter_input(INPUT_POST, 'product_cat',      FILTER_VALIDATE_INT);
$brand    = filter_input(INPUT_POST, 'product_brand',    FILTER_VALIDATE_INT);
$title    = trim(htmlspecialchars($_POST['product_title']    ?? ''));
$price    = filter_input(INPUT_POST, 'product_price',    FILTER_VALIDATE_FLOAT);
$desc     = trim(htmlspecialchars($_POST['product_desc']     ?? ''));
$keywords = trim(htmlspecialchars($_POST['product_keywords'] ?? ''));

// The existing image filename is stored in a hidden field as fallback
$existingImage = trim($_POST['existing_image'] ?? 'default.png');

if (!$id || !$cat || !$brand || $title === '' || $price === false || $price <= 0) {
    $_SESSION['error'] = 'Please fill in all required fields with valid values.';
    redirect(ROOT_URL . 'views/admin/product.php?edit_id=' . (int)$id);
}

// --- Handle image upload (only if a new file was provided) ---
$filename = $existingImage; // default: keep the old image

if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 2 * 1024 * 1024; // 2 MB

    $finfo    = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $_FILES['product_image']['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowed)) {
        $_SESSION['error'] = 'Invalid image type. Only JPEG, PNG, GIF, and WebP are allowed.';
        redirect(ROOT_URL . 'views/admin/product.php?edit_id=' . $id);
    }

    if ($_FILES['product_image']['size'] > $maxSize) {
        $_SESSION['error'] = 'Image must be under 2 MB.';
        redirect(ROOT_URL . 'views/admin/product.php?edit_id=' . $id);
    }

    $filename  = uniqid() . '_' . basename($_FILES['product_image']['name']);
    $filename  = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
    $uploadDir = __DIR__ . '/../images/products/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadDir . $filename)) {
        $_SESSION['error'] = 'Failed to save the uploaded image. Please try again.';
        redirect(ROOT_URL . 'views/admin/product.php?edit_id=' . $id);
    }
}

// --- Persist update ---
$result = $controller->updateProduct($id, $cat, $brand, $title, $price, $desc, $filename, $keywords);

if ($result) {
    $_SESSION['success'] = 'Product "' . $title . '" updated successfully!';
} else {
    $_SESSION['error'] = 'Database error: could not update the product. Please try again.';
}

redirect(ROOT_URL . 'views/admin/product.php');
