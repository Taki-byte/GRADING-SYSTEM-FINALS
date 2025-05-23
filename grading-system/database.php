<?php
try {
    $conn = new PDO("mysql:host=localhost;dbname=account_database", "root", "");
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
    error_log("Database connection error: " . $e->getMessage(), 0);
    die("Connection failed. Please try again later.");
}
?>
