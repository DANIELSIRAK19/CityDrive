<?php
require_once "./src/Database.php";
include './header.php';

// GET extOrderId from PayU redirect
$extOrderId = $_GET['extOrderId'] ?? null;

// If no extOrderId, redirect to failed page
if (!$extOrderId) {
    header("Location: failed.php");
    exit();
}

// Check if PayU notify.php created the booking
$sql = "SELECT * FROM booking 
        WHERE txnid = '$extOrderId'
        LIMIT 1";

$res = $db->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Payment Success - Car Rental</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">

	<style>
		.payment-success-box {
			max-width: 650px;
			margin: 80px auto;
			padding: 40px;
			border-radius: 10px;
			background: #ffffff;
			box-shadow: 0 4px 12px rgba(0,0,0,0.1);
			text-align: center;
		}

		.payment-success-box h1 {
			color: #27ae60;
			font-weight: 700;
			margin-bottom: 20px;
		}

		.payment-success-box .icon {
			font-size: 60px;
			color: #27ae60;
			margin-bottom: 20px;
		}

		.order-info {
			background: #d4edda;
			padding: 10px;
			border-radius: 6px;
			margin-bottom: 20px;
			color: #155724;
		}
	</style>
</head>

<body class="bg-light">

<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="payment-success-box">

        <div class="icon">✔️</div>
        <h1>Payment Successful</h1>
        <p class="text-muted">Your transaction has been completed successfully.</p>

        <?php if ($res && $res->num_rows > 0): 
            $booking = $res->fetch_object();
        ?>
            <div class="order-info">
                Booking ID: <strong><?= htmlspecialchars($booking->booking_id) ?></strong><br>
                Order Ref: <strong><?= htmlspecialchars($extOrderId) ?></strong>
            </div>

            <p>Thank you for choosing our service! You can view all your bookings at any time.</p>

            <a href="my-bookings.php" class="btn btn-success btn-lg mt-3">Go to My Bookings</a>

        <?php else: ?>

            <!-- If somehow no booking exists, fail gracefully -->
            <div class="order-info" style="background:#f8d7da;color:#721c24;">
                Booking not found for Order: <strong><?= htmlspecialchars($extOrderId) ?></strong>
            </div>

            <a href="failed.php?order_id=<?= htmlspecialchars($extOrderId) ?>" class="btn btn-danger btn-lg mt-3">View Failure Page</a>

        <?php endif; ?>

    </div>
</div>

<?php include './footer.php'; ?>

</body>
</html>
