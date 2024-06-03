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

if(isset($_GET['d_active'])){
    $update_admin = $db_handle->insertQuery("UPDATE `admin` SET`approve_status`='0',`updated_at`='$inserted_at' WHERE `admin_id` = {$_GET['d_active']}");
    if($update_admin){
        echo "
        <script>
        document.cookie = 'alert = 4;';
        window.location.href='Employee';
</script>
        ";
    } else {
        echo "
        <script>
        document.cookie = 'alert = 5;';
        window.location.href='Employee';
</script>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Food Mart - Employee</title>
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
                                <h4 class="page-title">Employee</h4>
                            </div><!--end col-->
                        </div><!--end row-->
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div>
            <!--end row-->
            <!-- end page title end breadcrumb -->
            <?php
            if(isset($_GET['active'])){
                ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Employee Status<span class="badge bg-soft-success font-12">update</span></h4>
                            </div><!--end card-header-->
                            <div class="card-body">
                                <form action="Update" method="post">
                                    <input type="hidden" value="<?php echo $_GET['active'];?>" name="admin_id">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floatingInput" placeholder="Post" name="post" required>
                                        <label for="floatingInput">Post</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="exampleFormControlSelect1" name="role" required>
                                            <option selected disabled>Select Role</option>
                                            <option value="1">Super Admin</option>
                                            <option value="2">Stock</option>
                                            <option value="3">Shop</option>
                                        </select>
                                    </div>
                                    <button type="submit" name="admin_approve" class="btn btn-primary">Make Active</button>
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
                            <h4 class="card-title">Employee List</h4>
                        </div><!--end card-header-->
                        <div class="card-body">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Post</th>
                                    <th>Joining Date</th>
                                    <th>Phone</th>
                                    <th>User Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $fetch_admin = $db_handle->runQuery("select * from admin order by admin_id desc");

                                for ($i= 0; $i < count($fetch_admin); $i++) {
                                    ?>
                                    <tr>
                                        <td><?php echo $fetch_admin[$i]['name'];?></td>
                                        <td><?php echo $fetch_admin[$i]['email'];?></td>
                                        <td><?php echo $fetch_admin[$i]['post'];?></td>
                                        <td><?php $joiningDate = $fetch_admin[$i]['joining_date'];
                                            $formattedDate = date("d M, Y", strtotime($joiningDate));
                                            echo $formattedDate;?></td>
                                        <td><?php echo $fetch_admin[$i]['phone'];?></td>
                                        <td><?php  if($fetch_admin[$i]['user_type'] == '1') echo 'Super Admin';
                                            if($fetch_admin[$i]['user_type'] == '2') echo 'Stock Manager';
                                            if($fetch_admin[$i]['user_type'] == '3') echo 'Shop Manager';
                                        ?></td>
                                        <td>
                                            <?php
                                            if($fetch_admin[$i]['approve_status'] == 0){
                                                ?>
                                                <span class="badge badge-soft-danger">Pending / Deactivated</span>
                                                <?php
                                            }  elseif($fetch_admin[$i]['approve_status'] == 1){
                                                ?>
                                                <span class="badge badge-soft-success">Accepted</span>
                                                <?php
                                            }
                                            ?>
                                            </td>
                                        <td class="text-right">
                                            <div class="dropdown d-inline-block">
                                                <a class="dropdown-toggle arrow-none" id="dLabel11" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                                    <i class="las la-ellipsis-v font-20 text-muted"></i>
                                                </a>
                                                <?php
                                                if($fetch_admin[$i]['approve_status'] == 0){
                                                    ?>
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel11" style="">
                                                        <a class="dropdown-item" href="Employee?active=<?php echo $fetch_admin[$i]['admin_id'];?>">Active</a>
                                                    </div>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel11" style="">
                                                        <a class="dropdown-item" href="Employee?d_active=<?php echo $fetch_admin[$i]['admin_id'];?>">Deactivate</a>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
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

        <?php include ('include/footer.php');?>
        <!--end footer-->
    </div>
    <!-- end page content -->
</div>
<!-- end page-wrapper -->


<!-- jQuery  -->
<?php include('include/js.php'); ?>

</body>

</html>