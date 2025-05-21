<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require 'db.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $vehicle_id = $_POST['vehicle_id'];
    $user_id = $_SESSION['user_id'];
    $rent_date = $_POST['rent_date'];
    $return_date = $_POST['return_date'];

 
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM rentals WHERE vehicle_id = ? AND ((rent_date <= ? AND (return_date IS NULL OR return_date >= ?)) OR (rent_date <= ? AND (return_date IS NULL OR return_date >= ?)) OR (? <= rent_date AND ? >= IFNULL(return_date, ?)))");
    $stmt->execute([ 
        $vehicle_id, 
        $rent_date, $rent_date, 
        $return_date, $return_date, 
        $rent_date, $return_date, $rent_date
    ]);
    $overlap = $stmt->fetchColumn();

    if ($overlap > 0) {
        $msg = "⚠️ This vehicle is already rented for the selected dates.";
    } else {
        
        $stmt = $pdo->prepare("INSERT INTO rentals (user_id, vehicle_id, rent_date, return_date) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$user_id, $vehicle_id, $rent_date, $return_date])) {
            $msg = "✅ Vehicle rented successfully.";
        } else {
            $msg = "❌ Rental failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rent a Vehicle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Rent a Vehicle</h2>

<?php if ($msg): ?>
    <p style="color:green;"> <?= $msg ?> </p>
<?php endif; ?>


<div>
    <label class="fuel-label">Fuel Type:</label>
    <select id="fuel">
        <option value="">All</option>
        <option value="Gasoline">Gasoline</option>
        <option value="Diesel">Diesel</option>
        <option value="Electric">Electric</option>
    </select>

    <label class="fuel-label">Transmission Type:</label>
    <select id="transmission">
        <option value="">All</option>
        <option value="Automatic">Automatic</option>
        <option value="Manual">Manual</option>
    </select>

    <button type="button" id="applyFilters">Apply Filters</button>
</div>

<br>


<form method="POST">
    <label>Select Vehicle:</label>
    <select name="vehicle_id" id="vehicle_select" required>
     
    </select><br>

    <label>Start Date:</label>
    <input type="date" name="rent_date" required><br>

    <label>Return Date:</label>
    <input type="date" name="return_date" required><br>

    <button type="submit">Rent</button>
</form>

<p><a href="dashboard.php">Go Back</a></p>

<script>

document.getElementById("applyFilters").addEventListener("click", function() {
    const fuel = document.getElementById("fuel").value;
    const transmission = document.getElementById("transmission").value;

    const formData = new FormData();
    formData.append("fuel", fuel);
    formData.append("transmission", transmission);

 
    fetch("vehicle_filter.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        document.getElementById("vehicle_select").innerHTML = data; 
    })
    .catch(error => console.error('Error:', error));
});


window.addEventListener("load", function() {
    document.getElementById("applyFilters").click();
});
</script>

</body>
</html>
