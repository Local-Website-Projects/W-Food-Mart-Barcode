<?php
session_start();
require_once('config/dbConfig.php');
$db_handle = new DBController();
date_default_timezone_set("Asia/Dhaka");
$inserted_at = date("Y-m-d H:i:s");

if (!isset($_SESSION['admin'])) {
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
    <title>Food Mart - Primary Stock Status</title>
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
                                <h4 class="page-title">Stock</h4>
                            </div><!--end col-->
                        </div><!--end row-->
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div>
            <!--end row-->
            <!-- end page title end breadcrumb -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Primary Stock Data</h4>
                        </div><!--end card-header-->
                        <div class="card-body">
                            <table id="datatable-buttons"
                                   class="table table-striped table-bordered dt-responsive nowrap"
                                   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <th>Product Name</th>
                                    <th>Product Code</th>
                                    <th>Stock In Quantity</th>
                                    <th>Transfer Quantity</th>
                                    <th>Remaining Stock</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $fetch_stock = $db_handle->runQuery("SELECT * FROM `primary_stock`, `product` WHERE primary_stock.product_id = product.product_id order by primary_stock.p_stock_id DESC");

                                for ($i = 0; $i < count($fetch_stock); $i++) {
                                    ?>
                                    <tr>
                                        <td><?php echo $i + 1; ?></td>
                                        <td><?php echo $fetch_stock[$i]['product_name']; ?></td>
                                        <td><?php echo $fetch_stock[$i]['product_code']; ?></td>
                                        <?php
                                        $total = $db_handle->runQuery("select SUM(quantity) as qty from primary_stock WHERE p_stock_id = {$fetch_stock[$i]['p_stock_id']} group by product_id");
                                        ?>
                                        <td><?php echo $total[0]['qty']; ?></td>
                                        <?php
                                        $transfer = $db_handle->runQuery("select SUM(quantity) as qty from shop_stock where stock_id = {$fetch_stock[$i]['p_stock_id']}");
                                        if($transfer[0]['qty'] != null){
                                            $t = $transfer[0]['qty'];
                                        } else {
                                            $t = 0;
                                        }
                                        ?>
                                        <td><?php echo $t;?></td>
                                        <td><?php echo $total[0]['qty'] - $t;?></td>
                                        <td class="text-right">
                                            <?php
                                            if(($total[0]['qty'] - $t) > 10){
                                                ?>
                                                <span class="badge badge-boxed  badge-outline-success">In Stock</span>
                                                <?php
                                            } if (($total[0]['qty'] - $t) < 10 && ($total[0]['qty'] - $t) != 0) {
                                                ?>
                                                <span class="badge badge-boxed  badge-outline-warning">Almost Stock Out</span>
                                                <?php
                                            } if(($total[0]['qty'] - $t) == 0) {
                                                ?>
                                                <span class="badge badge-boxed  badge-outline-danger">Out of Stock</span>
                                                <?php
                                            }
                                            ?>
                                        </td>
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