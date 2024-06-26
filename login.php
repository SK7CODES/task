<?php
session_start();
require 'db_connection.php';

$phone_number = $_POST['phone_number'];
$password = $_POST['password'];

try {
    $sql = "SELECT * FROM users WHERE phone_number = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$phone_number]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: register_vehicle.html");
        exit();
    } else {
        echo "Invalid phone number or password.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
