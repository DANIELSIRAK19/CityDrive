<?php
require_once './src/Database.php';

// Get start & end from AJAX
$start = $_POST['start'];
$end   = $_POST['end'];

/*
 * CORRECT OVERLAP LOGIC:
 * Two date ranges overlap if:
 *      booking_start < selected_end
 *      AND
 *      booking_end > selected_start
 */

$sql = "
    SELECT c.*
    FROM cars c
    WHERE NOT EXISTS (
        SELECT 1
        FROM booking b
        WHERE b.car = c.id
          AND b.booking_status IN ('Booked', 'Live')
          AND (
                b.start_date < ? 
                AND 
                b.end_date > ?
          )
    )
";

$stmt = $db->prepare($sql);
$stmt->bind_param("ss", $end, $start);  // use correct order: (end, start)
$stmt->execute();

$result = $stmt->get_result();

$cars = [];
while ($row = $result->fetch_assoc()) {
    $cars[] = $row;
}

header('Content-Type: application/json');
echo json_encode($cars);
