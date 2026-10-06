<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$query = trim(strip_tags($_GET['user_query'] ?? ''));

$controller = new ProductController();
$products = [];

if (!empty($query)) {
    $products = $controller->searchProducts($query);
}
?>
<?php include __DIR__ . '/layout/header.php'; ?>

<div class="main-layout" style="display: flex;">
    <?php include __DIR__ . '/layout/sidebar.php'; ?>

    <main class="content-area" style="flex: 1; padding: 20px;">
        <h1>Search Results for: "<?= htmlspecialchars($query) ?>"</h1>

        <div class="product-grid" style="display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px;">
            <?php if (empty($products)): ?>
                <p>No products found matching your search.</p>
            <?php else: ?>
                <?php foreach ($products as $p): ?>
                    <div class="product-card" style="border: 1px solid #ccc; padding: 15px; border-radius: 8px; width: 200px; text-align: center;">
                        <?php if (!empty($p['product_image'])): ?>
                            <img src="../images/products/<?= htmlspecialchars($p['product_image']) ?>" alt="Product Image" style="width: 100%; height: 150px; object-fit: cover;">
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($p['product_title']) ?></h3>
                        <p class="price">GHS <?= number_format($p['product_price'], 2) ?></p>
                        
                        <div class="card-actions" style="margin-top: 10px;">
                            <a href="single_product.php?pro_id=<?= htmlspecialchars($p['product_id']) ?>" class="btn btn-details" style="display: block; margin-bottom: 5px;">Details</a>
                            <a href="../actions/add_to_cart_action.php?add_cart=<?= htmlspecialchars($p['product_id']) ?>" class="btn btn-cart" style="display: block;">Add to Cart</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
