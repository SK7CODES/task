<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$manufacturer = $_POST['manufacturer'];
$model = $_POST['model'];
$cc = $_POST['cc'];
$color = $_POST['color'];
$type = $_POST['type'];
$seating_capacity = $_POST['seating_capacity'];
$rto_location = $_POST['rto_location'];
$rto_number = $_POST['rto_number'];
$number_plate = $_POST['number_plate'];

try {
    // Ensure the number plate is unique
    $sql = "SELECT * FROM vehicles WHERE number_plate = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$number_plate]);

    if ($stmt->rowCount() > 0) {            
        echo "Number plate already exists. Please try again.";
        exit();
    }

    $sql = "INSERT INTO vehicles (user_id, manufacturer, model, cc, color, type, seating_capacity, rto_location, rto_number, number_plate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $manufacturer, $model, $cc, $color, $type, $seating_capacity, $rto_location, $rto_number, $number_plate]);

    echo "Vehicle registered successfully with number plate: $number_plate";
    header("Refresh: 3; URL=register_user.html"); 
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>


