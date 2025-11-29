<?php
require_once("./src/Database.php");

// Read PayU JSON notification
$body = file_get_contents("php://input");

// Log for debugging
file_put_contents("payu_notify_log.txt", $body . PHP_EOL, FILE_APPEND);

// Always reply HTTP 200 so PayU does not retry
http_response_code(200);

// Decode JSON
$data = json_decode($body, true);

if (!$data || !isset($data["order"])) {
    exit("No order data");
}

$order = $data["order"];

$extOrderId   = $order["extOrderId"];
$totalAmount  = $order["totalAmount"] / 100;
$status       = $order["status"];
$payuOrderId  = $order["orderId"];

// Extract car_id from extOrderId
preg_match('/car(\d+)_(\d+)/', $extOrderId, $matches);
$car_id = $matches[1];

// Extract additionalDescription
$extra = json_decode($order["additionalDescription"], true);

$start_date  = $extra["start_date"];
$end_date    = $extra["end_date"];
$customer_id = $extra["customer_id"];

// ❗ STOP if payment failed
if ($status !== "COMPLETED") {
    file_put_contents("payu_notify_log.txt", "PAYMENT FAILED FOR ORDER: $payuOrderId\n", FILE_APPEND);
    return; // <-- SOLVES THE ISSUE
}

// Prevent duplicate booking
$check = $db->query("SELECT id FROM booking WHERE txnid='$extOrderId' LIMIT 1");
if ($check->num_rows > 0) {
    return; // already inserted
}

// Format dates
$formattedStart = date("Y-m-d H:i:s", strtotime($start_date));
$formattedEnd   = date("Y-m-d H:i:s", strtotime($end_date));
$payment_date   = date("Y-m-d H:i:s");
$booking_uid    = "BK" . uniqid();

// INSERT booking
$sql = "
INSERT INTO booking 
(
  booking_id, car, customer, start_date, end_date,
  total_price, booking_status, txnid, payment_id,
  payment_mode, payment_status, payment_date
)
VALUES 
(
  '$booking_uid',
  '$car_id',
  '$customer_id',
  '$formattedStart',
  '$formattedEnd',
  '$totalAmount',
  'Booked',
  '$extOrderId',       -- YOUR UNIQUE ID
  '$payuOrderId',      -- PAYU ORDER ID
  'online',
  'success',
  '$payment_date'
)
";

$db->query($sql);

file_put_contents("payu_notify_log.txt", "BOOKING INSERTED FOR PAYMENT: $payuOrderId\n", FILE_APPEND);
?>
