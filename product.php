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

$u = '';

if (isset($_GET['update'])) {
    $check_product = $db_handle->runQuery("select * from product where product_id = '{$_GET['update']}'");
    if($check_product[0]['status'] == '1') {
        $u = 0;
    }else {
        $u = 1;
    }
    $update_product_status = $db_handle->insertQuery("update product set status = '$u' where product_id = '{$_GET['update']}'");
    if($update_product_status) {
        echo "
        <script>
        document.cookie = 'alert = 4;';
        window.location.href='Product';
</script>
        ";
    } else {
        echo "
        <script>
        document.cookie = 'alert = 5;';
        window.location.href='Product';
</script>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Food Mart - Product</title>
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
                                <h4 class="page-title">Product</h4>
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
                            <h6 class="modal-title m-0" id="exampleModalDefaultLogin">Add Product</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div><!--end modal-header-->
                        <div class="modal-body">
                            <div class="card-body p-0">
                                <form action="Insert" method="post">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floatingInput"
                                               placeholder="Product Name" name="product_name" required>
                                        <label for="floatingInput">Product Name</label>
                                    </div>
                                    <!--<div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="product_code"
                                               placeholder="Product Code" name="product_code" required>
                                        <label for="floatingInput">Product Code</label>
                                        <span id="code_status" style="color: red; display: none;">This code is already in use.</span>
                                    </div>-->
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="inputGroupSelect04" aria-label="Example select with button addon" name="product_cat" required>
                                            <option selected disabled>Choose Product Category</option>
                                            <?php
                                            $fetch_cat = $db_handle->runQuery("select * from category where status != 0 order by category_name ASC");
                                            for ($i=0; $i < count($fetch_cat); $i++) {
                                                ?>
                                                <option value="<?php echo $fetch_cat[$i]['category_id'];?>"><?php echo $fetch_cat[$i]['category_name'];?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <div class="form-floating mb-3 mt-3">
                                            <input type="text" class="form-control" id="floatingInput"
                                                   placeholder="Product Varieties" name="product_varieties" required>
                                            <label for="floatingInput">Product Variety</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput"
                                                   placeholder="Company Name" name="company_name" required>
                                            <label for="floatingInput">Company Name</label>
                                        </div>
                                    </div>
                                    <button type="submit" name="add_product" id="add_product_button" class="btn btn-primary">Add Product
                                    </button>
                                </form>
                                <!-- Tab panes -->
                            </div><!--end card-body-->
                        </div><!--end modal-body-->

                    </div><!--end modal-content-->
                </div><!--end modal-dialog-->
            </div>
            <!--end modal-->
            <div class="card-body">
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                        data-bs-target="#exampleModalLogin">
                    Add Product
                </button>
            </div>

            <?php
            if (isset($_GET['edit'])) {
                ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Product<span class="badge bg-soft-success font-12">update</span>
                                </h4>
                            </div><!--end card-header-->
                            <div class="card-body">
                                <form action="Update" method="post">
                                    <?php
                                    $product_data = $db_handle->runQuery("select * from product where product_id = {$_GET['edit']}");
                                    ?>
                                    <input type="hidden" value="<?php echo $_GET['edit']; ?>" name="product_id" required>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floatingInput"
                                               value="<?php echo $product_data[0]['product_name']; ?>" name="product_name"
                                               required>
                                        <label for="floatingInput">Product Name</label>
                                    </div>
                                    <select class="form-select" id="inputGroupSelect04" aria-label="Example select with button addon" name="product_cat" required>
                                        <?php
                                        $fetch_cat_name = $db_handle->runQuery("select * from category, product where category.category_id = product.cat_id and product.product_id = {$_GET['edit']}");
                                        if($fetch_cat_name) {
                                            ?>
                                            <option selected value="<?php echo $fetch_cat_name[0]['cat_id'];?>"><?php echo $fetch_cat_name[0]['category_name'];?></option>
                                            <?php
                                        }
                                        ?>
                                        <?php
                                        $fetch_cat = $db_handle->runQuery("select * from category where status != 0 order by category_name ASC");
                                        for ($i=0; $i < count($fetch_cat); $i++) {
                                            ?>
                                            <option value="<?php echo $fetch_cat[0]['category_id'];?>"><?php echo $fetch_cat[0]['category_name'];?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <div class="form-floating mb-3 mt-3">
                                        <input type="text" class="form-control" id="floatingInput"
                                               value="<?php echo $product_data[0]['variety']; ?>" name="product_variety"
                                               required>
                                        <label for="floatingInput">Product Varieties</label>
                                    </div>
                                    <div class="form-floating mb-3 mt-3">
                                        <input type="text" class="form-control" id="floatingInput"
                                               value="<?php echo $product_data[0]['company_name']; ?>" name="company_name"
                                               required>
                                        <label for="floatingInput">Company Name</label>
                                    </div>
                                    <button type="submit" name="edit_product" class="btn btn-primary">Edit Product
                                    </button>
                                </form>

                            </div><!--end card-body-->
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
                                $fetch_product_no = $db_handle->numRows("select * from category,product where category.category_id = product.cat_id order by product_id DESC");
                                if($fetch_product_no > 0){
                                    for ($i = 0; $i < count($fetch_product); $i++) {
                                        ?>
                                        <tr>
                                            <td><?php echo $i + 1; ?></td>
                                            <td><?php echo $fetch_product[$i]['product_name']; ?></td>
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
                                                <a href="Product?update=<?php echo $fetch_product[$i]['product_id'];?>"
                                                   class="btn btn-sm btn-soft-danger btn-circle"><i class="dripicons-anchor"
                                                                                                    aria-hidden="true"></i></a>
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
    $(document).ready(function () {
        $('#product_code').on('input', function () {
            var code = $(this).val();
            // Make AJAX request to check if the code exists in the database
            $.ajax({
                type: 'POST',
                url: 'check_code.php', // Replace 'check_code.php' with the URL of your server-side script
                data: {code: code},
                success: function (response) {
                    if (response === 'exists') {
                        $('#code_status').show();
                        $('#add_product_button').prop('disabled', true);
                    } else {
                        $('#code_status').hide();
                        $('#add_product_button').prop('disabled', false);
                    }
                }
            });
        });
    });
</script>

</body>

</html>