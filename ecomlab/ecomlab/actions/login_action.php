<?php

// This action file is the endpoint that js/customer.js POSTs login
// credentials to. It follows the same pattern as customer_register_action.php:
// read POST data → call controller → return JSON → JS handles the result.
require_once "../core/core.php";
require_once "../controller/CustomerController.php";

header("Content-Type: application/json");

// Only accept POST requests — reject anything else early
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

// Sanitise: strip extra whitespace. filter_input also protects against
// basic header-injection by running the value through PHP's built-in
// FILTER_SANITIZE_EMAIL / FILTER_DEFAULT filters.
$email = trim(filter_input(INPUT_POST, 'login_email', FILTER_SANITIZE_EMAIL) ?? '');
$pass  = trim($_POST['login_pass'] ?? '');

// Server-side presence check (JS already validates, but JS can be bypassed)
if ($email === '' || $pass === '') {
    echo json_encode(["success" => false, "message" => "Email and password are required."]);
    exit;
}

// Hand credentials to the controller, which calls the model's login()
$controller = new CustomerController();
$customer   = $controller->login($email, $pass);

if ($customer) {
    // Store the key fields the rest of the app needs in the session.
    // We never store the password hash here — only safe metadata.
    $_SESSION['customer_id']    = $customer['customer_id'];
    $_SESSION['customer_name']  = $customer['customer_name'];
    $_SESSION['customer_email'] = $customer['customer_email'];
    $_SESSION['user_role']      = $customer['user_role'];

    echo json_encode([
        "success"  => true,
        "message"  => "Login successful. Redirecting...",
        "redirect" => "../index.php"   // JS will follow this URL
    ]);
} else {
    // Do NOT say whether the email or password was wrong specifically —
    // that leaks information about which accounts exist.
    echo json_encode(["success" => false, "message" => "Invalid email or password."]);
}
