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
    <title>Food Mart - Profile</title>
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
                                <h4 class="page-title">Profile</h4>
                            </div><!--end col-->
                        </div><!--end row-->
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div>
            <!--end row-->
            <!-- end page title end breadcrumb -->
            <div class="row mt-3">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Change Password</h4>
                        </div>
                        <div class="card-body">
                            <form action="Update" method="post">
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="floatingInput"
                                           name="old_password" required>
                                    <label for="floatingInput">Old Password</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    <label for="new_password">New Password</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" required>
                                    <label for="confirm_new_password">Confirm New Password</label>
                                    <span id="password_match_message" style="color: red; display: none;">Password and Confirm Password do not match.</span>
                                </div>
                                <button type="submit" name="update_password" class="btn btn-primary mt-3" id="update_password">Submit
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- container -->

        <?php include ('include/footer.php'); ?>
        <!--end footer-->
    </div>
    <!-- end page content -->
</div>
<!-- end page-wrapper -->


<!-- jQuery  -->
<?php include('include/js.php'); ?>

<script>
    document.getElementById("confirm_new_password").addEventListener("input", function() {
        var newPassword = document.getElementById("new_password").value;
        var confirmNewPassword = this.value;
        var messageSpan = document.getElementById("password_match_message");
        let btn = document.getElementById("update_password");

        if (newPassword !== confirmNewPassword) {
            messageSpan.style.display = "block";
            btn.disabled = true; // disable the button
        } else {
            messageSpan.style.display = "none";
            btn.disabled = false; // enable the button
        }
    });
</script>

</body>

</html>