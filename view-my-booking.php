<?php
include './header.php';
require_once './src/Database.php';
require_once './src/Session.php';
session_start();

if (!isset($_SESSION['isLogged']) || $_SESSION['isLogged'] === false) {
    header("Location: ./login.php");
    exit();
  }
  

$user = Session::get('user');


if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(400);
    echo 'Bad request';
    return;
}




$id = $db->real_escape_string($_GET['id']);

$sql = "SELECT * FROM booking WHERE id = '$id'";
$res = $db->query($sql);
$booking_details = $res->fetch_object();

$fileName1 = explode('/', $car_details->image1)[4];

if ($booking_details) {
    // If booking details exist, fetch car details using the car_id from the booking details
    $car_id = $booking_details->car;
    $sql = "SELECT * FROM cars WHERE id = '$car_id'";
    $res = $db->query($sql);
    $car_details = $res->fetch_object();
    $fileName1 = explode('/', $car_details->image1)[4];
    //print_r($fileName1);die;
    $fileName2 = explode('/', $car_details->image2)[4];
    $fileName3 = explode('/', $car_details->image3)[4];

}



?>

<main id="main">
    <section class="section-bg">
        <div class="container">
            <div class="row" style="padding-top: 100px; padding-bottom:100px">
            
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <!-- Carousel -->
                            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                <ol class="carousel-indicators">
                                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                                </ol>
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img src="./admin/uploaded-files/cars/<?php echo $fileName1 ?>"
                                            class="d-block w-100" alt="...">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="./admin/uploaded-files/cars/<?php echo $fileName2 ?>"
                                            class="d-block w-100" alt="...">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="./admin/uploaded-files/cars/<?php echo $fileName3 ?>"
                                            class="d-block w-100" alt="...">
                                    </div>
                                </div>
                                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
                                    data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
                                    data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <!-- Card -->
                            <div class="card">
        <div class="card-header">
            Booking details
        </div>
        <div class="card-body">
            <table class="table tabled-bordered">
                <tr>
                    <td><strong>Booking Id: </strong><?php echo htmlspecialchars($booking_details->booking_id) ?></td>
                    <td><strong>Booking Date: </strong><?php echo htmlspecialchars($booking_details->booking_date) ?></td>

                </tr>
                <tr>
                    <td><strong>Start Time.: </strong><?php echo htmlspecialchars($booking_details->start_date) ?></td>
                    <td><strong>End Time: </strong><?php echo htmlspecialchars($booking_details->end_date) ?></td>

                </tr>
                <tr>
                    <td colspan="3"><strong>Total Price:</strong> <?php echo htmlspecialchars($booking_details->total_price) ?></td>
                </tr>
                <tr>
                    <td><strong>Booking Status.: </strong><span class="badge badge-pill badge-success"><?php echo $booking_details->booking_status; ?></span></td>
                    <td><strong>Payment Status No: </strong><span class="badge badge-pill badge-info"><?php echo $booking_details->payment_status; ?></span></td>

                </tr>
            </table>
        </div>
    </div>            
                        
                        </div>

                    </div>
                    
                </div>


            </div>
        </div>
    </section><!-- #intro -->
</main>
<?php include './footer.php' ?>