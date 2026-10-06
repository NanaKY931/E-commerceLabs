<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

$controller = new CartController();
$items = $controller->getCartItems(get_ip());
$total = $controller->getCartTotal(get_ip());
?>
<?php include __DIR__ . '/layout/header.php'; ?>

<main class="cart-page">
    <div class="cart-container" style="padding: 20px;">
        <h1>Your Shopping Cart</h1>

        <?php if (empty($items)): ?>
            <p>Your cart is currently empty.</p>
            <a href="home.php" class="btn">Continue Shopping</a>
        <?php else: ?>
            <form action="../actions/update_qty_action.php" method="POST" id="updateCartForm">
                <table class="cart-table" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                    <thead>
                        <tr style="border-bottom: 2px solid #ccc; text-align: left;">
                            <th>Product</th>
                            <th>Image</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td><?= htmlspecialchars($item['product_title']) ?></td>
                                <td>
                                    <?php if (!empty($item['product_image'])): ?>
                                        <img src="../images/products/<?= htmlspecialchars($item['product_image']) ?>" alt="Product Image" style="width: 50px; height: 50px; object-fit: cover;">
                                    <?php endif; ?>
                                </td>
                                <td>GHS <?= number_format($item['product_price'], 2) ?></td>
                                <td>
                                    <input type="hidden" name="update[]" value="<?= htmlspecialchars($item['p_id']) ?>">
                                    <input type="number" name="qty[]" value="<?= htmlspecialchars($item['qty']) ?>" min="1" style="width: 60px;">
                                </td>
                                <td>GHS <?= number_format($item['qty'] * $item['product_price'], 2) ?></td>
                                <td>
                                    <!-- Remove checkbox for Task 12 -->
                                    <input type="checkbox" name="remove[]" value="<?= htmlspecialchars($item['p_id']) ?>" form="removeCartForm">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div style="margin-top: 20px; text-align: right;">
                    <button type="submit" class="btn btn-update">Update Quantity</button>
                </div>
            </form>
            
            <form action="../actions/remove_from_cart_action.php" method="POST" id="removeCartForm" style="display:inline;">
                <div style="margin-top: 10px; text-align: right;">
                    <button type="submit" class="btn btn-remove">Remove Selected Items</button>
                </div>
            </form>

            <div class="cart-summary" style="margin-top: 40px; text-align: right;">
                <h3>Total: GHS <?= number_format($total, 2) ?></h3>
                <div class="cart-actions" style="margin-top: 20px;">
                    <a href="home.php" class="btn btn-continue">Continue Shopping</a>
                    <a href="checkout.php" class="btn btn-checkout">Checkout</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/layout/footer.php'; ?>
