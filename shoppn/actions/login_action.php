<?php
// POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit('Method not allowed.');
}

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

// --- Sanitise inputs ---
$email = trim(strip_tags($_POST['email'] ?? ''));
$pass  = $_POST['pass'] ?? '';

// Validate email format server-side
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Please enter a valid email address.';
    redirect('../views/login.php');
}

if (empty($email) || empty($pass)) {
    $_SESSION['error'] = 'Email and password are required.';
    redirect('../views/login.php');
}

// --- Attempt login through controller ---
$controller = new CustomerController();
$result     = $controller->login($email, $pass);

if ($result['success']) {
    $c = $result['customer'];
    $_SESSION['customer_id']    = $c['customer_id'];
    $_SESSION['customer_name']  = $c['customer_name'];
    $_SESSION['customer_email'] = $c['customer_email'];
    $_SESSION['user_role']      = $c['user_role'];
    redirect('../index.php');
} else {
    $_SESSION['error'] = $result['error'];
    redirect('../views/login.php');
}
?>
