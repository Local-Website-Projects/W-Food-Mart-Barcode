<?php
session_start();
require_once('config/dbConfig.php');
$db_handle = new DBController();
date_default_timezone_set("Asia/Dhaka");
$inserted_at = date("Y-m-d H:i:s");
$today = date("m-d-Y");

if(!isset($_SESSION['admin'])){
    echo "
    <script>
    window.location.href = 'Login';
    </script>
    ";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Food Mart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php include('include/css.php'); ?>
</head>

<body class="">
<!-- Left Sidenav -->
<?php include('include/leftSideBar.php'); ?>
<!-- end left-sidenav-->


<div class="page-wrapper">
    <!-- Top Bar Start -->
    <?php include('include/topBar.php'); ?>
    <!-- Top Bar End -->

    <!-- Page Content-->
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="row">
                            <div class="col">
                                <h4 class="page-title">Dashboard</h4>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">FoodMart</a></li>
                                </ol>
                            </div><!--end col-->
                        </div><!--end row-->
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div><!--end row-->
            <!-- end page title end breadcrumb -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="row justify-content-center">
                        <div class="col-md-6 col-lg-3">
                            <div class="card report-card">
                                <div class="card-body">
                                    <div class="row d-flex justify-content-center">
                                        <div class="col">
                                            <p class="text-dark mb-0 fw-semibold">Daily Sell</p>
                                            <h3 class="m-0">BDT <?php
                                                $fetch_today_sell = $db_handle->runQuery("SELECT sum(grand_total) as total FROM `invoice_data` WHERE DATE(inserted_at) = CURDATE();");
                                                if($fetch_today_sell[0]['total'] != NULL)
                                                    echo $fetch_today_sell[0]['total'];
                                                else
                                                    echo '00';
                                                ?>
                                                </h3>
                                            <p class="mb-0 text-truncate text-muted"><span class="text-success"><i
                                                            class="mdi mdi-trending-up"></i></span> New Sells
                                                Today</p>
                                        </div>
                                        <div class="col-auto align-self-center">
                                            <div class="report-main-icon bg-light-alt">
                                                <i data-feather="users"
                                                   class="align-self-center text-muted icon-sm"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div> <!--end col-->
                        <div class="col-md-6 col-lg-3">
                            <div class="card report-card">
                                <div class="card-body">
                                    <div class="row d-flex justify-content-center">
                                        <div class="col">
                                            <p class="text-dark mb-0 fw-semibold">Monthly Sell</p>
                                            <h3 class="m-0">BDT <?php
                                                $fetch_monthly_sell = $db_handle->runQuery("SELECT sum(grand_total) as total FROM `invoice_data` WHERE YEAR(inserted_at) = YEAR(CURDATE()) AND MONTH(inserted_at) = MONTH(CURDATE())");
                                                if($fetch_monthly_sell[0]['total'] != NULL)
                                                    echo $fetch_monthly_sell[0]['total'];
                                                else
                                                    echo '00';
                                                ?> </h3>
                                            <p class="mb-0 text-truncate text-muted"><span class="text-success"><i
                                                            class="mdi mdi-trending-up"></i></span> Current Month
                                                Sell</p>
                                        </div>
                                        <div class="col-auto align-self-center">
                                            <div class="report-main-icon bg-light-alt">
                                                <i data-feather="clock"
                                                   class="align-self-center text-muted icon-sm"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div> <!--end col-->
                        <div class="col-md-6 col-lg-3">
                            <div class="card report-card">
                                <div class="card-body">
                                    <div class="row d-flex justify-content-center">
                                        <div class="col">
                                            <p class="text-dark mb-0 fw-semibold">Yearly Sell</p>
                                            <?php
                                            $fetch_yearly_sell = $db_handle->runQuery("SELECT sum(grand_total) as total FROM `invoice_data` WHERE YEAR(inserted_at) = YEAR(CURDATE())");
                                            ?>
                                            <h3 class="m-0">BDT <?php echo $fetch_yearly_sell[0]['total'];?></h3>
                                            <p class="mb-0 text-truncate text-muted"> Yearly Sell</p>
                                        </div>
                                        <div class="col-auto align-self-center">
                                            <div class="report-main-icon bg-light-alt">
                                                <i data-feather="briefcase"
                                                   class="align-self-center text-muted icon-sm"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card report-card">
                                <div class="card-body">
                                    <div class="row d-flex justify-content-center">
                                        <div class="col">
                                            <p class="text-dark mb-0 fw-semibold">Total Sell</p>
                                            <?php
                                            $total_sell = $db_handle->runQuery("SELECT sum(grand_total) as total FROM `invoice_data`");
                                            ?>
                                            <h3 class="m-0">BDT <?php echo $total_sell[0]['total'];?></h3>
                                            <p class="mb-0 text-truncate text-muted">Overall Sell</p>
                                        </div>
                                        <div class="col-auto align-self-center">
                                            <div class="report-main-icon bg-light-alt">
                                                <i data-feather="activity"
                                                   class="align-self-center text-muted icon-sm"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div> <!--end col-->
                        <!--end col-->
                    </div><!--end row-->
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h4 class="card-title">Audience Overview</h4>
                                </div><!--end col-->
                            </div>  <!--end row-->
                        </div><!--end card-header-->
                        <div class="card-body">
                            <div class="">
                                <div id="ana_dash_1" class="apex-charts"></div>
                            </div>
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div><!--end col-->
            </div><!--end row-->
        </div>
        <!-- container -->

        <?php include ('include/footer.php');?>
        <!--end footer-->
    </div>
    <!-- end page content -->
</div>
<!-- end page-wrapper -->


<!-- jQuery  -->
<?php include('include/js.php'); ?>

<?php
$fetch_monthly_sell_data = $db_handle->runQuery("SELECT MONTH(inserted_at) AS month, SUM(grand_total) AS total_grand_total FROM invoice_data WHERE YEAR(inserted_at) = YEAR(CURRENT_DATE()) GROUP BY MONTH(inserted_at) ORDER BY MONTH(inserted_at)");

// Initialize an array with 12 elements all set to 0
$sell_data = array_fill(0, 12, 0); // Note: Changed to 0-based index
$purchase_data = array_fill(0, 12, 0); // Note: Changed to 0-based index

// Loop through the query results and update the sell_data array
foreach($fetch_monthly_sell_data as $data) {
    $month = (int)$data['month'] - 1; // Convert 1-based month to 0-based index
    $total_grand_total = (float)$data['total_grand_total'];
    $sell_data[$month] = $total_grand_total;
}

// Convert the PHP array to JSON
$sell_data_json = json_encode($sell_data);


$fetch_purchase_data = $db_handle->runQuery("SELECT MONTH(inserted_at) AS month, SUM(quantity * buying_cost) AS total_purchase_cost FROM primary_stock WHERE YEAR(inserted_at) = YEAR(CURRENT_DATE()) GROUP BY MONTH(inserted_at) ORDER BY MONTH(inserted_at);");

// Initialize an array with 12 elements all set to 0
$purchase_data = array_fill(0, 12, 0); // Note: Changed to 0-based index

// Loop through the query results and update the purchase_data array
foreach($fetch_purchase_data as $data) {
    $month = (int)$data['month'] - 1; // Convert 1-based month to 0-based index
    $total_purchase_cost = (float)$data['total_purchase_cost'];
    $purchase_data[$month] = $total_purchase_cost;
}

// Convert the PHP array to JSON
$purchase_data_json = json_encode($purchase_data);
?>

<script>
    // Get the PHP-generated data
    var sellData = <?php echo $sell_data_json; ?>;
    var purchaseData = <?php echo $purchase_data_json; ?>;

    // Define the chart options using the PHP data
    var options1 = {
        chart: {height: 320, type: "area", stacked: !0, toolbar: {show: !1, autoSelected: "zoom"}},
        colors: ["#2a77f4", "#a5c2f1"],
        dataLabels: {enabled: !1},
        stroke: {curve: "smooth", width: [1.5, 1.5], dashArray: [0, 4], lineCap: "round"},
        grid: {padding: {left: 0, right: 0}, strokeDashArray: 3},
        markers: {size: 0, hover: {size: 0}},
        series: [
            {name: "Sell Data", data: sellData}, // Use the PHP data here
            {name: "Purchase Data", data: purchaseData}
        ],
        xaxis: {
            type: "month",
            categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            axisBorder: {show: !0},
            axisTicks: {show: !0}
        },
        yaxis: {
            min: 0 // Ensure the y-axis starts from 0
        },
        fill: {type: "gradient", gradient: {shadeIntensity: 1, opacityFrom: .4, opacityTo: .3, stops: [0, 90, 100]}},
        tooltip: {x: {format: "dd/MM/yy HH:mm"}},
        legend: {position: "top", horizontalAlign: "right"}
    };

    // Render the first chart
    var chart1 = new ApexCharts(document.querySelector("#ana_dash_1"), options1);
    chart1.render();

    // Define the second chart options
    var options2 = {
        chart: {height: 270, type: "donut"},
        plotOptions: {pie: {donut: {size: "85%"}}},
        dataLabels: {enabled: !1},
        stroke: {show: !0, width: 2, colors: ["transparent"]},
        series: sellData.slice(0, 3), // Use the first three months for the donut chart
        legend: {
            show: !0,
            position: "bottom",
            horizontalAlign: "center",
            verticalAlign: "middle",
            floating: !1,
            fontSize: "13px",
            offsetX: 0,
            offsetY: 0
        },
        labels: ["Jan", "Feb", "Mar"],
        colors: ["#2a76f4", "rgba(42, 118, 244, .5)", "rgba(42, 118, 244, .18)"],
        responsive: [{
            breakpoint: 600,
            options: {plotOptions: {donut: {customScale: .2}}, chart: {height: 240}, legend: {show: !1}}
        }],
        tooltip: {
            y: {
                formatter: function (o) {
                    return o + " %"
                }
            }
        }
    };

    // Render the second chart
    var chart2 = new ApexCharts(document.querySelector("#ana_device"), options2);
    chart2.render();
</script>

</body>

</html>