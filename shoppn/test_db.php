<?php
// test_db.php
// This is a temporary script to test our database connection
require_once 'core/db_class.php';

echo "<h1>Database Connection Test</h1>";

try {
    $db = new Database();
    echo "<p style='color: green;'>✅ Successfully connected to the MySQL server!</p>";
    
    // Test if the `shoppn` database has tables (meaning the SQL file was imported)
    // We need to access the protected $conn property for testing. Let's do a quick reflection.
    $reflection = new ReflectionClass($db);
    $property = $reflection->getProperty('conn');
    $property->setAccessible(true);
    $conn = $property->getValue($db);
    
    $result = $conn->query("SHOW TABLES");
    
    if ($result && $result->num_rows > 0) {
        echo "<p style='color: green;'>✅ Found tables in the `shoppn` database! (The SQL file has been imported)</p>";
        echo "<ul>";
        while ($row = $result->fetch_array()) {
            echo "<li>" . htmlspecialchars($row[0]) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: orange;'>⚠️ Connected to the database, but it's empty. You still need to import `shoppn_empty.sql` via phpMyAdmin.</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Ensure your MySQL server (XAMPP/WAMP/MAMP) is running.</p>";
}
?>
