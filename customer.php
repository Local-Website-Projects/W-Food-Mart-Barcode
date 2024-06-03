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
    $check_cat = $db_handle->runQuery("select * from category where category_id = '{$_GET['update']}'");
    if($check_cat[0]['status'] == '1') {
        $u = 0;
    }else {
        $u = 1;
    }
    $update_cat_status = $db_handle->insertQuery("update category set status = '$u' where category_id = '{$_GET['update']}'");
    if($update_cat_status) {
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
    <title>Food Mart - Customer</title>
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
                                <h4 class="page-title">Customer</h4>
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
                            <h6 class="modal-title m-0" id="exampleModalDefaultLogin">Add Customer</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div><!--end modal-header-->
                        <div class="modal-body">
                            <div class="card-body p-0">
                                <form action="Insert" method="post">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floatingInput"
                                               placeholder="Customer Name" name="customer_name" required>
                                        <label for="floatingInput">Customer Name</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floatingInput"
                                               placeholder="Customer Contact No" name="contact_no" required>
                                        <label for="floatingInput">Contact No.</label>
                                    </div>
                                    <button type="submit" name="add_customer" class="btn btn-primary">Add Customer
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
                    Add Customer
                </button>
            </div>

            <?php
            if (isset($_GET['edit'])) {
                ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Customer<span class="badge bg-soft-success font-12">update</span>
                                </h4>
                            </div><!--end card-header-->
                            <div class="card-body">
                                <form action="Update" method="post">
                                    <?php
                                    $customer_data = $db_handle->runQuery("select * from customer_data where customer_id = {$_GET['edit']}");
                                    ?>
                                    <input type="hidden" value="<?php echo $_GET['edit']; ?>" name="customer_id">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floatingInput"
                                               value="<?php echo $customer_data[0]['customer_name']; ?>" name="customer_name"
                                               required>
                                        <label for="floatingInput">Category Name</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floatingInput"
                                               value="<?php echo $customer_data[0]['contact_phone']; ?>" name="customer_phone"
                                               required>
                                        <label for="floatingInput">Contact No</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="floatingInput"
                                               value="<?php echo $customer_data[0]['discount_percentage']; ?>" name="discount"
                                               required>
                                        <label for="floatingInput">Contact No</label>
                                    </div>
                                    <button type="submit" name="edit_customer" class="btn btn-primary">Edit Customer
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
                            <h4 class="card-title">Customer List</h4>
                        </div><!--end card-header-->
                        <div class="card-body">
                            <table id="datatable-buttons"
                                   class="table table-striped table-bordered dt-responsive nowrap"
                                   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <th>Customer Name</th>
                                    <th>Contact No</th>
                                    <th>Total Purchase</th>
                                    <th>Discount (%)</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $fetch_customer = $db_handle->runQuery("select * from customer_data order by customer_id desc");

                                for ($i = 0; $i < count($fetch_customer); $i++) {
                                    ?>
                                    <tr>
                                        <td><?php echo $i + 1; ?></td>
                                        <td><?php echo $fetch_customer[$i]['customer_name']; ?></td>
                                        <td><?php echo $fetch_customer[$i]['contact_phone']; ?></td>
                                        <td><?php echo $fetch_customer[$i]['total_purchase']; ?></td>
                                        <td><?php echo $fetch_customer[$i]['discount_percentage']; ?></td>
                                        <td class="text-right">
                                            <a href="Customer?edit=<?php echo $fetch_customer[$i]['customer_id']; ?>"
                                               class="btn btn-sm btn-soft-success btn-circle me-2"><i
                                                    class="dripicons-pencil"></i></a>
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