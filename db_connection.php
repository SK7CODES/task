<?php

$host = 'localhost';
$db = 'new_db'; 
$user = 'postgres'; 
$pass = 'sahil'; 
$port = '5432'; 

// DSN (Data Source Name)
$dsn = "pgsql:host=$host;port=$port;dbname=$db";

try {
    // PDO (PHP Data Objects) instance
    $pdo = new PDO($dsn, $user, $pass);

    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
