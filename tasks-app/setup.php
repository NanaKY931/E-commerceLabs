<?php
$host = "localhost";
$db_user = "root";
$db_pass = "";
$conn = new mysqli($host, $db_user, $db_pass);
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
$conn->query("CREATE DATABASE IF NOT EXISTS tasks_app");
$conn->select_db("tasks_app");
$sql = "CREATE TABLE IF NOT EXISTS tasks (
id INT AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(255) NOT NULL,
description TEXT,
status ENUM('pending', 'in_progress', 'done') DEFAULT 'pending',
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
echo "<h2>Setup complete!</h2>";
echo "<p><a href='index.php'>Go to Tasks App</a></p>";
} else {
echo "Error creating table: " . $conn->error;
}