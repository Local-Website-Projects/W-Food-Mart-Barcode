<?php
session_start();
require_once('config/dbConfig.php');
$db_handle = new DBController();
date_default_timezone_set("Asia/Dhaka");
$inserted_at = date("Y-m-d H:i:s");

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
    <title>Invoice Report - Category</title>
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
                                <h4 class="page-title">Invoice Report</h4>
                            </div><!--end col-->
                        </div><!--end row-->
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div>
            <!--end row-->
            <!-- end page title end breadcrumb -->

            <!--add employee modals-->
            <div class="modal fade" id="exampleModalLogin" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalDefaultLogin" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title m-0" id="exampleModalDefaultLogin">Add Category</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div><!--end modal-header-->
                        <div class="modal-body">
                            <div class="card-body p-0">
                                <form action="Insert" method="post">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floatingInput"
                                               placeholder="Category Name" name="category_name" required>
                                        <label for="floatingInput">Category Name</label>
                                    </div>
                                    <button type="submit" name="add_category" class="btn btn-primary">Add Category
                                    </button>
                                </form>
                                <!-- Tab panes -->
                            </div><!--end card-body-->
                        </div><!--end modal-body-->

                    </div><!--end modal-content-->
                </div><!--end modal-dialog-->
            </div>
            <!--end modal-->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Invoice List</h4>
                        </div><!--end card-header-->
                        <div class="card-body">
                            <table id="datatable-buttons"
                                   class="table table-striped table-bordered dt-responsive nowrap"
                                   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <th>Invoice ID</th>
                                    <th>Customer Name</th>
                                    <th>Purchase Amount</th>
                                    <th>Date</th>
                                    <th>View</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $fetch_invoice_data = $db_handle->runQuery("SELECT * FROM `invoice_data` order by id desc");

                                for ($i = 0; $i < count($fetch_invoice_data); $i++) {
                                    ?>
                                    <tr>
                                        <td><?php echo $i + 1; ?></td>
                                        <td>INV-<?php echo $fetch_invoice_data[$i]['invoice_id']; ?></td>
                                        <td><?php
                                            $fetch_customer = $db_handle->runQuery("select customer_name from customer_data where customer_id = {$fetch_invoice_data[$i]['customer_id']}");
                                            $fetch_customer_no = $db_handle->numRows("select customer_name from customer_data where customer_id = {$fetch_invoice_data[$i]['customer_id']}");
                                            if($fetch_customer_no > 0){
                                                echo $fetch_customer[0]['customer_name'];
                                            } else {
                                                echo "N/A";
                                            }
                                            ?></td>
                                        <td><?php echo $fetch_invoice_data[$i]['grand_total']; ?></td>
                                        <td><?php $inserted_at = $fetch_invoice_data[$i]['inserted_at']; // Assuming this is a datetime string
                                            $date = new DateTime($inserted_at);
                                            $formattedDate = $date->format('d F, Y \a\t h:i a'); // 'd' for day, 'F' for full month name, 'Y' for year, 'h' for hour, 'i' for minutes, 'a' for am/pm
                                            echo $formattedDate; ?></td>
                                        <td><a href="print_invoice.php?id=<?php echo $fetch_invoice_data[$i]['invoice_id'];?>" target="_blank"><i class="las la-eye text-secondary font-16"></i></a></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>


        </div><!-- container -->

        <?php include('include/footer.php'); ?>
        <!--end footer-->
    </div>
    <!-- end page content -->
</div>
<!-- end page-wrapper -->


<!-- jQuery  -->
<?php include('include/js.php'); ?>

</body>

</html>