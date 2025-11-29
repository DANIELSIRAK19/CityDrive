<?php
require_once './Database.php';

//$db = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    echo 'Method not allowed';
    return;
}

$isError = false;
$errors = [
    "name" => "",
    "brandImage" => "",
];

if (empty($_POST['name'])) {
    $isError = true;
    $errors['name'] = 'Please enter brand name';
}



# File validation

//photo validation

$allowedExts = ['png', 'jpeg', 'jpg'];
$maxAllowedSize = 1024 * 500; // 500KB

if ($_FILES['photo']['error'] == 4) {
    $isError = true;
    $errors['brandImage'] = 'please select photo';
} else {
    if (
        !in_array(
            strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION)),
            $allowedExts
        )
    ) {
        $isError = true;
        $errors['brandImage'] = 'Invalid file format for photo (png, jpg, jpeg only)';
    }

    if ($_FILES['photo']['size'] > $maxAllowedSize) {
        $isError = true;
        $errors['brandImage'] = 'photo size is too large (MAX 100KB)';
    }
}

if ($isError) {
    http_response_code(400);
    $payload = json_encode(['msg' => 'Correct the following fields', 'errors' => $errors]);
    echo $payload;
    return;
}

$name = $db->real_escape_string($_POST['name']);



$fileName = md5(time());
$ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
$fullFilePath = '../admin/uploaded-files/brands/' . $fileName . '.' . $ext;

try {

    $sql = "INSERT INTO brands (brand_name, image)
    VALUES ('$name', '$fullFilePath')";

   
    $db->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

    if ($db->query($sql) === false) {
        throw new Exception('Failed to save data');
    }


    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $fullFilePath)) {
        throw new Exception('Failed to move uploaded file');
    }

    $db->commit();

    http_response_code(200);
    echo json_encode(['msg' => 'Your request is submitted']);

} catch (Exception $e) {

    if (file_exists($fullFilePath)) {
        unlink($fullFilePath);
    }

    $db->rollback();
    $db->commit();

    http_response_code(500);
    echo json_encode(['msg' => 'Can not submit your request at this time, please try again later']);
}