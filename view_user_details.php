
<?php

require 'db_connection.php';

$vehiclesPerPage = 1; 

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View User Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 20px auto;
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        .user-details, .vehicle-details {
            margin-top: 20px;
            line-height: 1.6;
        }
        .user-details p, .vehicle-details p {
            margin: 5px 0;
            color: #555;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            margin: 0 5px;
            padding: 8px 16px;
            text-decoration: none;
            background-color: #333;
            color: #fff;
            border-radius: 4px;
        }
        .pagination a.active {
            background-color: #007bff;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>View User Details</h1>
        <?php
        if (isset($_GET['phone_number'])) {
            $phone_number = $_GET['phone_number'];

            try {
                $sql = "SELECT * FROM users WHERE phone_number = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$phone_number]);
                $user = $stmt->fetch();

                if ($user) {
                    echo "<div class='user-details'>";
                    echo "<h2>User Details:</h2>";
                    echo "<p>Name: " . htmlspecialchars($user['name']) . "</p>";
                    echo "<p>Phone Number: " . htmlspecialchars($user['phone_number']) . "</p>";
                    echo "<p>Date of Birth: " . htmlspecialchars($user['date_of_birth']) . "</p>";
                    echo "<p>Address: " . htmlspecialchars($user['address']) . "</p>";
                    echo "</div>";

                    // Pagination logic
                    $totalVehiclesSql = "SELECT COUNT(*) FROM vehicles WHERE user_id = ?";
                    $stmt = $pdo->prepare($totalVehiclesSql);
                    $stmt->execute([$user['id']]);
                    $totalVehicles = $stmt->fetchColumn();

                    $totalPages = ceil($totalVehicles / $vehiclesPerPage);

                    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($currentPage - 1) * $vehiclesPerPage;

                    $sql = "SELECT * FROM vehicles WHERE user_id = ? LIMIT ? OFFSET ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$user['id'], $vehiclesPerPage, $offset]);
                    $vehicles = $stmt->fetchAll();

                    if ($vehicles) {
                        echo "<div class='vehicle-details'>";
                        echo "<h2>Registered Vehicles:</h2>";
                        foreach ($vehicles as $vehicle) {
                            echo "<p>Manufacturer: " . htmlspecialchars($vehicle['manufacturer']) . "</p>";
                            echo "<p>Model: " . htmlspecialchars($vehicle['model']) . "</p>";
                            echo "<p>CC: " . htmlspecialchars($vehicle['cc']) . "</p>";
                            echo "<p>Color: " . htmlspecialchars($vehicle['color']) . "</p>";
                            echo "<p>Type: " . htmlspecialchars($vehicle['type']) . "</p>";
                            echo "<p>Seating Capacity: " . htmlspecialchars($vehicle['seating_capacity']) . "</p>";
                            echo "<p>RTO Location: " . htmlspecialchars($vehicle['rto_location']) . "</p>";
                            echo "<p>RTO Number: " . htmlspecialchars($vehicle['rto_number']) . "</p>";
                            echo "<p>Number Plate: " . htmlspecialchars($vehicle['number_plate']) . "</p><br>";
                        }
                        echo "</div>";

                        // Pagination links
                        if ($totalPages > 1) {
                            echo '<div class="pagination">';
                            for ($i = 1; $i <= $totalPages; $i++) {
                                $active = $i == $currentPage ? 'active' : '';
                                echo "<a href='?phone_number=$phone_number&page=$i' class='$active'>$i</a>";
                            }
                            echo '</div>';
                        }
                    } else {
                        echo "<p>No vehicles registered.</p>";
                    }
                } else {
                    echo "<p>No user found with this phone number.</p>";
                }
            } catch (PDOException $e) {
                echo "<p>Error: " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p>Please provide a phone number.</p>";
        }
        ?>
    </div>
</body>

</html>
