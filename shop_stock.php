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

if(isset($_GET['accept'])){
    $accept_stock = $db_handle->insertQuery("UPDATE `shop_stock` SET `status`='1',`updated_at`='$inserted_at' WHERE `shop_stock_id` = {$_GET['accept']}");
    if($accept_stock){
        echo "
        <script>
        document.cookie = 'alert = 4;';
        window.location.href='Shop-Stock';
</script>
        ";
    } else {
        echo "
        <script>
        document.cookie = 'alert = 5;';
        window.location.href='Shop-Stock';
</script>
        ";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Food Mart - Shop Stock Status</title>
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
                            <h4 class="card-title">Shop Stock Data</h4>
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
                                    <th>Stock Transfer Quantity</th>
                                    <th>Sell Quantity</th>
                                    <th>Remaining Stock</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $fetch_shop_products = $db_handle->runQuery("SELECT shop_stock.quantity,shop_stock.sell_quantity, shop_stock_id, product_name,product_code,variety,shop_stock.status FROM `shop_stock`,`product`,`primary_stock` WHERE shop_stock.stock_id = primary_stock.p_stock_id and primary_stock.product_id = product.product_id order by shop_stock_id DESC");

                                for ($i = 0; $i < count($fetch_shop_products); $i++) {
                                    ?>
                                    <tr>
                                        <td><?php echo $i + 1; ?></td>
                                        <td><?php echo $fetch_shop_products[$i]['product_name']; ?></td>
                                        <td><?php echo $fetch_shop_products[$i]['product_code']; ?></td>
                                        <td><?php echo $fetch_shop_products[$i]['quantity']; ?></td>
                                        <td><?php echo $fetch_shop_products[$i]['sell_quantity']; ?></td>
                                        <td><?php echo $fetch_shop_products[$i]['quantity'] - $fetch_shop_products[$i]['sell_quantity'];?></td>
                                        <td class="text-right">
                                            <span class="badge badge-boxed  badge-outline-success">In Stock</span>
                                            <?php
/*                                            if(($total[0]['qty'] - $t) > 10){
                                                */?><!--
                                                <span class="badge badge-boxed  badge-outline-success">In Stock</span>
                                                <?php
/*                                            } elseif (($total[0]['qty'] - $t) < 10 && $total[0]['qty'] != 0) {
                                                */?>
                                                <span class="badge badge-boxed  badge-outline-warning">Almost Stock Out</span>
                                                <?php
/*                                            } else {
                                                */?>
                                                <span class="badge badge-boxed  badge-outline-danger">Stock Out</span>
                                                --><?php
/*                                            }
                                            */?>
                                        </td>
                                        <td>
                                            <?php
                                            if($fetch_shop_products[$i]['status'] == 0){
                                                ?>
                                                <a href="Shop-Stock?accept=<?php echo $fetch_shop_products[$i]['shop_stock_id'];?>"><i class="fas fa-check"></i></a>
                                                <?php
                                            } else {
                                                ?>
                                                <span class="badge badge-boxed  badge-outline-success">Accepted</span>
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