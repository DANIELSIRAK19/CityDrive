<?php
session_start();
ini_set('display_errors', 0);

include './header.php';
require_once './src/Database.php';

/* Fetch car brands */
$sql = "SELECT * FROM brands ORDER BY id DESC";
$res = $db->query($sql);
$brands = [];
while ($row = $res->fetch_object()) {
    $brands[] = $row;
}
?>

<main id="main">
<section class="section-bg">

<!-- HERO -->
<div class="container-fluid pt-5">
    <header class="section-header hero-banner"></header>
</div>

<!-- MAIN -->
<div class="container py-5">

<!-- ===================== DATE FILTERS ===================== -->
<div class="row mb-4">

    <!-- Start Date -->
    <div class="col-12 col-md-4 mb-3">
        <label for="start">Pickup Date:</label>
        <div class="input-group date" id="startPicker" data-target-input="nearest">
            <input type="text" class="form-control datetimepicker-input"
                   id="start" data-target="#startPicker"
                   placeholder="Select start date and time">
            <div class="input-group-append" data-target="#startPicker" data-toggle="datetimepicker">
                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
            </div>
        </div>
    </div>

    <!-- End Date -->
    <div class="col-12 col-md-4 mb-3">
        <label for="end">Drop Date:</label>
        <div class="input-group date" id="endPicker" data-target-input="nearest">
            <input type="text" class="form-control datetimepicker-input"
                   id="end" data-target="#endPicker"
                   placeholder="Select end date and time">
            <div class="input-group-append" data-target="#endPicker" data-toggle="datetimepicker">
                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
            </div>
        </div>
    </div>

    <!-- Button -->
    <div class="col-12 col-md-4 d-flex align-items-end mb-3">
        <button id="calculateDuration" class="btn btn-primary w-100">Apply Date</button>
    </div>

</div>

<!-- ===================== SORT + VIEW MODE ===================== -->
<!-- ===================== SORT + VIEW MODE ===================== -->
<div class="d-flex flex-wrap justify-content-end align-items-center mb-3">

    <select id="sortPrice" class="form-control w-auto mb-2 mr-2">
        <option value="asc">Low to High</option>
        <option value="desc">High to Low</option>
    </select>

    <div class="btn-group mb-2">
        <a href="#" class="btn btn-outline-secondary" id="listViewBtn">
            <i class="fa fa-bars"></i>
        </a>
        <a href="#" class="btn btn-outline-secondary active" id="gridViewBtn">
            <i class="fa fa-th"></i>
        </a>
    </div>

</div>

<div class="row">

<!-- ===================== SIDEBAR ===================== -->
<aside class="col-12 col-md-3 mb-4">
    <div class="card">
        <article class="filter-group">
            <header class="card-header">
                <a data-toggle="collapse" data-target="#collapse_2">
                    <i class="icon-control fa fa-chevron-down"></i>
                    <h6 class="title">Brands</h6>
                </a>
            </header>

            <div class="collapse show" id="collapse_2">
                <div class="card-body">
                    <?php foreach ($brands as $brand): ?>
                        <label class="custom-control custom-checkbox">
                            <input type="checkbox"
                                   class="custom-control-input brand-checkbox"
                                   data-brand="<?= $brand->id ?>">
                            <div class="custom-control-label"><?= $brand->brand_name ?></div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </article>
    </div>
</aside>

<!-- ===================== MAIN CARS AREA ===================== -->
<main class="col-12 col-md-9">

    <div class="row grid-view" id="carsContainer"></div>

    <nav class="mt-4" aria-label="Page navigation">
        <ul class="pagination"></ul>
    </nav>

</main>

</div> <!-- END ROW -->
</div> <!-- END CONTAINER -->
</section>
</main>

<?php include './footer.php'; ?>


<!-- ===================== RESPONSIVE CSS ===================== -->
<style>
/* ============================
   CAR CARD BASE STYLE
============================ */
.car-card-wrapper {
    padding: 0 10px;
    margin-bottom: 20px;
}

.car-card {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    transition: .3s ease;
}

.car-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transform: translateY(-3px);
}

.car-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

.car-card-body {
    padding: 12px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.car-card-title {
    font-size: 17px;
    font-weight: 700;
    margin-bottom: 8px;
}

.car-card-specs {
    font-size: 14px;
    color: #555;
    margin-bottom: 10px;
}

.car-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid #eaeaea;
    padding-top: 10px;
}

.car-card-footer strong {
    color: #007bff;
}

/* =============================
   GRID VIEW (DEFAULT)
   mobile = 1 column
   tablet = 2 columns
   desktop = 3 columns
============================= */

.grid-view .car-card-wrapper {
    flex: 0 0 100%;
    max-width: 100%;
}

@media (min-width: 576px) {
    .grid-view .car-card-wrapper {
        flex: 0 0 50%;
        max-width: 50%;
    }
}

@media (min-width: 768px) {
    .grid-view .car-card-wrapper {
        flex: 0 0 33.33%;
        max-width: 33.33%;
    }
}

/* =============================
   LIST VIEW — FIXED & RESPONSIVE
============================= */

.list-view .car-card-wrapper {
    width: 100% !important;
    max-width: 100% !important;
    flex: 0 0 100%;
}

.list-view .car-card {
    flex-direction: row;
    height: 200px;
}

