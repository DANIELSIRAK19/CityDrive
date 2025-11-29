<?php 
$key="xxxxxxxx";
$salt="xxxxxxx";
$mode='test';  //test or live
// Set your pages path 
$success_url="http://localhost/car-rental/response.php"; // set your success page url response.php path
$failed_url="http://localhost/car-rental/failed.php"; // Transaction failed  page URL failed.php path 
$cancelled_url="http://localhost/car-rental/cancelled.php"; // Transaction cancelled  page URL cancelled.php path
// Please do not change anything after this line..
if($mode=='live')
{
$action = 'https://secure.payu.in/_payment';	
}
else {
$action = 'https://test.payu.in/_payment';	
$key="oZ7oo9";
$salt="UkojH5TS";
}



?>