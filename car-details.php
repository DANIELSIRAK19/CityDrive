<?php
include './header.php';
require_once './src/Session.php';


session_start();

$user = Session::get('user');

// Get current URL
$current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

// Set redirect URL only if it's not already set or if it's the current URL
if (!isset($_SESSION['redirect_url']) || $_SESSION['redirect_url'] !== $current_url) {
    $_SESSION['redirect_url'] = $current_url;
}

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(400);
    echo 'Bad request';
    return;
}


require_once './src/Database.php';

$id = $_GET['id'];
$start = $_GET['start'];
$end = $_GET['end'];


$date1 = new DateTime($start);
$date2 = new DateTime($end);

// Format the date to DD-MM-YYYY
$formattedDate1 = $date1->format('d-m-Y');

// Format the time to hh:mm AM/PM
$formattedTime1 = $date1->format('h:i A');

// Format the date to DD-MM-YYYY
$formattedDate2 = $date2->format('d-m-Y');

// Format the time to hh:mm AM/PM
$formattedTime2 = $date2->format('h:i A');

// Combine the formatted date and time
$start_formattedDateTime = $formattedDate1 . ' ' . $formattedTime1;
$end_formattedDateTime = $formattedDate2 . ' ' . $formattedTime2;

// Calculate the difference in hours
$interval = $date1->diff($date2);
$totalHours = $interval->days * 24 + $interval->h;



echo "Total Hours Difference: $totalHours hours";

echo 'start date' . $start_formattedDateTime . '</br>';
echo 'end date' . $end_formattedDateTime;
//Start Date: 2024-04-24T10:15:00.000Z
// Use the id, start, and end values as needed


$id = $db->real_escape_string($_GET['id']);

$sql = "SELECT * FROM cars WHERE id = '$id'";
$res = $db->query($sql);
$car_details = $res->fetch_object();


$fileName1 = explode('/', $car_details->image1)[4];
//print_r($fileName1);die;
$fileName2 = explode('/', $car_details->image2)[4];
$fileName3 = explode('/', $car_details->image3)[4];
$convenience_fee = 50;
$total_price = ($car_details->price_per_hour * $totalHours) + $convenience_fee;



?>

<main id="main">

    <section class="section-bg">
        <div class="container">
            <div class="row" style="padding-top: 30px; padding-bottom:100px">
                <div class="col-lg-12 my-2">
                    <div class="card ">
                        <!--TODO: for mobile height 180px-->
                        <div class="card-body">
                            <h4>Book your car</h4>


                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-md-8">
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
                        <div class="col-md-4">
                            <!-- Card -->
                            <div class="card ">
                                <ul class="list-group list-group-flush">
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <h6 class="mb-0"><i class="fa fa-venus-mars" aria-hidden="true"></i>Price Per
                                            hour</h6>
                                        <span
                                            class="text-secondary">PLN<?php echo $car_details->price_per_hour ?>/hr</span>
                                    </li>
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center flex-wrap border-top-0">
                                        <h6 class="mb-0"><i class="fa fa-calendar" aria-hidden="true"></i> Convenience
                                            Fee
                                        </h6>
                                        <span class="text-secondary">50</span>
                                    </li>

                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center flex-wrap border-top-0  ">
                                        <h6 class="mb-0"><i class="fas fa-calendar-plus"></i> Total Hours</h6>
                                        <span class="text-secondary"><?php echo $totalHours ?></span>
                                    </li>


                                </ul>
                                <!--<ul class="list-group list-group-flush">
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                        <h6 class="mb-0"><i class="fa fa-venus-mars" aria-hidden="true"></i>Gender</h6>
                                        <span class="badge bg-info text-dark">male</span>
                                    </li>
                                </ul>
                                <form method="post" action="./booking.php"> <!-- Specify the action attribute
                                <input type="hidden" name="car_id" value="<?php echo $id ?>" />
                                <input type="hidden" name="total_price" value="<?php echo $total_price ?>" />
                                <input type="hidden" name="start_date" value=" <?php echo $start_formattedDateTime ?>" />
                                <input type="hidden" name="end_date" value=" <?php echo $end_formattedDateTime ?>" />
                                <input type="hidden" name="customer_id" value=" <?php echo $user->id ?>" />

                               
                                <div class="text-center m-3">
                                    <button type="submit" class="btn btn-success">
                                        Rent (PLN <?php echo $total_price ?>)
                                    </button>
                                </div>-->
                                <a href="pay.php?car_id=<?php echo $id ?>&amount=<?php echo $total_price ?>&start_date=<?php echo $start_formattedDateTime ?>&end_date=<?php echo $end_formattedDateTime ?>"
                                class="btn btn-primary">PLN <?php echo $total_price ?> Rent Now</a>


                                </form>


                            </div>
                        </div>

                    </div>
                    <div class="row ">

                        <div class="col-md-8">
                            <h3 class="mt-3"><?php echo htmlspecialchars($car_details->car_name) ?></h3>
                            <!-- Product Description -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                                role="tab" aria-controls="home" aria-selected="true">Car details</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile"
                                                role="tab" aria-controls="profile" aria-selected="false">Booking
                                                Time</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact"
                                                role="tab" aria-controls="contact" aria-selected="false">Description</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="home" role="tabpanel"
                                            aria-labelledby="home-tab">


                                            <div class="d-flex justify-content-between m-2">
                                                <span>Fuel Type:
                                                    <?php echo $car_details->fuel_type ?>
                                                </span>
                                                <span>Body Type:
                                                    <?php echo $car_details->car_body_type ?>
                                                </span>
                                                <span>Seating Capacity:
                                                    <?php echo $car_details->seating_capacity ?>
                                                </span>

                                            </div>
                                            <div class="d-flex justify-content-between m-2">
                                                <span>Km Driven:
                                                    <?php echo $car_details->km_driven ?>
                                                </span>
                                                <span>Body Type:
                                                    <?php echo $car_details->reg_no ?>
                                                </span>
                                                <span>Seating Capacity:
                                                    <?php echo $car_details->model ?>
                                                </span>

                                            </div>
                                            <div class="d-flex justify-content-between m-2">
                                                <span>Ac:
                                                    <?php $car_details->ac == '0' ? $labelColor = 'danger' : $labelColor = 'success' ?>
                                                    <td><span
                                                            class="badge badge-<?php echo $labelColor ?>"><?php echo $car_details->ac == '0' ? 'No' : 'Yes' ?></span>
                                                </span>
                                                <span>Air Bags:
                                                    <?php $car_details->air_bags == '0' ? $labelColor = 'danger' : $labelColor = 'success' ?>
                                                    <td><span
                                                            class="badge badge-<?php echo $labelColor ?>"><?php echo $car_details->air_bags == '0' ? 'No' : 'Yes' ?></span>
                                                </span>
                                                <span>Sun Roof:
                                                    <?php $car_details->sun_roof == '0' ? $labelColor = 'danger' : $labelColor = 'success' ?>
                                                    <td><span
                                                            class="badge badge-<?php echo $labelColor ?>"><?php echo $car_details->sun_roof == '0' ? 'No' : 'Yes' ?></span>
                                                </span>

                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="profile" role="tabpanel"
                                            aria-labelledby="profile-tab">
                                            <p>Pickup Time : <?php echo $start_formattedDateTime ?></p>
                                            <p> Booking End Date : <?php echo $end_formattedDateTime ?></p>
                                        </div>
                                        <div class="tab-pane fade" id="contact" role="tabpanel"
                                            aria-labelledby="contact-tab">

                                            <p><?php echo $car_details->description ?></p>
                                        </div>
                                    </div>
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