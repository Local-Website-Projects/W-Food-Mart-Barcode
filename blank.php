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
    <title>Food Mart - Category</title>
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
                                <h4 class="page-title">Category</h4>
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
            <div class="card-body">
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                        data-bs-target="#exampleModalLogin">
                    Add Category
                </button>
            </div>

            <?php
            if (isset($_GET['edit'])) {
                ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Category<span class="badge bg-soft-success font-12">update</span>
                                </h4>
                            </div><!--end card-header-->
                            <div class="card-body">
                                <form action="Update" method="post">
                                    <?php
                                    $cat_data = $db_handle->runQuery("select * from category where category_id = {$_GET['edit']}");
                                    ?>
                                    <input type="hidden" value="<?php echo $_GET['edit']; ?>" name="category_id">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floatingInput"
                                               value="<?php echo $cat_data[0]['category_name']; ?>" name="category_name"
                                               required>
                                        <label for="floatingInput">Category Name</label>
                                    </div>
                                    <button type="submit" name="edit_category" class="btn btn-primary">Edit Category
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
                            <h4 class="card-title">Category List</h4>
                        </div><!--end card-header-->
                        <div class="card-body">
                            <table id="datatable-buttons"
                                   class="table table-striped table-bordered dt-responsive nowrap"
                                   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <th>Category Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $fetch_category = $db_handle->runQuery("select * from category order by category_id desc");

                                for ($i = 0; $i < count($fetch_category); $i++) {
                                    ?>
                                    <tr>
                                        <td><?php echo $i + 1; ?></td>
                                        <td><?php echo $fetch_category[$i]['category_name']; ?></td>
                                        <td>
                                            <?php
                                            if ($fetch_category[$i]['status'] == 0) {
                                                ?>
                                                <span class="badge badge-soft-danger">Deactive</span>
                                                <?php
                                            } elseif ($fetch_category[$i]['status'] == 1) {
                                                ?>
                                                <span class="badge badge-soft-success">Active</span>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td class="text-right">
                                            <a href="Category?edit=<?php echo $fetch_category[$i]['category_id']; ?>"
                                               class="btn btn-sm btn-soft-success btn-circle me-2"><i
                                                        class="dripicons-pencil"></i></a>
                                            <a href="Category?update=<?php echo $fetch_category[$i]['category_id'];?>"
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

</body>

</html>