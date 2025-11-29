<?php
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(400);
    echo 'Bad request';
    return;
}
require_once './Database.php';
require_once './dompdf/autoload.inc.php';
$db = Database::getInstance();
$id = $db->real_escape_string($_GET['id']);

$sql = "SELECT * FROM booking WHERE id = '$id'";
$res = $db->query($sql);
if ($res === false) {
    http_response_code(400);
    echo 'Bad request';
    return;
}
$data = $res->fetch_object();
$tz = new DateTimeZone('Asia/Kolkata');
$booingDate = new DateTime('Asia/Kolkata');
$appointmentDate = new DateTime($data->date, $tz);

$html = '<div>
    <table border="1" style="border-collapse: collapse; width:100%; padding: 40px">
        <tr>
            <td colspan="4" style="text-align: center; padding-top: 20px">
                <h1>KUDRAT COMPUTER CARE</h1>
                <p style="padding-bottom:20px">
                    M.G.Road, Near Raju Market,<br>
                    Rangia, Assam<br>
                    Pin- 781354<br>
                    Phone: 9707462026<br>
                    Email: k9707462026@gmail.com<br>
                    Website:http://www.kudratcomputercare.com<br>
                    Visiting-time: 9am to 4Pm
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: left; font-weight:bold; padding:10px">Date: ' . $booingDate->format('d-m-Y H:i:s') . '</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: left; font-weight:bold; padding:10px">Registration Number: #' . $appointmentDate->format('dmY') . '/' . $id . '</td>
            <td colspan="2" style="text-align: left; font-weight:bold; padding:10px">Appointment date: ' . $appointmentDate->format('d-m-Y') . '</td>
        </tr>
        <tr>
            <td colspan="4">
                <table style="padding: 20px">
                    <tr>
                        <td style="width:150px"><b>Name</b></td>
                        <td>' . $data->name . '</td>
                    </tr>
                    <tr>
                        <td><b>Gender</b></td>
                        <td>' . $data->gender . '</td>
                    </tr>
                    <tr>
                        <td><b>Phone</b></td>
                        <td>' . $data->phone . '</td>
                    </tr>
                    <tr>
                        <td><b>Date of Birth</b></td>
                        <td>' . $data->dob . '</td>
                    </tr>
                    <tr>
                        <td><b>Address</b></td>
                        <td>' . $data->address . '</td>
                    </tr>
                    <tr>
                        <td><b>City</b></td>
                        <td>' . $data->city . '</td>
                    </tr>
                    <tr>
                        <td><b>State</b></td>
                        <td>' . $data->state . '</td>
                    </tr>
                    <tr>
                        <td><b>Pin</b></td>
                        <td>7' . $data->zip . '</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <div style="position: fixed; bottom: 0; left: 0">
        <strong>Note:</strong> This is a computer generated receipt there for no need of any signature
    </div>
</div>';

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream(time());