.list-view .car-card img {
    width: 230px;
    height: 100%;
    object-fit: cover;
    flex-shrink: 0;
}

/* body area expands fully */
.list-view .car-card-body {
    flex: 1;
    padding: 15px;
}

/* ====== MOBILE LIST FIX ====== */
@media (max-width: 576px) {
    .list-view .car-card {
        flex-direction: column;
        height: auto;
    }

    .list-view .car-card img {
        width: 100%;
        height: 180px;
    }
}

</style>


<!-- ===================== JAVASCRIPT ===================== -->
<script>
$(document).ready(function() {

    /* Datetime Picker */
    $('#startPicker, #endPicker').datetimepicker({
        format: 'YYYY-MM-DD HH:mm',
        minDate: new Date(),
        useCurrent: false
    });

    let loadedCars = [];
    let loadedStart = null;
    let loadedEnd = null;
    let currentPage = 1;
    let carsPerPage = 6;

    /* FETCH CARS */
    function fetchCars(start, end) {
        $.post('./check-data.php', { start, end }, function(res) {
            loadedCars = res;
            loadedStart = start;
            loadedEnd = end;
            currentPage = 1;
            renderPage();
        });
    }

    /* RENDER CARS */
    function renderPage() {
        $("#carsContainer").empty();

        let startIdx = (currentPage - 1) * carsPerPage;
        let carsToShow = loadedCars.slice(startIdx, startIdx + carsPerPage);

        carsToShow.forEach(car => {
            let extracted = car.image1.substring(car.image1.indexOf('/'));

            let card = `
            <div class="col-12 col-sm-6 col-md-4 mb-4 car-card-wrapper">
                <div class="car-card">
                <img src="http://localhost/car-rental${extracted}">
                    <div class="car-card-body">
                        <div class="car-card-title">${car.car_name}</div>
                        <div class="car-card-specs">
                            <span><i class="fa fa-cog"></i> ${car.transmission}</span><br>
                            <span><i class="fa fa-users"></i> ${car.seating_capacity} persons</span>
                        </div>
                        <div class="car-card-footer">
                            <strong>PLN ${car.price_per_hour}/hr</strong>
                            <a href="./car-details.php?id=${car.id}&start=${encodeURIComponent(loadedStart)}&end=${encodeURIComponent(loadedEnd)}"
                               class="btn btn-outline-primary btn-sm">View Car</a>
                        </div>
                    </div>
                </div>
            </div>`;

            $("#carsContainer").append(card);
        });

        renderPagination();
    }

    /* PAGINATION */
    function renderPagination() {
        let totalPages = Math.ceil(loadedCars.length / carsPerPage);
        let html = "";

        html += `<li class="page-item ${currentPage==1 ? "disabled" : ""}">
                    <a class="page-link" href="#" onclick="changePage(${currentPage-1})">Previous</a>
                 </li>`;

        for (let i=1; i<=totalPages; i++) {
            html += `<li class="page-item ${i==currentPage ? "active" : ""}">
                        <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                     </li>`;
        }

        html += `<li class="page-item ${currentPage==totalPages ? "disabled" : ""}">
                    <a class="page-link" href="#" onclick="changePage(${currentPage+1})">Next</a>
                 </li>`;

        $(".pagination").html(html);
    }

    window.changePage = function(page) {
        let totalPages = Math.ceil(loadedCars.length / carsPerPage);
        if (page < 1 || page > totalPages) return;
        currentPage = page;
        renderPage();
    };

    /* SORT */
    $("#sortPrice").change(function() {
        loadedCars.sort((a, b) =>
            $(this).val() == "asc"
                ? a.price_per_hour - b.price_per_hour
                : b.price_per_hour - a.price_per_hour
        );
        currentPage = 1;
        renderPage();
    });

    /* BRAND FILTER */
    $(".brand-checkbox").change(function() {
        let selected = $(".brand-checkbox:checked")
            .map(function(){ return $(this).data("brand"); })
            .get();

        if (selected.length === 0) {
            fetchCars(loadedStart, loadedEnd);
            return;
        }

        $.post("./fetch-cars.php", { brands: selected }, function(res) {
            loadedCars = res;
            currentPage = 1;
            renderPage();
        });
    });

    /* VIEW MODES */
    $("#gridViewBtn").click(function(e) {
        e.preventDefault();
        $("#carsContainer").removeClass("list-view").addClass("grid-view");
        $(this).addClass('active');
        $("#listViewBtn").removeClass('active');
    });
    $("#listViewBtn").click(function(e) {
        e.preventDefault();
        $("#carsContainer").removeClass("grid-view").addClass("list-view");
        $(this).addClass('active');
        $("#gridViewBtn").removeClass('active');
    });

    /* DEFAULT LOAD */
    if (!$('#start').val() || !$('#end').val()) {
        fetchCars(moment().toISOString(), moment().add(1,'day').toISOString());
    }

    /* APPLY BUTTON */
    $("#calculateDuration").click(function() {
        let start = moment($('#start').val(), 'YYYY-MM-DD HH:mm')
                        .utcOffset(0, true).toISOString();
        let end = moment($('#end').val(), 'YYYY-MM-DD HH:mm')
                        .utcOffset(0, true).toISOString();
        fetchCars(start, end);
    });

});
</script>
