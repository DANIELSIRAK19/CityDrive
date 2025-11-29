<?php
require_once './Database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    echo json_encode(['msg' => 'Method not allowed']);
    exit;
}

$errors = [
    "name" => "",
    "email" => "",
    "phone" => "",
    "address" => "",
    "driving_license_no" => "",
    "driving_license_image1" => "",
    "driving_license_image2" => "",
    "address_proof_no" => "",
    "address_proof_image" => "",
];

$isError = false;
$id = $_POST['id'];

// ---------------- VALIDATION ----------------

if (empty($_POST['name'])) {
    $isError = true;
    $errors['name'] = 'Please enter name';
}
if (empty($_POST['email'])) {
    $isError = true;
    $errors['email'] = 'Please enter email';
}
if (empty($_POST['phone'])) {
    $isError = true;
    $errors['phone'] = 'Please enter phone';
}
if (empty($_POST['address'])) {
    $isError = true;
    $errors['address'] = 'Please enter address';
}
if (empty($_POST['driving-license-no'])) {
    $isError = true;
    $errors['driving_license_no'] = 'Please enter driving license number';
}
if (empty($_POST['address-proof-no'])) {
    $isError = true;
    $errors['address_proof_no'] = 'Please enter address proof number';
}


// ---------------- FILE VALIDATION ----------------

$allowedExts = ['png', 'jpeg', 'jpg'];
$maxAllowedSize = 1024 * 300; // 300KB

function validate_file($file, $allowedExts, $maxAllowedSize, &$isError, &$errors, $keyName)
{
    if ($file['error'] === 4) {
        $isError = true;
        $errors[$keyName] = "Please upload a file";
        return;
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExts)) {
        $isError = true;
        $errors[$keyName] = "Invalid file format (jpg, jpeg, png only)";
    }

    if ($file['size'] > $maxAllowedSize) {
        $isError = true;
        $errors[$keyName] = "Image size too large (MAX 300KB)";
    }
}

validate_file($_FILES['driving-license-image1'], $allowedExts, $maxAllowedSize, $isError, $errors, 'driving_license_image1');
validate_file($_FILES['driving-license-image2'], $allowedExts, $maxAllowedSize, $isError, $errors, 'driving_license_image2');
validate_file($_FILES['address-proof-image'], $allowedExts, $maxAllowedSize, $isError, $errors, 'address_proof_image');

if ($isError) {
    http_response_code(400);
    echo json_encode(['msg' => 'Please correct the fields below', 'errors' => $errors]);
    exit;
}


// ---------------- PROCESS & SAVE ----------------

// sanitize
$name    = $db->real_escape_string($_POST['name']);
$email   = $db->real_escape_string($_POST['email']);
$phone   = $db->real_escape_string($_POST['phone']);
$address = $db->real_escape_string($_POST['address']);
$dl_no   = $db->real_escape_string($_POST['driving-license-no']);
$ap_no   = $db->real_escape_string($_POST['address-proof-no']);

// generate paths with real extension
$ext1 = strtolower(pathinfo($_FILES['driving-license-image1']['name'], PATHINFO_EXTENSION));
$ext2 = strtolower(pathinfo($_FILES['driving-license-image2']['name'], PATHINFO_EXTENSION));
$ext3 = strtolower(pathinfo($_FILES['address-proof-image']['name'], PATHINFO_EXTENSION));

// ensure directories exist
if (!is_dir('../admin/uploaded-files/customer-doc/DL/')) {
    mkdir('../admin/uploaded-files/customer-doc/DL/', 0777, true);
}
if (!is_dir('../admin/uploaded-files/customer-doc/address-proof/')) {
    mkdir('../admin/uploaded-files/customer-doc/address-proof/', 0777, true);
}

$dl1Path = '../admin/uploaded-files/customer-doc/DL/' . md5(time()) . ".$ext1";
$dl2Path = '../admin/uploaded-files/customer-doc/DL/' . md5(time() + 500) . ".$ext2";
$apPath  = '../admin/uploaded-files/customer-doc/address-proof/' . md5(time() + 1000) . ".$ext3";

try {
    $db->begin_transaction();

    $sql = "UPDATE customers SET 
                name = '$name',
                email = '$email',
                phone = '$phone',
                address = '$address',
                driving_license_no = '$dl_no',
                driving_license_image1 = '$dl1Path',
                driving_license_image2 = '$dl2Path',
                address_proof_no = '$ap_no',
                address_proof_image = '$apPath',
                is_verified = 'Pending'
            WHERE id = '$id'";

    if (!$db->query($sql)) {
        throw new Exception("Database update failed");
    }

    // save files
    if (!move_uploaded_file($_FILES['driving-license-image1']['tmp_name'], $dl1Path))
        throw new Exception("Failed uploading DL front");

    if (!move_uploaded_file($_FILES['driving-license-image2']['tmp_name'], $dl2Path))
        throw new Exception("Failed uploading DL back");

    if (!move_uploaded_file($_FILES['address-proof-image']['tmp_name'], $apPath))
        throw new Exception("Failed uploading address proof");

    $db->commit();

    echo json_encode([
        'msg' => 'Documents submitted successfully. Redirecting...',
        'redirect' => 'verification-status.php'
    ]);
    exit;

} catch (Exception $e) {
    $db->rollback();

    // cleanup partial files
    if (file_exists($dl1Path)) unlink($dl1Path);
    if (file_exists($dl2Path)) unlink($dl2Path);
    if (file_exists($apPath)) unlink($apPath);

    http_response_code(500);
    echo json_encode(['msg' => 'Profile update failed. Try again later.']);
    exit;
}
