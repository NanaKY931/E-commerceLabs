<?php
require_once __DIR__ . '/../../core/core.php';
require_admin();

require_once __DIR__ . '/../../controllers/ProductController.php';

$controller = new ProductController();

$editMode = false;
$editBrand = null;

if (isset($_GET['edit_id'])) {
    $editMode = true;
    $editId = filter_var($_GET['edit_id'], FILTER_VALIDATE_INT);
    if ($editId) {
        $editBrand = $controller->getBrandById($editId);
    }
}

$brands = $controller->getAllBrands();
?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<main class="admin-page">
    <div class="admin-container">
        <h1><?= $editMode && $editBrand ? 'Edit Brand' : 'Add Brand' ?></h1>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="<?= $editMode && $editBrand ? '../../actions/update_brand_action.php' : '../../actions/add_brand_action.php' ?>" method="POST" class="admin-form">
            <?php if ($editMode && $editBrand): ?>
                <input type="hidden" name="brand_id" value="<?= htmlspecialchars($editBrand['brand_id']) ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="brand_name">Brand Name *</label>
                <input type="text" id="brand_name" name="brand_name" value="<?= $editMode && $editBrand ? htmlspecialchars($editBrand['brand_name']) : '' ?>" required>
            </div>

            <button type="submit" class="btn-submit">
                <?= $editMode && $editBrand ? 'Update Brand' : 'Add Brand' ?>
            </button>
            <?php if ($editMode): ?>
                <a href="brand.php" class="btn-cancel">Cancel Edit</a>
            <?php endif; ?>
        </form>

        <h2>All Brands</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Brand Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($brands)): ?>
                    <tr><td colspan="3">No brands found.</td></tr>
                <?php else: ?>
                    <?php foreach ($brands as $b): ?>
                        <tr>
                            <td><?= htmlspecialchars($b['brand_id']) ?></td>
                            <td><?= htmlspecialchars($b['brand_name']) ?></td>
                            <td>
                                <a href="brand.php?edit_id=<?= htmlspecialchars($b['brand_id']) ?>" class="btn-edit">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include __DIR__ . '/../layout/footer.php'; ?>
