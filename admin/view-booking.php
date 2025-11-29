<?php
include './header.php';


require_once '../src/Database.php';

$id = filter_var($_GET['id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $db->real_escape_string($id);



$sql = "SELECT b.*, c.*, ca.* 
        FROM booking b
        LEFT JOIN customers c ON b.customer = c.id
        LEFT JOIN cars ca ON b.car = ca.id
        WHERE b.id = '$id'";
//echo $sql;die;

$res = $db->query($sql);
$data = $res->fetch_object();

$date1 = new DateTime($data->start_date);

$date2 = new DateTime($data->end_date);

// Format the date to DD-MM-YYYY
$formattedDate1 = $date1->format('d-m-Y');

// Format the time to hh:mm AM/PM
$formattedTime1 = $date1->format('h:i A');

// Format the date to DD-MM-YYYY
$formattedDate2 = $date2->format('d-m-Y');

// Format the time to hh:mm AM/PM
$formattedTime2 = $date2->format('h:i A');

// Combine the formatted date and time
$start_formattedDateTime = $formattedDate1 . ' ' . $formattedTime1;

$end_formattedDateTime = $formattedDate2 . ' ' . $formattedTime2;

// Calculate the difference in hours
$interval = $date1->diff($date2);

$totalHours = $interval->days * 24 + $interval->h;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the form submission
    if (isset($_POST['booking_status'])) {
    $booking_id = $_POST["booking_id"];
    
    $booking_status = $_POST["booking_status"];

   
    // Update the status in the backend or database
    // Example: $status = ($_POST['status'] == 'on') ? 'verified' : 'not_verified'; // Save $status to the backend or database
    $sql = " UPDATE booking SET booking_status = '$booking_status' WHERE id = '$booking_id'";
    //echo $sql;die;
    if ($db->query($sql) === true) {
        echo "<script>alert('Booking status updated successfully'); window.location.href = './bookings.php';</script>";
      } else {
        echo "<script>alert('Unable to update status');</script>";
      }

    }
}


?>
<div class="container-fluid">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"><a href="">Dashboard</a> / View Booking </li>
    </ol>
    <div class="card">
        <div class="card-header">
            Booking details
        </div>
        <div class="card-body">
            <table class="table tabled-bordered">
                <tr>
                    <td><strong>Booking Status:</strong></td>
                    <?php $data->booking_status == 'Booked' ? $labelColor = 'info' : $labelColor = 'success' ?>
                    <td><span class="badge badge-<?php echo $labelColor ?>"><?php echo $data->booking_status ?></span>
                    </td>

                    <td><strong>Booking Id: </strong><?php echo htmlspecialchars($data->booking_id) ?></td>
                    <td><strong>Customer Name: </strong><?php echo htmlspecialchars($data->name) ?></td>

                </tr>
                <tr>
                    <td><strong>Trip Start Date.: </strong><?php echo htmlspecialchars($data->start_date) ?></td>
                    <td><strong>Trip End Date: </strong><?php echo htmlspecialchars($data->end_date) ?></td>
                    <td><strong>Total Hours: </strong><?php echo htmlspecialchars($totalHours).'/hrs' ?></td>
                    <td><strong>Total Price: </strong><?php echo htmlspecialchars($data->total_price) ?></td>

                </tr>

                <tr>
                    <td><strong>Payment Id.: </strong><?php echo htmlspecialchars($data->payment_id) ?></td>

                    <?php $data->payment_status == 'sucess' ? $labelColor = 'info' : $labelColor = 'success' ?>
                    <td>Payment Status: <span
                            class="badge badge-<?php echo $labelColor ?>"><?php echo $data->payment_status ?></span>
                    </td>

                </tr>

            </table>
        </div>
    </div>
    <div class="card mt-2">
        <div class="card-header">
            Car details
        </div>
        <div class="card-body">
            <table class="table tabled-bordered">
                <tr>

                    <td><strong>Car Name: </strong><?php echo htmlspecialchars($data->car_name) ?></td>
                    <td><strong>Car Body Type: </strong><?php echo htmlspecialchars($data->car_body_type) ?></td>
                    <td><strong>Price Per Hour: </strong><?php echo htmlspecialchars($data->price_per_hour) ?></td>

                </tr>
                <tr>
                    <td><strong>Km Driven.: </strong><?php echo htmlspecialchars($data->km_driven) ?></td>
                    <td><strong>Registration No: </strong><?php echo htmlspecialchars($data->reg_no) ?></td>

                </tr>
            </table>
        </div>
    </div>
    <div class="card m-5">
						<div class="card-body">
                    <form method="POST" action="view-booking.php">
                    <div class="form-row">
									<div class="form-group col-lg-12">
										<h6>Status</h6>
									</div>
					</div>
                        <input type="hidden" name="booking_id" value="<?php echo $id ?>">
                        <div class="form-row">
									<div class="form-group col-lg-8">
                        <select class="form-control" name="booking_status">
                            <option value="Booked" <?php if ($data->booking_status === 'Booked')
                                echo 'selected'; ?>>
                                Booked</option>
                            <option value="Live" <?php if ($data->booking_status === 'Live')
                                echo 'selected'; ?>>Live
                            </option>
                            <option value="Completed" <?php if ($data->booking_status === 'Completed')
                                echo 'selected'; ?>>Completed</option>
                        </select>
                        </div>
             
                        <div class="form-group col-lg-4">
                    <button type="submit" class="btn btn-primary ">Submit</button>
                    </div>
</div> 
                </form>
            </div>
        </div>


</div>

<?php include './footer.php' ?>