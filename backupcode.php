<?php
require_once("./src/Database.php");
require_once("./src/Session.php");
session_start();

if (!isset($_GET['car_id'])) {
    header("Location: index.php");
    exit();
}

// PayU Sandbox Credentials (YOUR REAL ONES)
$merchantPosId = "501312";
$clientId      = "501312";
$clientSecret  = "8db83a55a45200f0dcf743de7c69c77a";
$secondKey     = "e9169d7a2a9a689da0710071deddd5fb";

// PayU Sandbox Endpoints
$oauthUrl = "https://secure.snd.payu.com/pl/standard/oauth/token";
$orderUrl = "https://secure.snd.payu.com/api/v2_1/orders";

// Must be logged in
if (!isset($_SESSION['isLogged']) || $_SESSION['isLogged'] === false) {
    $_SESSION['login_message'] = "You must log in to continue.";
    header("Location: login.php");
    exit();
}

$user = Session::get('user');
$customer_id = $user->id;

// Fetch customer verification
$sql = "SELECT * FROM customers WHERE id='$customer_id'";
$res = $db->query($sql);
$customer = $res->fetch_object();

if ($customer->is_verified === "Not Verified" || $customer->is_verified === "Not verified") {
    header("Location: profile-not-verified.php");
    exit();
}
if ($customer->is_verified === "Pending") {
    header("Location: verification-status.php");
    exit();
}

// Get car data
$carid      = $_GET['car_id'];
$start_date = $_GET['start_date'];
$end_date   = $_GET['end_date'];
$price      = $_GET['amount'];

$sql = "SELECT * FROM cars WHERE id='$carid'";
$res = $db->query($sql);
$car = $res->fetch_object();
$car_name = $car->car_name;

//////////////////////////////////////////////////////////////
// STEP 1 — GET OAUTH TOKEN
//////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////
// STEP 1 — GET OAUTH TOKEN (WITH DEBUG HEADERS)
//////////////////////////////////////////////////////////////

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $oauthUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/x-www-form-urlencoded",
    "Authorization: Basic " . base64_encode("$clientId:$clientSecret")
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// ⬇️ ADD THESE TWO LINES FOR DEBUGGING REDIRECTS
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

$response = curl_exec($ch);

// DEBUG OUTPUT
echo "<h3>RAW RESPONSE (HEADERS + BODY)</h3>";
var_dump($response);

echo "<h3>CURL ERROR</h3>";
var_dump(curl_error($ch));

echo "<h3>HTTP CODE</h3>";
var_dump(curl_getinfo($ch, CURLINFO_HTTP_CODE));

curl_close($ch);
exit();




if (!isset($tokenData["access_token"])) {
    echo "<h2>ERROR: PayU token request failed</h2>";
    exit();
}

$accessToken = $tokenData["access_token"];

//////////////////////////////////////////////////////////////
// STEP 2 — CREATE ORDER
//////////////////////////////////////////////////////////////

$order = [
    "notifyUrl" => "https://huskily-prefinancial-lakeesha.ngrok-free.dev/car-rental/payu_notify.php",
    "customerIp" => $_SERVER['REMOTE_ADDR'],
    "merchantPosId" => $merchantPosId,
    "description" => "Car rental: $car_name",
    "currencyCode" => "PLN",
    "totalAmount" => $price * 100,
    "products" => [
        [
            "name" => $car_name,
            "unitPrice" => $price * 100,
            "quantity" => 1
        ]
    ],
    "buyer" => [
        "email" => $user->email,
        "phone" => $user->phone,
        "firstName" => $user->name,
        "lastName" => $user->name
    ],
    "continueUrl" => "https://huskily-prefinancial-lakeesha.ngrok-free.dev/car-rental/success.php"
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $orderUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($order));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $accessToken"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$data = json_decode($response, true);
curl_close($ch);

// Debug (TEMPORARY)
echo "<h3>DEBUG ORDER RESPONSE</h3>";
var_dump($response);

if (!isset($data["redirectUri"])) {
    echo "<h2>ERROR: PayU did not return redirect link</h2>";
    exit();
}

// Redirect customer to PayU payment page
header("Location: " . $data["redirectUri"]);
exit();

?>








// cars/php
                var html = '<div class="col-md-4">';
                html += '<div class="container">';
                html += '<div class="row">';
                html += '<div class="card">';
                html += '<div class="bg-image hover-overlay">';
                html += '<img src="http://localhost/car-rental' + extractedPath + '"  class="img-fluid" alt="' + car.car_name + '" />';
                html += '</div>';
                html += '<hr class="my-0" />';
                html += '<div class=" p-1">';
                html += '<a href="#!" class="text-dark font-weight-bold ml-2">' + car.car_name + '</a>';
                html += '</div>';
                html += '<div class="d-flex justify-content-between m-2">';
                html += '<span><i class="fa fa-cog"></i> ' + car.transmission + '</span>';
                html += '<span><i class="fa fa-users"></i> ' + car.seating_capacity + ' persons</span>';
                html += '</div>';
                html += '<div class="d-flex justify-content-between align-items-center p-2 mb-1">';
                html += '<a href="#!" class="text-dark font-weight-bold">PLN' + car.price_per_hour + '/hr</a>';
                html += '<a href="./car-details.php?id=' + car.id + '&start=' + encodeURIComponent(start) + '&end=' + encodeURIComponent(end) + '" class="btn btn-outline-primary">View Car</a>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '</div>';