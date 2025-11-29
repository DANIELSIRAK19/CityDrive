<?php
include './header.php';

require_once '../src/Database.php';

// 1. Validate & read ID
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    // invalid id -> send back to customers list
    header('Location: ./customers.php');
    exit();
}

$id = (int) $_GET['id']; // safe integer

// 2. Fetch customer details with prepared statement
$stmt = $db->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$customer_details = $result->fetch_object();
$stmt->close();

// If no customer found, redirect or show error
if (!$customer_details) {
    echo "<script>alert('Customer not found'); window.location.href='./customers.php';</script>";
    exit();
}

// 3. Safely derive image file names (last part of the path)
$front_image = $customer_details->driving_license_image1
    ? basename($customer_details->driving_license_image1)
    : '';

$back_image = $customer_details->driving_license_image2
    ? basename($customer_details->driving_license_image2)
    : '';

$address_proof_image = $customer_details->address_proof_image
    ? basename($customer_details->address_proof_image)
    : '';

// 4. Handle verification POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['status'])) {

    // If checkbox is checked -> Verified, otherwise Pending
    $status = ($_POST['status'] === 'Verified') ? 'Verified' : 'Pending';

    $updateStmt = $db->prepare("UPDATE customers SET is_verified = ? WHERE id = ?");
    $updateStmt->bind_param("si", $status, $id);

    if ($updateStmt->execute()) {
        echo "<script>alert('User verified successfully'); window.location.href = './customers.php';</script>";
        $updateStmt->close();
        exit();
    } else {
        echo '<script>alert("Unable to verify user");</script>';
        $updateStmt->close();
    }
}
?>



<div class="container-fluid">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"><a href="./view-customer.php">Users</a> / View Customer</li>
    </ol>
    <div class="card">
        <div class="table-responsive" style="height: 600px; overflow-y:auto">
            <div class="col-lg-12">

                <table cellspacing="0" width="100%" class="my-5" style="margin-top:60px">

                    <!-- CUSTOMER INFO ROW -->
                    <tr>
                        <td colspan="2" style="padding: 10px">
                            <table width="100%" style="border-collapse: collapse;">
                                <tr style="vertical-align: middle;">

                                    <!-- Status -->
                                    <td width="110px"><strong>Status:</strong></td>

                                    <?php
                                    $status = $customer_details->is_verified;

                                    if ($status === 'Verified') {
                                        $labelColor = 'success';
                                    } elseif ($status === 'Pending') {
                                        $labelColor = 'warning';
                                    } else {
                                        $labelColor = 'danger'; // Not Verified
                                    }
                                    ?>

                                    <td width="160px">
                                        <span class="badge badge-<?php echo $labelColor; ?>" style="font-size:14px;">
                                            <?php echo $status; ?>
                                        </span>
                                    </td>

                                    <!-- Name -->
                                    <td width="110px"><strong>Name:</strong></td>
                                    <td><?php echo $customer_details->name; ?></td>

                                    <!-- Email -->
                                    <td width="130px"><strong>Email:</strong></td>
                                    <td><?php echo $customer_details->email; ?></td>

                                    <!-- Phone -->
                                    <td width="130px"><strong>Phone No:</strong></td>
                                    <td><?php echo $customer_details->phone; ?></td>
                                </tr>

                                <tr style="vertical-align: top;">
                                    <td width="110px" style="padding-top:40px"><strong>Address:</strong></td>
                                    <td colspan="7" style="padding-top:40px">
                                        <?php echo $customer_details->address; ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- DOCUMENTS ROW -->
                    <tr>
                        <td colspan="2" style="padding-top:50px">
                            <table width="100%">
                                <tbody>

                                    <!-- DL Number -->
                                    <tr style="vertical-align: middle;">
                                        <td width="180px"><strong>DL Number:</strong></td>
                                        <td>
                                            <input type="text" class="form-control"
                                                value="<?php echo $customer_details->driving_license_no; ?>" readonly />
                                        </td>
                                    </tr>

                                    <!-- DL IMAGES -->
                                    <tr>
                                        <td style="padding-top:20px"><strong>DL Images:</strong></td>
                                        <td class="image-box" style="padding-top:20px">

                                            <!-- FRONT IMAGE -->
                                            <?php if (!empty($front_image)) : ?>
                                                <img src="./uploaded-files/customer-doc/DL/<?php echo $front_image; ?>"
                                                    alt="DL Front" class="image"
                                                    style="width:150px; height:auto; margin-right:15px;"
                                                    onclick="openImage(this.src)">
                                            <?php else : ?>
                                                <div style="width:150px; height:100px; background:#f5f5f5; border:1px solid #ddd;
                                                            display:flex; align-items:center; justify-content:center;
                                                            color:#888;">
                                                    No Image
                                                </div>
                                            <?php endif; ?>


                                            <!-- BACK IMAGE -->
                                            <?php if (!empty($back_image)) : ?>
                                                <img src="./uploaded-files/customer-doc/DL/<?php echo $back_image; ?>"
                                                    alt="DL Back" class="image"
                                                    style="width:150px; height:auto;"
                                                    onclick="openImage(this.src)">
                                            <?php else : ?>
                                                <div style="width:150px; height:100px; background:#f5f5f5; border:1px solid #ddd;
                                                            display:flex; align-items:center; justify-content:center;
                                                            color:#888;">
                                                    No Image
                                                </div>
                                            <?php endif; ?>

                                        </td>
                                    </tr>

                                    <!-- ADDRESS PROOF NUMBER -->
                                    <tr>
                                        <td width="190px" style="padding-top:40px">
                                            <strong>Address Proof No:</strong>
                                        </td>
                                        <td style="padding-top:40px">
                                            <?php echo $customer_details->address_proof_no; ?>
                                        </td>
                                    </tr>

                                    <!-- ADDRESS PROOF IMAGE -->
                                    <tr>
                                        <td style="padding-top:20px"><strong>Address Proof Image:</strong></td>
                                        <td class="image-box" style="padding-top:20px">

                                            <?php if (!empty($address_proof_image)) : ?>
                                                <img src="./uploaded-files/customer-doc/address-proof/<?php echo $address_proof_image; ?>"
                                                    alt="Address Proof" class="image"
                                                    style="width:150px; height:auto;"
                                                    onclick="openImage(this.src)">
                                            <?php else : ?>
                                                <div style="width:150px; height:100px; background:#f5f5f5; border:1px solid #ddd;
                                                            display:flex; align-items:center; justify-content:center;
                                                            color:#888;">
                                                    No Image
                                                </div>
                                            <?php endif; ?>


                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </td>
                    </tr>

                </table>


               <form method="POST" action="">
                    <input type="hidden" name="customer_id" value="<?php echo $id ?>">

                    <div class="verify-container">
                        <label for="verifyBox" style="margin:0;"><strong>Is Verified:</strong></label>
                        <input type="checkbox" id="verifyBox" name="status" value="Verified"
                            <?php echo ($customer_details->is_verified === 'Verified') ? 'checked' : ''; ?>>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm mt-3">Submit</button>
                </form>



                <div class="page-break" style="position:relative; height:75px"></div>

            </div>
        </div>
    </div>

    <?php
    include './footer.php';
    ?>