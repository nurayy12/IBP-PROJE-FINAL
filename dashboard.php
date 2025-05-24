<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Control Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Welcome, <?= $_SESSION['name'] ?>!</h1>
    <p><a href="rentals.php" class="btn-primary" >Rent a Vehicle</a> | <a href="logout.php">Logout</a></p>
    <p><a href="rented_vehicles.php" class="btn-primary">My Rental History</a></p>
   
</body>
</html>
