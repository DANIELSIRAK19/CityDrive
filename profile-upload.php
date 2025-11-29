<?php 
include './header.php';
require_once './src/Database.php';
require_once './src/Session.php';
session_start();

if (!isset($_SESSION['isLogged']) || $_SESSION['isLogged'] == false) {
    header("Location: login.php");
    exit();
}

$user = Session::get('user');
$customer_id = $user->id;

$sql = "SELECT * FROM customers WHERE id = '$customer_id'";
$res = $db->query($sql);
$profile_details = $res->fetch_object();

// If already submitted → send to status page
if ($profile_details->is_verified === "Pending" || $profile_details->is_verified === "Verified") {
    header("Location: verification-status.php");
    exit();
}

?>
<main id="main">
    <section class="section-bg">
        <div class="container">
            
            <div class="row" style="padding-top: 100px; padding-bottom:100px">

                <div class="col-lg-12 my-2">
                    <div class="card w-100">
                        <div class="card-body">
                            <h4>Upload Documents for Verification</h4>
                            <p>Please fill out details below</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <form id="formProfile" method="post" enctype="multipart/form-data">
                                
                                <input type="hidden" name="id" value="<?php echo $user->id; ?>">

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label>Name</label>
                                        <input type="text" class="form-control" name="name" 
                                                value="<?php echo $user->name; ?>">
                                        <small id="nameError" class="text-danger"></small>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="email" 
                                                value="<?php echo $user->email; ?>">
                                        <small id="emailError" class="text-danger"></small>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Phone</label>
                                        <input type="text" class="form-control" name="phone" 
                                                value="<?php echo $user->phone; ?>">
                                        <small id="phoneError" class="text-danger"></small>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label>Full Address</label>
                                        <textarea class="form-control" name="address" 
                                                  placeholder="Address, Zip-code, City, Country"><?php 
                                                  echo $profile_details->address ?? ""; ?></textarea>
                                        <small id="addressError" class="text-danger"></small>
                                    </div>
                                </div>

                                <div class="form-row">

                                    <div class="form-group col-md-4">
                                        <label>Driving License Number</label>
                                        <input type="text" class="form-control" name="driving-license-no">
                                        <small id="dlNoError" class="text-danger"></small>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>DL Front Image</label>
                                        <input type="file" class="form-control" name="driving-license-image1">
                                        <small id="drivingLicenseImage1Error" class="text-danger"></small>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>DL Back Image</label>
                                        <input type="file" class="form-control" name="driving-license-image2">
                                        <small id="drivingLicenseImage2Error" class="text-danger"></small>
                                    </div>

                                </div>

                                <div class="form-row">

                                    <div class="form-group col-md-6">
                                        <label>Proof of Address Type</label>
                                        <select class="form-control" name="address-proof">
                                            <option value="">-- Select Address Proof --</option>
                                            <option value="house_ownership">House Ownership</option>
                                            <option value="rental_agreement">Rental Agreement</option>
                                            <option value="other">Other Proof</option>
                                        </select>
                                        <small id="addressProofError" class="text-danger"></small>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Document Identification Number</label>
                                        <input type="text" class="form-control" name="address-proof-no">
                                        <small id="addressProofNoError" class="text-danger"></small>
                                    </div>

                                    <div class="form-group col-md-4 mt-3">
                                        <label>Image of Proof of Address</label>
                                        <input type="file" class="form-control" name="address-proof-image">
                                        <small id="addressProofImageError" class="text-danger"></small>
                                    </div>

                                </div>

                                <div class="form-row text-right">
                                    <div class="form-group col-lg-4 col-sm-12 offset-lg-8">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            Submit
                                        </button>
                                    </div>
                                </div>

                            </form>

                            <div id="msg"></div>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>
</main>

<?php include './footer.php'; ?>

<script>
const formProfile = document.querySelector('#formProfile');

const nameError = document.querySelector('#nameError');
const emailError = document.querySelector('#emailError');
const phoneError = document.querySelector('#phoneError');
const addressError = document.querySelector('#addressError');
const dlNoError = document.querySelector('#dlNoError');
const drivingLicenseImage1Error = document.querySelector('#drivingLicenseImage1Error');
const drivingLicenseImage2Error = document.querySelector('#drivingLicenseImage2Error');
const addressProofError = document.querySelector('#addressProofError');
const addressProofNoError = document.querySelector('#addressProofNoError');
const addressProofImageError = document.querySelector('#addressProofImageError');

function clearErrors() {
    nameError.textContent = "";
    emailError.textContent = "";
    phoneError.textContent = "";
    addressError.textContent = "";
    dlNoError.textContent = "";
    drivingLicenseImage1Error.textContent = "";
    drivingLicenseImage2Error.textContent = "";
    addressProofError.textContent = "";
    addressProofNoError.textContent = "";
    addressProofImageError.textContent = "";
}

formProfile.addEventListener("submit", function(e){
    e.preventDefault();
    clearErrors();
    let data = new FormData(this);

    fetch("./src/profile-update.php", {
        method: "POST",
        body: data
    })
    .then(res => res.json()
        .then(json => {

            if (res.status === 200) {
                document.querySelector("#msg").innerHTML =
                    '<div class="alert alert-success">' + json.msg + '</div>';

                if (json.redirect) {
                    setTimeout(() => {
                        window.location.href = json.redirect;
                    }, 1200);
                }

            } else if (res.status === 400) {
                // validation errors
                if (json.errors) {
                    if (json.errors.name) nameError.textContent = json.errors.name;
                    if (json.errors.email) emailError.textContent = json.errors.email;
                    if (json.errors.phone) phoneError.textContent = json.errors.phone;
                    if (json.errors.address) addressError.textContent = json.errors.address;
                    if (json.errors.driving_license_no) dlNoError.textContent = json.errors.driving_license_no;
                    if (json.errors.driving_license_image1) drivingLicenseImage1Error.textContent = json.errors.driving_license_image1;
                    if (json.errors.driving_license_image2) drivingLicenseImage2Error.textContent = json.errors.driving_license_image2;
                    if (json.errors.address_proof_no) addressProofNoError.textContent = json.errors.address_proof_no;
                    if (json.errors.address_proof_image) addressProofImageError.textContent = json.errors.address_proof_image;
                }

            } else {
                document.querySelector("#msg").innerHTML =
                    '<div class="alert alert-danger">Something went wrong.</div>';
            }
        })
    )
    .catch(err => console.error(err));
});
</script>
