<?php
include './header.php';
require_once ("./src/Database.php");
require_once './src/Session.php';

if (!isset($_SESSION['isLogged']) || $_SESSION['isLogged'] === false) {
    header("Location: ./login.php");
    exit();
}

$user = Session::get('user');
$user_id = $user->id;

// Fetch all bookings latest first
$sql = "SELECT b.*, c.* 
        FROM booking b
        JOIN cars c ON b.car = c.id
        WHERE b.customer = '$user_id'
        ORDER BY b.start_date DESC";

$res = $db->query($sql);

$activeBookings = [];
$completedBookings = [];

while ($row = $res->fetch_object()) {
    
    // Normalize status text (optional)
    $status = strtolower($row->booking_status);

    if ($status === 'booked' || $status === 'live') {
        $activeBookings[] = $row;
    } else if ($status === 'completed') {
        $completedBookings[] = $row;
    }
}
?>

<main id="main">
<section class="section-bg">
<div class="container" style="padding-top:120px; padding-bottom:100px">

    <h3 class="mb-4">My Bookings</h3>


    <!-- ========================= -->
    <!--       ACTIVE BOOKINGS     -->
    <!-- ========================= -->

    <h4 class="mt-4 mb-3">Active Bookings</h4>

    <?php if(count($activeBookings) > 0): ?>
        <?php foreach($activeBookings as $car): ?>
            <?php $file = explode('/', $car->image1)[4]; ?>

            <div class="card mb-4 shadow-sm">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="./admin/uploaded-files/cars/<?php echo $file; ?>"
                             class="img-fluid rounded-start">
                    </div>

                    <div class="col-md-8">
                        <div class="card-body">
                            <a href="./view-my-booking.php?id=<?php echo $car->id ?>">
                                <h5 class="card-title"><?php echo $car->car_name; ?></h5>
                            </a>

                            <p class="card-text mb-1">Booking ID: <?php echo $car->booking_id; ?></p>
                            <p class="card-text mb-1">Start Date: <?php echo $car->start_date; ?></p>
                            <p class="card-text mb-1">End Date: <?php echo $car->end_date; ?></p>

                            <!-- Status Badge -->
                            <span class="badge bg-success">
                                <?php echo ucfirst($car->booking_status); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-muted">No active bookings.</p>
    <?php endif; ?>


    <!-- ========================= -->
    <!--     BOOKING HISTORY      -->
    <!-- ========================= -->

    <h4 class="mt-5 mb-3">Booking History</h4>

    <?php if(count($completedBookings) > 0): ?>
        <?php foreach($completedBookings as $car): ?>
            <?php $file = explode('/', $car->image1)[4]; ?>

            <div class="card mb-4 shadow-sm">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="./admin/uploaded-files/cars/<?php echo $file; ?>"
                             class="img-fluid rounded-start">
                    </div>

                    <div class="col-md-8">
                        <div class="card-body">
                            <a href="./view-my-booking.php?id=<?php echo $car->id ?>">
                                <h5 class="card-title"><?php echo $car->car_name; ?></h5>
                            </a>

                            <p class="card-text mb-1">Booking ID: <?php echo $car->booking_id; ?></p>
                            <p class="card-text mb-1">Start Date: <?php echo $car->start_date; ?></p>
                            <p class="card-text mb-1">End Date: <?php echo $car->end_date; ?></p>

                            <span class="badge bg-secondary">
                                <?php echo ucfirst($car->booking_status); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-muted">No booking history yet.</p>
    <?php endif; ?>

</div>
</section>
</main>

<?php include './footer.php'; ?>
