<?php

require 'db.php';


$fuel = $_POST['fuel'] ?? '';        
$transmission = $_POST['transmission'] ?? '';  


$query = "SELECT * FROM vehicles WHERE available = 1";  


if ($fuel) {
    $query .= " AND fuel = :fuel";  
}
if ($transmission) {
    $query .= " AND transmission = :transmission";  
}


$stmt = $pdo->prepare($query);


$params = [];
if ($fuel) {
    $params['fuel'] = $fuel;
}
if ($transmission) {
    $params['transmission'] = $transmission;
}


$stmt->execute($params);

$vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);


foreach ($vehicles as $vehicle) {
    echo "<option value='{$vehicle['id']}'>{$vehicle['brand']} - {$vehicle['model']} ({$vehicle['price']}₺)</option>";
}
?>
