<?php
session_start();
date_default_timezone_set('Africa/Accra'); // Common timezone for the region based on Paystack GHS currency

require_once __DIR__ . '/db_class.php';

// Define ROOT_URL dynamically
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script = $_SERVER['SCRIPT_NAME'];
$pos = strpos($script, '/shoppn/');
$rootUrl = $pos !== false ? $protocol . "://" . $host . substr($script, 0, $pos) . '/shoppn/' : '/shoppn/';
define('ROOT_URL', $rootUrl);

// Helper function to get client IP
function get_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

// Helper function to redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['customer_id']);
}

// Check if user is admin
function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1;
}

// Require login
function require_login() {
    if (!is_logged_in()) {
        redirect('views/login.php');
    }
}

// Require admin
function require_admin() {
    if (!is_admin()) {
        redirect('../index.php'); // Redirect to home with error in real app
    }
}
?>
