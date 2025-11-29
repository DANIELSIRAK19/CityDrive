<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './src/Database.php';
require_once './src/Session.php';
session_start();

// Check logged in
if (!isset($_SESSION['isLogged']) || $_SESSION['isLogged'] == false) {
    header("Location: login.php");
    exit();
}

$user = Session::get('user');
if (!$user) {
    header("Location: logout.php");
    exit();
}

$customer_id = $user->id;

// Fetch user status
$sql = "SELECT is_verified FROM customers WHERE id = '$customer_id' LIMIT 1";
$res = $db->query($sql);
$row = $res->fetch_object();

if (!$row) {
    header("Location: logout.php");
    exit();
}

$status = $row->is_verified;

// Redirect based on status
if ($status === "Not Verified") {
    header("Location: verification-status.php");
    exit();
}

if ($status === "Pending") {
    header("Location: verification-status.php");
    exit();
}

if ($status === "Verified") {
    header("Location: verification-status.php");
    exit();
}

// Fallback
header("Location: profile-not-verified.php");
exit();
