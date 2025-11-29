<?php

require_once './src/Database.php';

// Always return JSON
header('Content-Type: application/json');

// CHECK IF BRANDS ARE SENT

if (!isset($_POST['brands']) || empty($_POST['brands'])) {
    echo json_encode([]);
    exit;
}
//VALIDATE BRAND INPUT
$brands = $_POST['brands'];

if (!is_array($brands)) {
    echo json_encode([]);
    exit;
}

// Convert all brand IDs to integers
$brands = array_map('intval', $brands);

// Convert to comma-separated list: "1,3,7"
$brandList = implode(',', $brands);

// MAIN QUERY — FETCH CARS BY MULTIPLE BRANDS

$sql = "SELECT * FROM cars WHERE brand IN ($brandList)";
$result = $db->query($sql);

// Build response array
$cars = [];
while ($row = $result->fetch_object()) {
    $cars[] = $row;
}

// RETURN JSON
echo json_encode($cars);
exit;

?>
