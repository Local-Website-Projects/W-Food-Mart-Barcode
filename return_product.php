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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Food Mart - Return Products</title>
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
                                <h4 class="page-title">Return Products</h4>
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
                        <div class="card-body">
                            <form action="Insert" method="post">
                                <div class="form-floating mb-3">
                                    <div class="form-floating mb-3 mt-3">
                                        <label class="mb-3">Select Invoice</label>
                                        <select class="select2 form-control mb-3 custom-select"
                                                style="width: 100%; height:36px;">
                                            <option>Select</option>
                                            <?php
                                            $fetch_invoice_number = $db_handle->runQuery("select * from invoice_data");
                                            for ($i = 0; $i < count($fetch_invoice_number); $i++) {
                                                ?>
                                                <option value="<?php echo $fetch_invoice_number[$i]['invoice_id']; ?>"><?php echo $fetch_invoice_number[$i]['invoice_id']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-floating mb-3 mt-3">
                                        <select class="form-select" id="inputGroupSelect04"
                                                aria-label="Example select with button addon" name="product_cat"
                                                required>
                                            <option selected disabled>Choose Product</option>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="form-floating mb-3 mt-3">
                                        <input type="text" class="form-control" id="floatingInput"
                                               placeholder="Product Quantity" name="product_quantity" required>
                                        <label for="floatingInput">Product Quantity</label>
                                    </div>
                                    <div class="form-floating mb-3 mt-3">
                                        <input type="text" class="form-control" id="floatingInput"
                                               placeholder="Product Price" name="product_price" required>
                                        <label for="floatingInput">Price</label>
                                    </div>
                                </div>
                                <button type="submit" name="add_product" id="add_product_button"
                                        class="btn btn-primary">Add Product
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Product List</h4>
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
                                    <th>Category Name</th>
                                    <th>Varieties</th>
                                    <th>Company Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $fetch_product = $db_handle->runQuery("select * from category,product where category.category_id = product.cat_id order by product_id DESC");

                                for ($i = 0; $i < count($fetch_product); $i++) {
                                    ?>
                                    <tr>
                                        <td><?php echo $i + 1; ?></td>
                                        <td><?php echo $fetch_product[$i]['product_name']; ?></td>
                                        <td><?php echo $fetch_product[$i]['product_code']; ?></td>
                                        <td><?php echo $fetch_product[$i]['category_name']; ?></td>
                                        <td><?php echo $fetch_product[$i]['variety']; ?></td>
                                        <td><?php echo $fetch_product[$i]['company_name']; ?></td>
                                        <td>
                                            <?php
                                            if ($fetch_product[$i]['status'] == 0) {
                                                ?>
                                                <span class="badge badge-soft-danger">Deactive</span>
                                                <?php
                                            } elseif ($fetch_product[$i]['status'] == 1) {
                                                ?>
                                                <span class="badge badge-soft-success">Active</span>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td class="text-right">
                                            <a href="Product?edit=<?php echo $fetch_product[$i]['product_id']; ?>"
                                               class="btn btn-sm btn-soft-success btn-circle me-2"><i
                                                        class="dripicons-pencil"></i></a>
                                            <a href="Product?update=<?php echo $fetch_product[$i]['product_id']; ?>"
                                               class="btn btn-sm btn-soft-danger btn-circle"><i class="dripicons-anchor"
                                                                                                aria-hidden="true"></i></a>
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

<script>
        $(document).ready(function() {
        // Event listener for the first select field
        $('.select2').change(function() {
            var invoiceId = $(this).val();

            // Perform AJAX request to fetch data based on the selected invoice ID
            $.ajax({
                type: 'POST',
                url: 'fetch_product_data.php', // Change this to your PHP script URL
                data: {invoiceId: invoiceId},
                success: function(response) {
                    var data = JSON.parse(response);
                    var productCatSelect = $('#inputGroupSelect04');

                    // Clear existing options
                    productCatSelect.empty();

                    // Append default option
                    productCatSelect.append('<option selected disabled>Choose Product</option>');

                    // Append new options from the response
                    $.each(data, function(index, product) {
                        productCatSelect.append('<option value="' + product.product_id + '">' + product.product_name + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status + error);
                }
            });
        });
    });
</script>

</body>

</html>