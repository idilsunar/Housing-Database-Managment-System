<?php
// mongo_connect.php - MongoDB Connection
require_once __DIR__ . '/vendor/autoload.php';

try {
    $mongoClient = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $mongoDB = "support_tickets_db";
    $mongoCollection = "tickets";
} catch (Exception $e) {
    die("MongoDB Connection failed: " . $e->getMessage());
}
?>