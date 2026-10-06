<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$pro_id = filter_var($_GET['pro_id'] ?? '', FILTER_VALIDATE_INT);

if (!$pro_id) {
    redirect('home.php');
}

$controller = new ProductController();
$product = $controller->getProductById($pro_id);

if (!$product) {
    redirect('home.php');
}
?>
<?php include __DIR__ . '/layout/header.php'; ?>

<div class="main-layout" style="display: flex;">
    <?php include __DIR__ . '/layout/sidebar.php'; ?>

    <main class="content-area" style="flex: 1; padding: 20px;">
        <div class="product-details" style="display: flex; gap: 40px;">
            <div class="product-image-container" style="flex: 1;">
                <?php if (!empty($product['product_image'])): ?>
                    <img src="../images/products/<?= htmlspecialchars($product['product_image']) ?>" alt="<?= htmlspecialchars($product['product_title']) ?>" style="width: 100%; max-width: 400px; border-radius: 8px;">
                <?php else: ?>
                    <div class="no-image" style="width: 100%; max-width: 400px; height: 300px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 8px;">No Image</div>
                <?php endif; ?>
            </div>

            <div class="product-info" style="flex: 2;">
                <h1><?= htmlspecialchars($product['product_title']) ?></h1>
                
                <p class="meta" style="color: #666; font-size: 0.9em; margin-bottom: 20px;">
                    <strong>Category:</strong> <?= htmlspecialchars($product['cat_name'] ?? 'N/A') ?> | 
                    <strong>Brand:</strong> <?= htmlspecialchars($product['brand_name'] ?? 'N/A') ?>
                </p>

                <p class="price" style="font-size: 1.5em; font-weight: bold; color: #2c3e50; margin-bottom: 20px;">
                    GHS <?= number_format($product['product_price'], 2) ?>
                </p>

                <div class="description" style="line-height: 1.6; margin-bottom: 30px;">
                    <h3>Description</h3>
                    <p><?= nl2br(htmlspecialchars($product['product_desc'])) ?></p>
                </div>

                <?php if (!empty($product['product_keywords'])): ?>
                    <div class="keywords" style="margin-bottom: 30px;">
                        <strong>Keywords:</strong> <?= htmlspecialchars($product['product_keywords']) ?>
                    </div>
                <?php endif; ?>

                <a href="../actions/add_to_cart_action.php?add_cart=<?= htmlspecialchars($product['product_id']) ?>" class="btn btn-cart btn-large" style="padding: 10px 20px; font-size: 1.1em;">Add to Cart</a>
            </div>
        </div>
    </main>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
