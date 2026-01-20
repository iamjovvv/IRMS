<?php

$host = 'localhost';
$db = 'irms_db';
$user = 'root';
$pass = '1234';
$charset = 'utf8';
$port = 3308;

// $charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        
    ]);
} catch (PDOException $e) {
    // die('Database connection failed');
    die('Database connection failed: ' . $e->getMessage());
}

?>