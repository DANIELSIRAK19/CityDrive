<?php
require_once './Database.php';



if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    echo 'Method not allowed';
    return;
}

$isError = false;
$errors = [
    "carName" => "",
    "brandName" => "",
    "carBodyType" => "",
    "transmissin" => "",
    "fuel_type" => "",
    "model" => "",
    "seating_capacity" => "",
    "km_driven" => "",
    "price_per_hour" => "",
    "reg_no" => "",
    "image" => "",
];

if (empty($_POST['car_name'])) {
    $isError = true;
    $errors['carName'] = 'Please enter car name';
}

if (empty($_POST['brand'])) {
    $isError = true;
    $errors['brandName'] = 'Please enter brand';
}

if (empty($_POST['car_body_type'])) {
    $isError = true;
    $errors['carBodyType'] = 'Please enter car_body_type ';
}


if (empty($_POST['transmission'])) {
    $isError = true;
    $errors['transmission'] = 'Please enter transmission';
}


if (empty($_POST['fuel_type'])) {
    $isError = true;
    $errors['fuel_type'] = 'Please enter fuel_type no';
}

if (strlen($_POST['model']) < 1) {
    $isError = true;
    $errors['model'] = 'Please enter model ';
}

if (empty($_POST['seating_capacity'])) {
    $isError = true;
    $errors['seating_capacity'] = 'Please enter seating_capacity';
}

if (empty($_POST['km_driven'])) {
    $isError = true;
    $errors['km_driven'] = 'Please enter km_driven';
}

if (empty($_POST['price_per_hour'])) {
    $isError = true;
    $errors['price_per_hour'] = 'Please enter price_per_hour';
}
if (empty($_POST['reg_no'])) {
    $isError = true;
    $errors['reg_no'] = 'Please enter reg_no';
}



# File validation

$allowedExts = ['png', 'jpeg', 'jpg'];
$maxAllowedSize = 5 * 1024 * 1024; // 5MB


foreach ($_FILES['images']['error'] as $key => $error) {
    if ($error == 4) {
        $isError = true;
        $errors['image'] = 'Please select car images';
    } else {
        if (
            !in_array(
                strtolower(pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION)),
                $allowedExts
            )
        ) {
            $isError = true;
    $errors['image'] = 'Invalid file format for image (jpg, jpeg, png only)';
        }

        if ($_FILES['images']['size'][$key] > $maxAllowedSize) {
            $isError = true;
            $errors['image'] = 'mage size is too large (MAX 5MB)';
        }
    }
}

if ($isError) {
    http_response_code(400);
    $payload = json_encode(['msg' => 'Correct the following fields', 'errors' => $errors]);
    echo $payload;
    return;
}

   $car_name = $db->real_escape_string($_POST['car_name']);
   $brand = $db->real_escape_string($_POST['brand']);
   $car_body_type = $db->real_escape_string($_POST['car_body_type']);
   $transmission = $db->real_escape_string($_POST['transmission']);
   $fuel_type = $db->real_escape_string($_POST['fuel_type']);
   $model = $db->real_escape_string($_POST['model']);
   $seating_capacity = $db->real_escape_string($_POST['seating_capacity']);
   $km_driven = $db->real_escape_string($_POST['km_driven']);
   $price_per_hour = $db->real_escape_string($_POST['price_per_hour']);
   $reg_no = $db->real_escape_string($_POST['reg_no']);
   $ac = $db->real_escape_string($_POST['ac']);
   $sun_roof = $db->real_escape_string($_POST['sun_roof']);
   $air_bags = $db->real_escape_string($_POST['air_bags']);
   $central_lock = $db->real_escape_string($_POST['central_lock']);
   $description = $db->real_escape_string($_POST['description']);


// No errors, proceed with file processing
$uploadedFiles = [];
foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
    $fileName = md5(time() . $key);
    $ext = pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);
    $fullFilePath = '../admin/uploaded-files/cars/' . $fileName . '.' . $ext;
    //move_uploaded_file($tmp_name, $fullFilePath);
    if (!move_uploaded_file($tmp_name, $fullFilePath)) {
        if (file_exists($fullFilePath)) {
            unlink($fullFilePath);
        }
        throw new Exception('Failed to upload file: ' . $_FILES['images']['name'][$key]);
    }
    $uploadedFiles[] = $fullFilePath;
}

//print_r($uploadedFiles[0]);die;

$image_1 = $uploadedFiles[0];
$image_2 = $uploadedFiles[1];
$image_3 = $uploadedFiles[2];


//$fileName = md5(time());
//$ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
//$fullFilePath = '../admin/uploaded-files/brands/'.$fileName.'.'.$ext;



try{

    $sql = "INSERT INTO cars (car_name,  brand,	car_body_type, transmission,fuel_type,  model,seating_capacity	,  	km_driven,price_per_hour,  	reg_no,ac,  sun_roof,
    air_bags,  central_lock,description,image1,image2,image3)
    VALUES ('$car_name', '$brand','$car_body_type','$transmission','$fuel_type','$model','$seating_capacity','$km_driven',
    '$price_per_hour','$reg_no','$ac','$sun_roof','$air_bags','$central_lock','$description','$image_1','$image_2','$image_3')";
    //echo $sql;die;
    
    $db->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

    if($db->query($sql) === false) {
        throw new Exception('Failed to save data');
    }

    
    $db->commit();

    http_response_code(200);
    echo json_encode(['msg' => 'Car added successfully']);

} catch(Exception $e){
    
    if(file_exists($fullFilePath)){
        unlink($fullFilePath);
    }

    $db->rollback();
    $db->commit();

    http_response_code(500);
    echo json_encode(['msg' => 'Can not submit your request at this time, please try again later']);
}