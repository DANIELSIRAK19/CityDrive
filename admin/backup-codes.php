FIRST SECTION FROM VIEW_CUSTOMER>PHP
<?php
include './header.php';


if (!isset($_GET['id']) || strlen($_GET['id']) < 1 || !ctype_digit($_GET['id'])) {
    header('Location:./index.php');
    exit();
}
require_once '../src/Database.php';


$id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $db->real_escape_string($id);

$query = "SELECT * FROM customers
       WHERE id = '$id'";

$result = $db->query($query);
$customer_details = $result->fetch_object();

$front_image = explode('/', $customer_details->driving_license_image1)[5];
$back_image = explode('/', $customer_details->driving_license_image2)[5];
$address_proof_image = explode('/', $customer_details->address_proof_image)[5];




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the form submission
    if (isset($_POST['status'])) {

        $customer_id = $_POST["customer_id"];
        $status = ($_POST['status'] == 'Verified') ? 'Verified' : 'Pending';

        // Example: $status = ($_POST['status'] == 'on') ? 'verified' : 'not_verified'; // Save $status to the backend or database
        $sql = " UPDATE customers SET is_verified = '$status' WHERE id = '$id'";
        //echo $sql;die;
        if ($db->query($sql) === true) {
            echo "<script>alert('User verified successfully'); window.location.href = './customers.php';</script>";
        } else {
            echo '<script>alert("Unable to verify user");</script>';

        }

    }
}


?>


customers.php first few lines:


//$db = Database::getInstance();

if (isset($_GET['delete']) && ctype_digit($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    // Optional: check that customer exists first
    $check = $db->prepare("SELECT id FROM customers WHERE id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $checkRes = $check->get_result();
    $check->close();

    if ($checkRes->num_rows < 1) {
        // No such customer, just reload the page
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    // Delete customer
    $stmt = $db->prepare("DELETE FROM customers WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $msg = "User deleted";
    } else {
        $error = "Cannot delete user";
    }

    $stmt->close();

    // Avoid resubmitting the delete on refresh
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
