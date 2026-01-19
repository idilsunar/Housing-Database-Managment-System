<?php
// db_connect.php - MySQL Database Connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'housing_mgmt';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("MySQL Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");
?>