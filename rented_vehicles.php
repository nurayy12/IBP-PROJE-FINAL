<?php


require 'functions.php'; 
check_login(); 
require 'db.php';

$user_id = $_SESSION['user_id']; 

if (isset($_GET['delete_rental_id'])) {
    $delete_id = $_GET['delete_rental_id'];

  
    $stmt = $pdo->prepare("DELETE FROM rentals WHERE id = ? AND user_id = ?");
    $stmt->execute([$delete_id, $user_id]);

    header("Location: rented_vehicles.php");
    exit();
}




$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT r.id AS rental_id, v.brand, v.model, v.price, r.rent_date, r.return_date
    FROM rentals r
    JOIN vehicles v ON r.vehicle_id = v.id
    WHERE r.user_id = ?
    ORDER BY r.rent_date DESC
");
$stmt->execute([$user_id]);
$rentals = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Rental History</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>My Rental History</h2>
     <?php if (count($rentals) > 0): ?>
        <?php foreach ($rentals as $rental): ?>
            <div class="vehicle-box">
                <h3><?= htmlspecialchars($rental['brand'] . ' ' . $rental['model']) ?></h3>
                <p><strong>Price:</strong> <?= $rental['price'] ?> ₺</p>
                <p><strong>Rental Date:</strong> <?= $rental['rent_date'] ?></p>
                <p><strong>Return Date:</strong> <?= $rental['return_date'] ?? 'Not Returned Yet' ?></p>
                <a href="rented_vehicles.php?delete_rental_id=<?= $rental['rental_id'] ?>" 
                onclick="return confirm('Are you sure you want to delete this rental?');" 
                class="btn-delete">Delete</a>

            
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>You do not have any rented vehicles yet.</p>
    <?php endif; ?>

    <div style="text-align:center; margin-top:20px;">
        <a href="index.php" class="btn-primary">Return to Homepage</a>
    </div>
   
</body>
</html>