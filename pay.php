<?php
session_start();
require_once "./src/Database.php";
require_once "./src/Session.php";

include './header.php';



// ================= SECURITY =================
if (!isset($_SESSION['isLogged']) || $_SESSION['isLogged'] == false) {
    $_SESSION['login_message'] = "Please login before booking.";
    header("Location: login.php");
    exit();
}

//Check Verification of user
$user = Session::get('user');
$customer_id = $user->id;

// Verify user status
$sql = "SELECT is_verified FROM customers WHERE id='$customer_id' LIMIT 1";
$res = $db->query($sql);
$row = $res->fetch_object();

if (!$row || $row->is_verified !== "Verified") {
    header("Location: verification-status.php");
    exit();
}

// ========== STEP 1: GET request → Show review page ==========
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (!isset($_GET['car_id'], $_GET['amount'], $_GET['start_date'], $_GET['end_date'])) {
        echo "Invalid request.";
        exit();
    }

    $user = Session::get('user');

    $car_id     = $_GET['car_id'];
    $amount     = $_GET['amount'];
    $start_date = $_GET['start_date'];
    $end_date   = $_GET['end_date'];

    // Load car details
    $sql = "SELECT * FROM cars WHERE id = '$car_id'";
    $res = $db->query($sql);
    $car = $res->fetch_object();
    $fileName = explode('/', $car->image1)[4];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Review Booking - Car Rental</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
</head>

<body>
<div class="container mt-5 mb-5 pt-4 pb-4">
    <h2>Review Your Booking</h2>
    <hr>

    <div class="row">
        <!-- USER INFO -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">

                    <h5>Payer Information</h5>
                    <p><strong>Name:</strong> <?= $user->name ?></p>
                    <p><strong>Email:</strong> <?= $user->email ?></p>
                    <p><strong>Phone:</strong> <?= $user->phone ?></p>

                    <h5 class="mt-4">Booking Details</h5>
                    <p><strong>Car:</strong> <?= $car->car_name ?></p>
                    <p><strong>Start:</strong> <?= $start_date ?></p>
                    <p><strong>End:</strong> <?= $end_date ?></p>
                    <p><strong>Total Amount:</strong> PLN <?= $amount ?></p>

                </div>
            </div>
        </div>

        <!-- CAR SUMMARY + PAY BUTTON -->
        <div class="col-md-4 text-center">
            <div class="card">
                <img src="./admin/uploaded-files/cars/<?= $fileName ?>" class="card-img-top">
                <div class="card-body">
                    <h5><?= $car->car_name ?></h5>
                    <p class="card-text">PLN <?= $amount ?></p>
                </div>
            </div>

            <form action="pay.php" method="POST" class="mt-3">
                <input type="hidden" name="car_id" value="<?= $car_id ?>">
                <input type="hidden" name="amount" value="<?= $amount ?>">
                <input type="hidden" name="start_date" value="<?= $start_date ?>">
                <input type="hidden" name="end_date" value="<?= $end_date ?>">

                <button type="submit" class="btn btn-primary btn-lg w-100">
                    Proceed to PayU
                </button>
            </form>

        </div>
    </div>

</div>
<?php include './footer.php'; ?>

</body>
</html>


<?php
    exit(); // IMPORTANT: Prevents running PayU code on GET
}



// ========================================================
// ========== STEP 2: POST request → CREATE PAYU ORDER ====
// ========================================================

// ========================================================
// ========== STEP 2: POST request → CREATE PAYU ORDER ====
// ========================================================

$user = Session::get('user');

$car_id     = $_POST['car_id'];
$amount     = $_POST['amount'] * 100; // PayU requires x100
$start_date = $_POST['start_date'];
$end_date   = $_POST['end_date'];

// PayU credentials
$posId = "501312";
$clientId = "501312";
$clientSecret = "8db83a55a45200f0dcf743de7c69c77a";

// Your domain
$baseUrl = "https://huskily-prefinancial-lakeesha.ngrok-free.dev/CAR-RENTAL"; 

// Endpoints
$authUrl  = "https://secure.snd.payu.com/pl/standard/user/oauth/authorize";
$orderUrl = "https://secure.snd.payu.com/api/v2_1/orders";

// ===================== GET OAUTH TOKEN =====================
$auth = base64_encode("$clientId:$clientSecret");

$ch = curl_init($authUrl);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Basic $auth",
    "Content-Type: application/x-www-form-urlencoded"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
$response = curl_exec($ch);
curl_close($ch);

$token = json_decode($response)->access_token;

// ===================== CREATE UNIQUE extOrderId BEFORE =====================
$extOrderId = "car{$car_id}_" . time();

// ===================== CREATE ORDER =====================
$order = [
    "notifyUrl"   => "$baseUrl/payu_notify.php",

    // ⭐ SUCCESS REDIRECT WITH EXTORDERID
    "continueUrl" => "$baseUrl/success.php?extOrderId=$extOrderId",

    "customerIp"  => $_SERVER['REMOTE_ADDR'],
    "merchantPosId" => $posId,
    "description" => "Car Rental Booking",
    "currencyCode" => "PLN",
    "totalAmount"  => $amount,

    "additionalDescription" => json_encode([
        "car_id"      => $car_id,
        "start_date"  => $start_date,
        "end_date"    => $end_date,
        "customer_id" => $user->id
    ]),

    "products" => [
        [
            "name"     => "Car rental (#$car_id)",
            "unitPrice"=> $amount,
            "quantity" => 1
        ]
    ],

    // ⭐ THIS MUST MATCH continueUrl
    "extOrderId" => $extOrderId
];

// Execute API call
$ch = curl_init($orderUrl);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $token"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($order));

$result = curl_exec($ch);
curl_close($ch);

$data = json_decode($result);

// Redirect user to PayU page
if (isset($data->redirectUri)) {
    header("Location: " . $data->redirectUri);
    exit();
}

// Show error if FAIL
echo "<pre>";
print_r($data);
echo "</pre>";
exit();
