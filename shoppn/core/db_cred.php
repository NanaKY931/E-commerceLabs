<?php
// Database credentials with environment detection

if (isset($_SERVER['HTTP_HOST']) && (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false)) {
    // Local environment (e.g., XAMPP or PHP built-in server)
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'shoppn');
} else {
    // Remote live server environment
    define('DB_HOST', 'localhost');
    define('DB_USER', 'nana.yirenkyi');
    define('DB_PASS', 'DestinyV1sion!');
    define('DB_NAME', 'ecommerce_2026A_nana_yirenkyi');
}
?>
