<?php
require_once __DIR__ . '/../../controllers/ProductController.php';
$sidebarController = new ProductController();
$sidebarCats = $sidebarController->getAllCategories();
$sidebarBrands = $sidebarController->getAllBrands();
?>
<aside class="sidebar">
    <div class="sidebar-section">
        <h3>Categories</h3>
        <ul>
            <?php foreach ($sidebarCats as $cat): ?>
                <li><a href="/shoppn/views/home.php?cat=<?= htmlspecialchars($cat['cat_id']) ?>"><?= htmlspecialchars($cat['cat_name']) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="sidebar-section">
        <h3>Brands</h3>
        <ul>
            <?php foreach ($sidebarBrands as $brand): ?>
                <li><a href="/shoppn/views/home.php?brand=<?= htmlspecialchars($brand['brand_id']) ?>"><?= htmlspecialchars($brand['brand_name']) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</aside>
