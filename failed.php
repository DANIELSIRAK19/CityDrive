<?php include './header.php'; ?> 

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Payment Failed - Car Rental</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">

	<style>
		.payment-failed-box {
			max-width: 650px;
			margin: 80px auto;
			padding: 40px;
			border-radius: 10px;
			background: #ffffff;
			box-shadow: 0 4px 12px rgba(0,0,0,0.1);
			text-align: center;
		}

		.payment-failed-box h1 {
			color: #e74c3c;
			font-weight: 700;
			margin-bottom: 20px;
		}

		.payment-failed-box .icon {
			font-size: 60px;
			color: #e74c3c;
			margin-bottom: 20px;
		}

		.order-info {
			background: #f8d7da;
			padding: 10px;
			border-radius: 6px;
			margin-bottom: 20px;
		}
	</style>
</head>

<body class="bg-light">

<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="payment-failed-box">

        <div class="icon">❌</div>
        <h1>Payment Failed</h1>
        <p class="text-muted">Unfortunately, your payment could not be completed.</p>

        <?php if (isset($_GET["order_id"])): ?>
            <div class="order-info">
                Order ID: <strong><?= htmlspecialchars($_GET["order_id"]) ?></strong>
            </div>
        <?php endif; ?>

        <p>Please try again later or use a different payment method.</p>

        <a href="index.php" class="btn btn-primary btn-lg mt-3">Return to Home</a>
    </div>
</div>


<?php include './footer.php'; ?>

</body>
</html>
