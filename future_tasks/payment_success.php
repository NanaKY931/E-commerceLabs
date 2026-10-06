<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

require_login();

$order_id = filter_var($_GET['order_id'] ?? '', FILTER_VALIDATE_INT);
if (!$order_id) {
    redirect('home.php');
}

$controller = new CartController();
$order = $controller->getOrderById($order_id);

// Basic security check: ensure order belongs to current user
if (!$order || $order['details']['customer_id'] !== $_SESSION['customer_id']) {
    redirect('home.php');
}

$details = $order['details'];
$items = $order['items'];
$total = 0;
?>
<?php include __DIR__ . '/layout/header.php'; ?>

<main class="success-page">
    <div class="success-container" style="padding: 20px; max-width: 800px; margin: 0 auto;">
        <h1 style="color: green;">Payment Successful!</h1>
        <p>Thank you for your order. Your transaction has been completed.</p>
        
        <div class="invoice" style="border: 1px solid #ccc; padding: 20px; border-radius: 8px; margin-top: 30px;">
            <h2>Invoice: #<?= htmlspecialchars($details['invoice_no']) ?></h2>
            <p><strong>Order Date:</strong> <?= htmlspecialchars($details['order_date']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($details['order_status']) ?></p>

            <table class="cart-table" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr style="border-bottom: 2px solid #ccc; text-align: left;">
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): 
                        $sub = $item['qty'] * $item['product_price'];
                        $total += $sub;
                    ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td><?= htmlspecialchars($item['product_title']) ?></td>
                            <td><?= htmlspecialchars($item['qty']) ?></td>
                            <td>GHS <?= number_format($item['product_price'], 2) ?></td>
                            <td>GHS <?= number_format($sub, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <h3 style="text-align: right; margin-top: 20px;">Total Paid: GHS <?= number_format($total, 2) ?></h3>
        </div>

        <div style="margin-top: 30px; text-align: center;">
            <a href="home.php" class="btn">Return to Shop</a>
        </div>
    </div>
</main>

<?php include __DIR__ . '/layout/footer.php'; ?>
