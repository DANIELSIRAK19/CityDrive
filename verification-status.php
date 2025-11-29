<?php
session_start();

if (!isset($_SESSION['isLogged']) || $_SESSION['isLogged'] == false) {
    header("Location: login.php");
    exit();
}

include './header.php';
require_once './src/Database.php';
require_once './src/Session.php';

$user = Session::get('user');
$customer_id = $user->id;

$sql = "SELECT is_verified FROM customers WHERE id='$customer_id' LIMIT 1";
$res = $db->query($sql);
$row = $res->fetch_object();

if (!$row) {
    echo "<h3>User not found.</h3>";
    include './footer.php';
    exit();
}

$status = $row->is_verified;
?>

<main id="main">
<section class="section-bg">
<div class="container text-center" style="padding-top:120px; padding-bottom:120px">

<?php if ($status === "Pending"): ?>

    <h3>Your documents are under review</h3>
    <p>This process may take 1–2 business days.</p>
    <a href="./cars.php" class="btn btn-primary btn-lg">
                    Return
                </a>


<?php elseif ($status === "Verified"): ?>

    <h3>Your profile is verified</h3>
    <p>You may now proceed to book a car.</p>
            <a href="./cars.php" class="btn btn-primary btn-lg">
                Book Now
            </a>
                


<?php else: ?>

    <!-- safety fallback -->
    <h3>Your profile is not verified</h3>
    <a href="profile-upload.php" class="btn btn-primary mt-3">Verify My Profile</a>

<?php endif; ?>

</div>
</section>
</main>

<?php include './footer.php'; ?>
