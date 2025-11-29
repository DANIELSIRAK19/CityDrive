<?php
session_start();
ini_set('display_errors', 0);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Car Rental System</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Montserrat:300,400,500,700"
    rel="stylesheet">

  <!-- Old Bootstrap Datetimepicker (Restored) -->
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css">

  <!-- Bootstrap CSS -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Libraries CSS -->
  <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

  <!-- Main Stylesheet -->
  <link href="css/style.css" rel="stylesheet">

  <!-- Moment.js (Required for old datetimepicker) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

  <!-- =======================================================
    Theme Name: NewBiz
    Theme URL: https://bootstrapmade.com/newbiz-bootstrap-business-template/
    Author: BootstrapMade.com
    License: https://bootstrapmade.com/license/
  ======================================================= -->
</head>

<body>

  <!-- Header -->
  <header id="header" class="fixed-top">
    <div class="container">

      <!-- Logo -->
      <div class="logo float-left">
        <a href="./index.php" class="scrollto">
          <img src="img/logo-new.png" alt="" class="img-fluid">
        </a>
      </div>

      <!-- Navigation -->
      <nav class="main-nav float-right d-none d-lg-block">

        <?php if (isset($_SESSION['isLogged']) && $_SESSION['isLogged'] == true): ?>

          <ul>
            <li class="active"><a href="./index.php">Home</a></li>
            <li><a href="./cars.php">Explore cars</a></li>
            <li><a href="./aboutus.php">About Us</a></li>
            <li><a href="./contact.php">Contact Us</a></li>
          </ul>

          <!-- User Dropdown -->
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                <i class="fa fa-user-circle fa-fw"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="./profile.php">Profile</a>
                <a class="dropdown-item" href="./my-bookings.php">My Bookings</a>
                <a class="dropdown-item" href="./logout.php">Logout</a>
              </div>
            </li>
          </ul>

        <?php else: ?>

          <ul>
            <li class="active"><a href="./index.php">Home</a></li>
            <li><a href="./cars.php">Explore cars</a></li>
            <li><a href="./contact.php">Contact Us</a></li>
            <li><a href="./login.php">Login</a></li>
            <li><a href="./register.php">Register</a></li>
          </ul>

        <?php endif ?>

      </nav>
      <!-- End Navigation -->

    </div>
  </header>
  <!-- End Header -->
