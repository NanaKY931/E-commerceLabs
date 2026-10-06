<?php
require_once __DIR__ . '/../../core/core.php';
require_admin();

require_once __DIR__ . '/../../controllers/ProductController.php';

$controller = new ProductController();

$editMode = false;
$editProduct = null;

if (isset($_GET['edit_id'])) {
    $editMode = true;
    $editId = filter_var($_GET['edit_id'], FILTER_VALIDATE_INT);
    if ($editId) {
        $editProduct = $controller->getProductById($editId);
    }
}

$categories = $controller->getAllCategories();
$brands = $controller->getAllBrands();
$products = $controller->getAllProducts();
?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<main class="admin-page">
    <div class="admin-container">
        <h1><?= $editMode && $editProduct ? 'Edit Product' : 'Add Product' ?></h1>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="<?= $editMode && $editProduct ? '../../actions/update_product_action.php' : '../../actions/add_product_action.php' ?>" method="POST" enctype="multipart/form-data" class="admin-form" id="productForm">
            <?php if ($editMode && $editProduct): ?>
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($editProduct['product_id']) ?>">
                <input type="hidden" name="existing_image" value="<?= htmlspecialchars($editProduct['product_image']) ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="product_title">Product Title *</label>
                <input type="text" id="product_title" name="product_title" value="<?= $editMode && $editProduct ? htmlspecialchars($editProduct['product_title']) : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="product_cat">Category *</label>
                <select id="product_cat" name="product_cat" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= htmlspecialchars($c['cat_id']) ?>" <?= ($editMode && $editProduct && $editProduct['product_cat'] == $c['cat_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['cat_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="product_brand">Brand *</label>
                <select id="product_brand" name="product_brand" required>
                    <option value="">Select Brand</option>
                    <?php foreach ($brands as $b): ?>
                        <option value="<?= htmlspecialchars($b['brand_id']) ?>" <?= ($editMode && $editProduct && $editProduct['product_brand'] == $b['brand_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($b['brand_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="product_price">Price (GHS) *</label>
                <input type="number" step="0.01" id="product_price" name="product_price" value="<?= $editMode && $editProduct ? htmlspecialchars($editProduct['product_price']) : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="product_desc">Description *</label>
                <textarea id="product_desc" name="product_desc" rows="4" required><?= $editMode && $editProduct ? htmlspecialchars($editProduct['product_desc']) : '' ?></textarea>
            </div>

            <div class="form-group">
                <label for="product_keywords">Keywords</label>
                <input type="text" id="product_keywords" name="product_keywords" value="<?= $editMode && $editProduct ? htmlspecialchars($editProduct['product_keywords']) : '' ?>">
            </div>

            <div class="form-group">
                <label for="product_image">Product Image <?= $editMode && $editProduct ? '(Leave blank to keep existing)' : '*' ?></label>
                <input type="file" id="product_image" name="product_image" accept="image/*" <?= $editMode && $editProduct ? '' : 'required' ?>>
                <span class="field-error" id="err-product_image"></span>
                <?php if ($editMode && $editProduct && !empty($editProduct['product_image'])): ?>
                    <div class="image-preview" style="margin-top:10px;">
                        <p>Current Image:</p>
                        <img src="../../images/products/<?= htmlspecialchars($editProduct['product_image']) ?>" alt="Current Image" style="max-width: 150px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn-submit">
                <?= $editMode && $editProduct ? 'Update Product' : 'Add Product' ?>
            </button>
            <?php if ($editMode): ?>
                <a href="product.php" class="btn-cancel">Cancel Edit</a>
            <?php endif; ?>
        </form>

        <h2 style="margin-top: 40px;">All Products</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr><td colspan="6">No products found.</td></tr>
                <?php else: ?>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td>
                                <?php if (!empty($p['product_image'])): ?>
                                    <img src="../../images/products/<?= htmlspecialchars($p['product_image']) ?>" alt="Product Image" style="width: 50px; height: 50px; object-fit: cover;">
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($p['product_title']) ?></td>
                            <td><?= htmlspecialchars($p['cat_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($p['brand_name'] ?? 'N/A') ?></td>
                            <td>GHS <?= number_format($p['product_price'], 2) ?></td>
                            <td>
                                <a href="product.php?edit_id=<?= htmlspecialchars($p['product_id']) ?>" class="btn-edit">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
// Client-side file validation
document.getElementById('productForm').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('product_image');
    const errSpan = document.getElementById('err-product_image');
    errSpan.textContent = '';
    
    if (fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        if (!allowedTypes.includes(file.type)) {
            e.preventDefault();
            errSpan.textContent = 'Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.';
            return;
        }
        
        // 5MB limit
        if (file.size > 5 * 1024 * 1024) {
            e.preventDefault();
            errSpan.textContent = 'File is too large. Maximum size is 5MB.';
            return;
        }
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
