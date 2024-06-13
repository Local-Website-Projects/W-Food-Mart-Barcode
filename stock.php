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

$u = '';

if (isset($_GET['update'])) {
    $check_cat = $db_handle->runQuery("select * from category where category_id = '{$_GET['update']}'");
    if ($check_cat[0]['status'] == '1') {
        $u = 0;
    } else {
        $u = 1;
    }
    $update_cat_status = $db_handle->insertQuery("update category set status = '$u' where category_id = '{$_GET['update']}'");
    if ($update_cat_status) {
        echo "
        <script>
        document.cookie = 'alert = 4;';
        window.location.href='Category';
</script>
        ";
    } else {
        echo "
        <script>
        document.cookie = 'alert = 5;';
        window.location.href='Category';
</script>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Food Mart - Stock</title>
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
            <?php
            if (isset($_GET['edit'])) {
                ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Stock<span class="badge bg-soft-success font-12">update</span>
                                </h4>
                            </div><!--end card-header-->
                            <div class="card-body">
                                <form action="Update" method="post">
                                    <?php
                                    $stock_data = $db_handle->runQuery("select * from primary_stock where p_stock_id = {$_GET['edit']}");
                                    ?>
                                    <input type="hidden" value="<?php echo $_GET['edit']; ?>" name="p_stock_id">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="floatingInput"
                                               value="<?php echo $stock_data[0]['quantity']; ?>" name="quantity"
                                               required>
                                        <label for="floatingInput">Stock In Quantity</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floatingInput"
                                               value="<?php echo $stock_data[0]['buying_cost']; ?>" name="buying_cost"
                                               required>
                                        <label for="floatingInput">Stock In Price</label>
                                    </div>
                                    <button type="submit" name="edit_primary_stock" class="btn btn-primary">Edit
                                        Stock Data
                                    </button>
                                </form>

                            </div><!--end card-body-->
                        </div>
                    </div>
                </div>
                <?php

            } elseif (isset($_GET['transfer'])) {
                ?>
                <form class="mb-5" action="Insert" method="post">
                    <?php
                    $inQuantity = $db_handle->runQuery("select * from primary_stock where p_stock_id = {$_GET['transfer']}");
                    $stock_in = $inQuantity[0]['quantity'];

                    $outQuantity = $db_handle->runQuery("select SUM(quantity) as q from shop_stock where stock_id = {$_GET['transfer']}");
                    if(count($outQuantity) > 0){
                        $remains = $stock_in - $outQuantity[0]['q'];
                    } else {
                        $remains = $stock_in;
                    }
                    ?>
                    <input type="hidden" value="<?php echo $_GET['transfer']; ?>" name="shop_stock_id">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="availableStock" value="<?php echo $remains;?>" required readonly>
                        <label for="availableStock">Stock Available for Transfer</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="transferQuantity" name="transfer_quantity" required>
                        <label for="transferQuantity">Transfer Quantity</label>
                        <span id="quantityError" style="color: red; display: none;">Transfer quantity cannot exceed available stock.</span>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="transferQuantity" name="selling_cost" required>
                        <label for="transferQuantity">Selling Cost</label>
                    </div>

                    <button type="submit" id="transferButton" name="transfer_primary_stock" class="btn btn-primary">Transfer To Shop</button>

                </form>
                <?php
            } else {
                ?>
                <div class="row mt-3">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Add Primary Stock</h4>
                            </div>
                            <div class="card-body">
                                <form action="Insert" method="post">
                                    <div class="form-floating mb-3">
                                        <label class="mb-3">Product Code</label>
                                        <select class="select2 form-control mb-3 custom-select" name="product_id"
                                                required
                                                style="width: 100%; height:36px;">
                                            <option disabled selected>Select Product</option>
                                            <?php
                                            $fetch_code = $db_handle->runQuery("select * from product where status != 0 order by product_name ASC");
                                            for ($i = 0; $i < count($fetch_code); $i++) {
                                                ?>
                                                <option value="<?php echo $fetch_code[$i]['product_id']; ?>"><?php echo $fetch_code[$i]['product_name']; ?> - <?php echo $fetch_code[$i]['variety'];?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="floatingInput"
                                               name="stock_in_quantity" required>
                                        <label for="floatingInput">Stock In Quantity</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floatingInput" name="purchase_price"
                                               required>
                                        <label for="floatingInput">Purchase Price</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" id="floatingInput" name="stock_in_date"
                                               required>
                                        <label for="floatingInput">Stock In Date</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" id="floatingInput" name="expire_date"
                                               required>
                                        <label for="floatingInput">Expire Date</label>
                                    </div>
                                    <button type="submit" name="add_primary_stock" class="btn btn-primary mt-3">Submit
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>

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
                                    <th>Stock In Quantity</th>
                                    <th>Transfer Quantity</th>
                                    <th>Remaining Stock</th>
                                    <th>Stock In Date</th>
                                    <th>Expire Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $fetch_stock = $db_handle->runQuery("SELECT * FROM `primary_stock`, `product` WHERE primary_stock.product_id = product.product_id");
                                $fetch_stock_no = $db_handle->numRows("SELECT * FROM `primary_stock`, `product` WHERE primary_stock.product_id = product.product_id");
                                if($fetch_stock_no > 0){
                                    for ($i = 0; $i < count($fetch_stock); $i++) {
                                        ?>
                                        <tr>
                                            <td><?php echo $i + 1; ?></td>
                                            <td><?php echo $fetch_stock[$i]['product_name']; ?></td>
                                            <td><?php echo $fetch_stock[$i]['quantity']; ?></td>
                                            <?php
                                            $transfer = $db_handle->runQuery("select SUM(quantity) as qty from shop_stock where stock_id = {$fetch_stock[$i]['p_stock_id']}");
                                            if($transfer[0]['qty'] != null){
                                                $t = $transfer[0]['qty'];
                                            } else {
                                                $t = 0;
                                            }
                                            ?>
                                            <td><?php echo $t;?></td>
                                            <td><?php echo $fetch_stock[$i]['quantity'] - $t;?></td>
                                            <td><?php $dateString = $fetch_stock[$i]['date'];
                                                $timestamp = strtotime($dateString);
                                                $formattedDate = date('d M, Y', $timestamp);
                                                echo $formattedDate; ?></td>
                                            <td><?php $dateString = $fetch_stock[$i]['expire_date'];
                                                $timestamp = strtotime($dateString);
                                                $formattedDate = date('d M, Y', $timestamp);
                                                echo $formattedDate; ?></td>
                                            <td>
                                                <?php
                                                // Get the current date
                                                $current_date = new DateTime();

                                                // Create a DateTime object for the expiry date
                                                $expiry_date = new DateTime($fetch_stock[$i]['expire_date']);

                                                // Calculate the difference between the current date and the expiry date
                                                $interval = $current_date->diff($expiry_date);

                                                // Get the difference in days
                                                $days_difference = $interval->days;

                                                // If expiry date is in the past, make the difference negative
                                                if ($expiry_date < $current_date) {
                                                    $days_difference = -$days_difference;
                                                }
                                                if($days_difference <= 30 && $days_difference > 0){
                                                    ?>
                                                    <span class="badge badge-boxed  badge-outline-warning">Almost Expired</span>
                                                    <?php
                                                } elseif ($days_difference <= 0){
                                                    ?>
                                                    <span class="badge badge-boxed  badge-outline-danger">Expired</span>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <span class="badge badge-boxed  badge-outline-success">Valid</span>
                                                    <?php
                                                }

                                                ?>
                                            </td>
                                            <td class="text-right">
                                                <a href="Stock?edit=<?php echo $fetch_stock[$i]['p_stock_id']; ?>"
                                                   class="btn btn-sm btn-soft-success btn-circle me-2"><i
                                                            class="dripicons-pencil"></i></a>
                                                <a href="Stock?transfer=<?php echo $fetch_stock[$i]['p_stock_id']; ?>"
                                                   class="btn btn-sm btn-soft-success btn-circle me-2"><i
                                                            class="dripicons-ticket"></i></a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
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

<script>
    document.getElementById('transferQuantity').addEventListener('input', function() {
        var availableStock = parseInt(document.getElementById('availableStock').value);
        var transferQuantity = parseInt(document.getElementById('transferQuantity').value);

        if (transferQuantity > availableStock) {
            document.getElementById('quantityError').style.display = 'block';
            document.getElementById('transferButton').disabled = true;
        } else {
            document.getElementById('quantityError').style.display = 'none';
            document.getElementById('transferButton').disabled = false;
        }
    });
</script>

</body>

</html>