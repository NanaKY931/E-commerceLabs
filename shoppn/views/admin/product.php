<?php
require_once __DIR__ . '/../../core/core.php';
require_admin();

require_once __DIR__ . '/../../controllers/ProductController.php';
$controller = new ProductController();

// --- Edit mode detection ---
$editMode    = false;
$editProduct = null;

if (isset($_GET['edit_id'])) {
    $editId = filter_var($_GET['edit_id'], FILTER_VALIDATE_INT);
    if ($editId) {
        $editProduct = $controller->getProductById($editId);
        $editMode    = ($editProduct !== false);
    }
}

// --- Fetch dropdown data ---
$categories = $controller->getAllCategories();
$brands     = $controller->getAllBrands();
$products   = $controller->getAllProducts();
?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<main class="admin-page">
    <div class="admin-container">
        <h1><?= $editMode ? 'Edit Product' : 'Add Product' ?></h1>

        <!-- Flash messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Product form — enctype required for file upload -->
        <form
            action="<?= $editMode ? '../../actions/update_product_action.php' : '../../actions/add_product_action.php' ?>"
            method="POST"
            enctype="multipart/form-data"
            class="admin-form admin-form-wide"
            id="product-form"
        >
            <?php if ($editMode): ?>
                <input type="hidden" name="product_id"    value="<?= (int)$editProduct['product_id'] ?>">
                <input type="hidden" name="existing_image" value="<?= htmlspecialchars($editProduct['product_image'] ?? 'default.png') ?>">
            <?php endif; ?>

            <div class="form-grid">
                <!-- Product Title (full width) -->
                <div class="form-group full-width">
                    <label for="product_title">Product Title *</label>
                    <input
                        type="text"
                        id="product_title"
                        name="product_title"
                        value="<?= $editMode ? htmlspecialchars($editProduct['product_title']) : '' ?>"
                        placeholder="e.g. iPhone 15 Pro Max"
                        required
                    >
                </div>

                <!-- Price -->
                <div class="form-group">
                    <label for="product_price">Price (GHS) *</label>
                    <input
                        type="number"
                        id="product_price"
                        name="product_price"
                        value="<?= $editMode ? htmlspecialchars($editProduct['product_price']) : '' ?>"
                        placeholder="e.g. 4999.99"
                        step="0.01"
                        min="0.01"
                        required
                    >
                </div>

                <!-- Keywords -->
                <div class="form-group">
                    <label for="product_keywords">Keywords</label>
                    <input
                        type="text"
                        id="product_keywords"
                        name="product_keywords"
                        value="<?= $editMode ? htmlspecialchars($editProduct['product_keywords'] ?? '') : '' ?>"
                        placeholder="e.g. phone, smartphone, apple"
                    >
                </div>

                <!-- Category dropdown -->
                <div class="form-group">
                    <label for="product_cat">Category *</label>
                    <select id="product_cat" name="product_cat" required>
                        <option value="">— Select category —</option>
                        <?php foreach ($categories as $cat): ?>
                            <option
                                value="<?= (int)$cat['cat_id'] ?>"
                                <?= ($editMode && $editProduct['product_cat'] == $cat['cat_id']) ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($cat['cat_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Brand dropdown -->
                <div class="form-group">
                    <label for="product_brand">Brand *</label>
                    <select id="product_brand" name="product_brand" required>
                        <option value="">— Select brand —</option>
                        <?php foreach ($brands as $brand): ?>
                            <option
                                value="<?= (int)$brand['brand_id'] ?>"
                                <?= ($editMode && $editProduct['product_brand'] == $brand['brand_id']) ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($brand['brand_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Description (full width) -->
                <div class="form-group full-width">
                    <label for="product_desc">Description</label>
                    <textarea
                        id="product_desc"
                        name="product_desc"
                        placeholder="Describe the product..."
                    ><?= $editMode ? htmlspecialchars($editProduct['product_desc'] ?? '') : '' ?></textarea>
                </div>

                <!-- Image upload (full width) -->
                <div class="form-group full-width">
                    <label>Product Image <?= $editMode ? '(leave blank to keep current)' : '' ?></label>

                    <!-- JS-side validation for instant UX feedback -->
                    <div class="file-upload-zone" id="upload-zone">
                        <input
                            type="file"
                            id="product_image"
                            name="product_image"
                            accept="image/jpeg,image/png,image/gif,image/webp"
                        >
                        <label for="product_image">📂 Click to choose an image</label>
                        <p class="upload-hint">JPEG, PNG, GIF or WebP · max 2 MB</p>
                    </div>

                    <!-- Preview: current image (edit) or newly selected file -->
                    <div class="img-preview" id="img-preview">
                        <?php if ($editMode && !empty($editProduct['product_image']) && $editProduct['product_image'] !== 'default.png'): ?>
                            <img
                                src="<?= ROOT_URL ?>images/products/<?= htmlspecialchars($editProduct['product_image']) ?>"
                                alt="Current product image"
                                id="preview-img"
                            >
                        <?php else: ?>
                            <img src="" alt="" id="preview-img" style="display:none;">
                        <?php endif; ?>
                    </div>
                </div>
            </div><!-- /.form-grid -->

            <button type="submit" class="btn-submit">
                <?= $editMode ? 'Update Product' : 'Add Product' ?>
            </button>
            <?php if ($editMode): ?>
                <a href="product.php" class="btn-cancel">Cancel Edit</a>
            <?php endif; ?>
        </form>

        <!-- Product list table -->
        <h2>All Products</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Price (GHS)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr><td colspan="7" style="text-align:center;color:var(--text-muted);">No products yet. Add one above!</td></tr>
                <?php else: ?>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td><?= (int)$p['product_id'] ?></td>
                            <td>
                                <?php if (!empty($p['product_image']) && $p['product_image'] !== 'default.png'): ?>
                                    <img
                                        src="<?= ROOT_URL ?>images/products/<?= htmlspecialchars($p['product_image']) ?>"
                                        alt="<?= htmlspecialchars($p['product_title']) ?>"
                                        style="width:48px;height:48px;object-fit:cover;border-radius:6px;border:1px solid var(--border);"
                                    >
                                <?php else: ?>
                                    <span style="color:var(--text-muted);font-size:0.8rem;">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($p['product_title']) ?></td>
                            <td><?= htmlspecialchars($p['cat_name']) ?></td>
                            <td><?= htmlspecialchars($p['brand_name']) ?></td>
                            <td><?= number_format((float)$p['product_price'], 2) ?></td>
                            <td>
                                <a href="product.php?edit_id=<?= (int)$p['product_id'] ?>" class="btn-edit">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<!-- JavaScript: client-side image validation + live preview (UX layer only) -->
<script>
(function () {
    const input    = document.getElementById('product_image');
    const preview  = document.getElementById('preview-img');
    const zone     = document.getElementById('upload-zone');
    const maxBytes = 2 * 1024 * 1024; // 2 MB
    const allowed  = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    input.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        // Client-side type check (UX feedback only — PHP also validates)
        if (!allowed.includes(file.type)) {
            alert('Invalid file type. Please choose a JPEG, PNG, GIF or WebP image.');
            this.value = '';
            return;
        }

        // Client-side size check (UX feedback only — PHP also validates)
        if (file.size > maxBytes) {
            alert('Image is too large. Please choose a file under 2 MB.');
            this.value = '';
            return;
        }

        // Show live preview
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src   = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    });

    // Drag-and-drop highlight
    zone.addEventListener('dragover',  () => zone.classList.add('drag-over'));
    zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
    zone.addEventListener('drop',      () => zone.classList.remove('drag-over'));
})();
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
