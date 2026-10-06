<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

require_login(); // Payment requires login based on the task description

$reference = $_GET['ref'] ?? '';
if (empty($reference)) {
    redirect('../views/payment_failed.php');
}

// Verify with Paystack API
$url = 'https://api.paystack.co/transaction/verify/' . $reference;
// Note: using a placeholder key. In a real app this comes from env/config.
$headers = ['Authorization: Bearer sk_test_YOUR_SECRET_KEY'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($err) {
    $_SESSION['error'] = 'Payment verification failed: ' . $err;
    redirect('../views/payment_failed.php');
}

$tranx = json_decode($response);

if (!$tranx || !$tranx->status || $tranx->data->status !== 'success') {
    $_SESSION['error'] = 'Payment verification failed. Transaction was not successful.';
    redirect('../views/payment_failed.php');
}

$amount = $tranx->data->amount / 100; // Convert kobo/pesewas back to major currency
$customer_id = $_SESSION['customer_id'];
$ip = get_ip();

$controller = new CartController();
$order_id = $controller->processPayment($customer_id, $ip, $reference, $amount);

if ($order_id) {
    redirect('../views/payment_success.php?order_id=' . $order_id);
} else {
    $_SESSION['error'] = 'Payment was successful but we failed to record your order. Please contact support.';
    redirect('../views/payment_failed.php');
}
?>
