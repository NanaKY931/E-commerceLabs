<?php
require_once __DIR__ . '/../../core/core.php';
require_admin();

require_once __DIR__ . '/../../controllers/ProductController.php';

$controller = new ProductController();

$editMode = false;
$editCategory = null;

if (isset($_GET['edit_id'])) {
    $editMode = true;
    $editId = filter_var($_GET['edit_id'], FILTER_VALIDATE_INT);
    if ($editId) {
        $editCategory = $controller->getCategoryById($editId);
    }
}

$categories = $controller->getAllCategories();
?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<main class="admin-page">
    <div class="admin-container">
        <h1><?= $editMode && $editCategory ? 'Edit Category' : 'Add Category' ?></h1>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="<?= $editMode && $editCategory ? '../../actions/update_category_action.php' : '../../actions/add_category_action.php' ?>" method="POST" class="admin-form">
            <?php if ($editMode && $editCategory): ?>
                <input type="hidden" name="cat_id" value="<?= htmlspecialchars($editCategory['cat_id']) ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="cat_name">Category Name *</label>
                <input type="text" id="cat_name" name="cat_name" value="<?= $editMode && $editCategory ? htmlspecialchars($editCategory['cat_name']) : '' ?>" required>
            </div>

            <button type="submit" class="btn-submit">
                <?= $editMode && $editCategory ? 'Update Category' : 'Add Category' ?>
            </button>
            <?php if ($editMode): ?>
                <a href="category.php" class="btn-cancel">Cancel Edit</a>
            <?php endif; ?>
        </form>

        <h2>All Categories</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr><td colspan="3">No categories found.</td></tr>
                <?php else: ?>
                    <?php foreach ($categories as $c): ?>
                        <tr>
                            <td><?= htmlspecialchars($c['cat_id']) ?></td>
                            <td><?= htmlspecialchars($c['cat_name']) ?></td>
                            <td>
                                <a href="category.php?edit_id=<?= htmlspecialchars($c['cat_id']) ?>" class="btn-edit">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include __DIR__ . '/../layout/footer.php'; ?>
