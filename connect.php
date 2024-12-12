<?php
// Database credentials
$dsn = 'mysql:host=localhost;dbname=test;charset=utf8mb4';
$user = 'root';
$password = '';

try {
    // Establish connection
    $pdo = new PDO($dsn, $user, $password);

    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>