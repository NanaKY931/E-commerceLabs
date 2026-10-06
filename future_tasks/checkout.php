<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_login();

$controller = new CartController();
$ip = get_ip();
$items = $controller->getCartItems($ip);
$total = $controller->getCartTotal($ip);

if (empty($items)) {
    redirect('cart.php');
}
?>
<?php include __DIR__ . '/layout/header.php'; ?>

<main class="payment-page">
    <div class="checkout-container" style="padding: 20px; max-width: 800px; margin: 0 auto;">
        <h1>Checkout / Payment</h1>
        
        <h2>Order Summary</h2>
        <table class="cart-table" style="width: 100%; border-collapse: collapse; margin-top: 20px; margin-bottom: 30px;">
            <thead>
                <tr style="border-bottom: 2px solid #ccc; text-align: left;">
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td><?= htmlspecialchars($item['product_title']) ?></td>
                        <td><?= htmlspecialchars($item['qty']) ?></td>
                        <td>GHS <?= number_format($item['product_price'], 2) ?></td>
                        <td>GHS <?= number_format($item['qty'] * $item['product_price'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-summary" style="text-align: right; margin-bottom: 40px;">
            <h3 style="font-size: 1.5em;">Total to Pay: GHS <?= number_format($total, 2) ?></h3>
        </div>

        <div style="text-align: center;">
            <button type="button" onclick="payWithPaystack()" class="btn btn-checkout" style="padding: 15px 30px; font-size: 1.2em;">Pay Now with Paystack</button>
        </div>
    </div>
</main>

<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
function payWithPaystack() {
    var handler = PaystackPop.setup({
        key: 'pk_test_YOUR_PUBLIC_KEY', // Replace with your public key
        email: '<?= htmlspecialchars($_SESSION["customer_email"]) ?>',
        amount: <?= $total * 100 ?>,
        currency: 'GHS',
        onSuccess: function(response) {
            window.location = '../actions/process_payment_action.php?ref=' + response.reference;
        },
        onCancel: function() {
            alert('Payment cancelled.');
        }
    });
    handler.openIframe();
}
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>
