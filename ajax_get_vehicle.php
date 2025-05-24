<?php

require 'db.php';

$vehicles = $pdo->query("SELECT * FROM vehicles WHERE available = 1")->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($vehicles);


