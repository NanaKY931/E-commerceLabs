<?php
// POST only — reject any other method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit('Method not allowed.');
}

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

// --- Sanitise & validate inputs server-side ---
$name    = trim(strip_tags($_POST['name']    ?? ''));
$email   = trim(strip_tags($_POST['email']   ?? ''));
$pass    = $_POST['pass']                         ?? '';
$country = trim(strip_tags($_POST['country'] ?? ''));
$city    = trim(strip_tags($_POST['city']    ?? ''));
$contact = trim(strip_tags($_POST['contact'] ?? ''));

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Please enter a valid email address.';
    redirect('../views/register.php');
}

// Validate required fields are not empty
if (empty($name) || empty($email) || empty($pass) || empty($country) || empty($city) || empty($contact)) {
    $_SESSION['error'] = 'All required fields must be filled in.';
    redirect('../views/register.php');
}

// Enforce field length limits (matches DB schema VARCHAR 100)
if (strlen($name) > 100 || strlen($email) > 50 || strlen($city) > 100 || strlen($contact) > 100) {
    $_SESSION['error'] = 'One or more fields exceed the maximum allowed length.';
    redirect('../views/register.php');
}

// --- Run registration through controller ---
$controller = new CustomerController();
$result     = $controller->register($name, $email, $pass, $country, $city, $contact);

if ($result['success']) {
    // Fetch the newly created customer row via controller to set session
    $customer = $controller->getCustomerByEmail($email);
    $_SESSION['customer_id']    = $customer['customer_id'];
    $_SESSION['customer_name']  = $customer['customer_name'];
    $_SESSION['customer_email'] = $customer['customer_email'];
    $_SESSION['user_role']      = $customer['user_role'];
    redirect('../views/account/my_account.php');
} else {
    $_SESSION['error'] = $result['error'];
    redirect('../views/register.php');
}
?>
