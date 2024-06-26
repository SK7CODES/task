<?php
require 'db_connection.php';

$name = $_POST['name'];
$phone_number = $_POST['phone_number'];
$date_of_birth = $_POST['date_of_birth'];
$address = $_POST['address'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

try {
    $sql = "INSERT INTO users (name, phone_number, date_of_birth, address, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $phone_number, $date_of_birth, $address, $password]);

    header("Location: login.html");
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
