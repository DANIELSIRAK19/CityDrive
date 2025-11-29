<?php
include './header.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(400);
    echo 'Bad request';
    return;
}

require_once '../src/Database.php';
$db = Database::getInstance();

$id = $db->real_escape_string($_GET['id']);

$sql = "SELECT * FROM booking WHERE id = '$id'";
$res = $db->query($sql);
$data = $res->fetch_object();
$fileName1 = explode('/', $data->age_proof_path)[4];
$fileName2 = explode('/', $data->add_proof_path)[4];
?>
<div class="container-fluid">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"><a href="">Dashboard</a> / Booking</li>
    </ol>
    <div class="card">
        <div class="card-header">
            Adhar Booking application details
        </div>
        <div class="card-body">
            <table class="table tabled-bordered">
                <tr>
                    <td><strong>Name: </strong><?php echo htmlspecialchars($data->name) ?></td>
                    <td><strong>Gender: </strong><?php echo htmlspecialchars($data->gender) ?></td>
                    <td><strong>Phone: </strong><?php echo htmlspecialchars($data->phone) ?></td>
                </tr>
                <tr>
                    <td><strong>Dob.: </strong><?php echo htmlspecialchars($data->dob) ?></td>
                    <td><strong>Adhar Booking Date: </strong><?php echo htmlspecialchars($data->date) ?></td>

                </tr>
                <tr>
                    <td colspan="3"><strong>Address: </strong> <?php echo htmlspecialchars($data->address) ?></td>
                </tr>
                <tr>
                    <td><strong>City.: </strong><?php echo htmlspecialchars($data->city) ?></td>
                    <td><strong>State: </strong><?php echo htmlspecialchars($data->state) ?></td>
                    <td><strong>Pin: </strong><?php echo htmlspecialchars($data->zip) ?></td>
                </tr>
                <tr>
                    <td><strong>Age proof document.: </strong><?php echo htmlspecialchars($data->age_proof_name) ?></td>
                    <td><a target="_blank" href="./uploaded-files/adhar-enroll-apply/<?php echo $fileName1 ?>"><i
                                class="fa fa-file-pdf text-danger"></i> Age proof document</a></td>
                </tr>
                <tr>
                    <td><strong>Address proof document.: </strong><?php echo htmlspecialchars($data->add_proof_name) ?>
                    </td>
                    <td><a target="_blank" href="./uploaded-files/adhar-enroll-apply/<?php echo $fileName2 ?>"><i
                                class="fa fa-file-pdf text-danger"></i> Address proof document</a></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<?php include './footer.php'?>