<?php
// ================================
// Database Configuration
// Change these if your XAMPP MySQL setup is different
// ================================
$db_host = "localhost";
$db_name = "notes_sharing_system";
$db_user = "root";
$db_pass = ""; // default XAMPP has no password

try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage() . "<br>Make sure XAMPP MySQL is running and you imported database.sql");
}
