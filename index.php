<?php include './header.php';

require_once './src/Session.php';
session_start();

$user = Session::get('user');
//unset($_SESSION['redirect_url']);
echo 'sessionvar_name' . $_SESSION['redirect_url'];
?>
<?php
require_once './src/Database.php';

// Fetch 3 latest cars for homepage
$sql = "SELECT * FROM cars ORDER BY id DESC LIMIT 3";
$result = $db->query($sql);

$cars_home = [];
while ($row = $result->fetch_object()) {
    $cars_home[] = $row;
}
?>

<!--==========================
    Intro Section
  ============================-->
<section id="intro" class="clearfix">
    <div class="container">

        <div class="intro-img">
            <img src="img/car34.png" alt="" class="img-fluid  wow fadeInUp">
        </div>

        <div class="intro-info">
            <h2> CityDrive Car Rental </h2>

            <div>
                <a href="#about" class="btn-get-started scrollto">Book Now</a>
                <a href="#services" class="btn-services scrollto">Explore Us</a>
            </div>
        </div>

    </div>
</section><!-- #intro -->

<main id="main">

    <!--==========================
      About Us Section
    ============================-->
    <section id="about">
        <div class="container">



            <div class="row about-container">

                <div class="col-lg-6 content order-lg-1 order-2">
                    <p>
                     City Drive Car Rental is Warsaw's oldest and most trusted source for premium vehicles — fully insured and ready to deliver an exceptional experience for our valued clients.           
                    </p>

                    <div class="icon-box wow fadeInUp">
                        <div class="icon"><i class="fa fa-map-marker"></i></div>
                        <h4 class="title pt-4"><a>Choose Location</a></h4>
                    </div>

                    <div class="icon-box wow fadeInUp" data-wow-delay="0.2s">
                        <div class="icon"><i class="fa fa-calendar"></i></div>
                        <h4 class="title pt-4"><a>Pickup Date</a></h4>
                    </div>

                    <div class="icon-box wow fadeInUp" data-wow-delay="0.4s">
                        <div class="icon"><i class="fa fa-car"></i></div>
                        <h4 class="title pt-4"><a>Book Your Car</a></h4>
                    </div>

                </div>

                <div class="col-lg-6 background order-lg-2 order-1 wow fadeInUp">
                    <img src="img/car.png" class="img-fluid" alt="">
                </div>
            </div>
        </div>
    </section><!-- #about -->

    <!--==========================
      Services Section
    ============================-->
    <section id="services" class="section-bg">
        <div class="container">

            <header class="section-header">
                <h3>Our Cars</h3>
               
            </header>

            <div class="row col">

                <div class="row">
                    <div class="col-md-4">
                        <div class="container">
                            <div class="row flex-nowrap" style="overflow-x:auto, white-space:nowrap">
                                <div class="card">
                                    <div class="bg-image hover-overlay">
                                        <img src="https://themewagon.github.io/carrent/images/img_1.jpg" style=""
                                            class="img-fluid" />
                                    </div>
                                    <hr class="my-0" />
                                    <div class=" p-1">
                                        <a href="#!" class="text-dark font-weight-bold ml-2">Mercedes Benz G63 AMG</a>
                                    </div>
                                    <div class="d-flex justify-content-between m-2">
                                        <span><i class="fa fa-cog"></i> Automatic</span>
                                        <span><i class="fa fa-users"></i> 5 Persons </span>
                                        <span><i class="fa fa-tachometer"></i> Diesel </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center p-2 mb-1">
                                        <a href="#!" class="text-dark font-weight-bold">PLN100/hr</a>
                                        <a href="" class="btn btn-outline-primary">View Car</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="container">
                            <div class="row">
                                <div class="card">
                                    <div class="bg-image hover-overlay">
                                        <img src="https://themewagon.github.io/carrent/images/img_1.jpg" style=""
                                            class="img-fluid" />
                                    </div>
                                    <hr class="my-0" />
                                    <div class=" p-1">
                                        <a href="#!" class="text-dark font-weight-bold ml-2">Audi R8 </a>
                                    </div>
                                    <div class="d-flex justify-content-between m-2">
                                        <span><i class="fa fa-cog"></i> Automatic</span>
                                        <span><i class="fa fa-users"></i> 2 Persons </span>
                                        <span><i class="fa fa-tachometer"></i> Petrol </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center p-2 mb-1">
                                        <a href="#!" class="text-dark font-weight-bold">PLN80/hr</a>
                                        <a href="cars.php" class="btn btn-outline-primary">View Car</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="container">
                            <div class="row">
                                <div class="card">
                                    <div class="bg-image hover-overlay">
                                        <img src="https://themewagon.github.io/carrent/images/img_1.jpg" style=""
                                            class="img-fluid" />
                                    </div>
                                    <hr class="my-0" />
                                    <div class=" p-1">
                                        <a href="#!" class="text-dark font-weight-bold ml-2">BMW M5 Competition</a>
                                    </div>
                                    <div class="d-flex justify-content-between m-2">
                                        <span><i class="fa fa-cog"></i> Manual</span>
                                        <span><i class="fa fa-users"></i> 5 Persons </span>
                                        <span><i class="fa fa-tachometer"></i> Petrol/LPG </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center p-2 mb-1">
                                        <a href="#!" class="text-dark font-weight-bold">PLN90/hr</a>
                                        <a href="" class="btn btn-outline-primary">View Car</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                
                
                </div>
                <div class="col-lg-12 mt-4 text-center">
                    <a href="./cars.php" class="btn btn-primary btn-lg" style="border-radius:1cm">View All
                        Cars</a>
                </div>
            </div>
        </div>
    </section><!-- #services -->
</main>
<?php include './footer.php' ?>