<?php
require_once('config/dbConfig.php');
$db_handle = new DBController();
date_default_timezone_set("Asia/Dhaka");
$inserted_at = date("Y-m-d H:i:s");
$result = 0;
if(isset($_POST['register'])){
    $full_name = $db_handle->checkValue($_POST['full_name']);
    $email = $db_handle->checkValue($_POST['email']);
    $password = $db_handle->checkValue($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $phone = $db_handle->checkValue($_POST['phone']);
    $joining_date = $db_handle->checkValue($_POST['joining_date']);

    $insert_register = $db_handle->insertQuery("INSERT INTO `admin`( `name`,`joining_date`, `email`, `phone`, `password`, `inserted_at`) VALUES ('$full_name','$joining_date','$email','$phone','$hashed_password','$inserted_at')");
    if($insert_register){
        $result = 1;
    } else {
        $result = 2;
    }
}

if (isset($_POST['login'])) {
    $email = $db_handle->checkValue($_POST['email']);
    $password = $db_handle->checkValue($_POST['password']);

    if(empty($email) || empty($password)) {
        echo "
        <script>
        document.cookie = 'alert = 6;';
        window.location.href='Login';
</script>
        ";
    } else {
        $fetch_user = $db_handle ->runQuery("SELECT * FROM `admin` WHERE `email`='$email'");
        if(count($fetch_user) == 1){
            if($fetch_user[0]['approve_status'] == '0'){
                echo "
        <script>
        document.cookie = 'alert = 5;';
        window.location.href='Login';
</script>
        ";
            }
            else {
                if (password_verify($password, $fetch_user[0]['password'])) {
                    session_start();
                    $_SESSION['admin'] = $fetch_user[0]['admin_id'];
                    echo "
        <script>
        document.cookie = 'alert = 1;';
        window.location.href='Home';
</script>
        ";
                } else {
                    echo "
        <script>
        document.cookie = 'alert = 5;';
        window.location.href='Login';
</script>
        ";
                }
            }
        } else {
            echo "
        <script>
        document.cookie = 'alert = 5;';
        window.location.href='Login';
</script>
        ";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <title>Food Mart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Food Mart" name="description"/>
    <meta content="" name="author"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link href="vendor/toaster/css/toastr.min.css" rel="stylesheet" type="text/css"/>
    <!-- App css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css"/>
</head>

<body class="account-body accountbg">

<!-- Log In page -->
<div class="container">
    <div class="row vh-100 d-flex justify-content-center">
        <div class="col-12 align-self-center">
            <div class="row">
                <div class="col-lg-5 mx-auto">
                    <div class="card">
                        <div class="card-body p-0 auth-header-box">
                            <div class="text-center p-3">
                                <a class='logo logo-admin' href='Home'>
                                    <img src="assets/images/logo-sm-dark.png" height="50" alt="logo" class="auth-logo">
                                </a>
                                <h4 class="mt-3 mb-1 fw-semibold text-white font-18">Let's Get Started</h4>
                                <p class="text-muted  mb-0">Sign in to continue to Food Mart.</p>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <ul class="nav-border nav nav-pills" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active fw-semibold" data-bs-toggle="tab" href="#LogIn_Tab"
                                       role="tab">Log In</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#Register_Tab"
                                       role="tab">Register</a>
                                </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <?php
                                if($result == 1){
                                    ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Congrats!</strong> You are successfully registered now.
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    <?php
                                }
                                if ($result == 2) {
                                    ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Sorry!</strong> Something went wrong.
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="tab-pane active p-3" id="LogIn_Tab" role="tabpanel">
                                    <form class="form-horizontal auth-form"
                                          action="#" method="post">

                                        <div class="form-group mb-2">
                                            <label class="form-label" for="username">User Email</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="email" id="username"
                                                       placeholder="Enter Email">
                                            </div>
                                        </div><!--end form-group-->

                                        <div class="form-group mb-2">
                                            <label class="form-label" for="userpassword">Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="password"
                                                       id="userpassword" placeholder="Enter password">
                                            </div>
                                        </div>
                                        <!--end form-group-->
                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <button class="btn btn-primary w-100 waves-effect waves-light"
                                                        type="submit" name="login">Log In <i class="fas fa-sign-in-alt ms-1"></i>
                                                </button>
                                            </div><!--end col-->
                                        </div> <!--end form-group-->
                                    </form><!--end form-->
                                    <div class="m-3 text-center text-muted">
                                        <p class="mb-0">Don't have an account? <a class='text-primary ms-2'
                                                                                   href='Register'>Free
                                                Register</a></p>
                                    </div>
                                </div>
                                <div class="tab-pane px-3 pt-3" id="Register_Tab" role="tabpanel">
                                    <form class="form-horizontal auth-form"
                                          action="#" method="post">
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="username">Full Name</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="full_name" placeholder="Enter Full Name" required>
                                            </div>
                                        </div><!--end form-group-->

                                        <div class="form-group mb-2">
                                            <label class="form-label" for="mo_number">Mobile Number</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="phone"
                                                       id="mo_number" placeholder="Enter Mobile Number" required>
                                            </div>
                                        </div><!--end form-group-->

                                        <div class="form-group mb-2">
                                            <label class="form-label" for="username">Joining Date</label>
                                            <div class="input-group">
                                                <input type="date" class="form-control" name="joining_date" required>
                                            </div>
                                        </div><!--end form-group-->

                                        <div class="form-group mb-2">
                                            <label class="form-label" for="useremail">Email</label>
                                            <div class="input-group">
                                                <input type="email" class="form-control" name="email" id="useremail"
                                                       placeholder="Enter Email" required>
                                            </div>
                                        </div><!--end form-group-->

                                        <div class="form-group mb-2">
                                            <label class="form-label" for="username">Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="password" placeholder="Password" id="password" required>
                                            </div>
                                        </div><!--end form-group-->

                                        <div class="form-group mb-2">
                                            <label class="form-label" for="conf_password">Confirm Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="conf-password"
                                                       id="conf_password" placeholder="Confirm Password" required>
                                            </div>
                                            <span id="msg" style="color: red; display: none">Password and confirm password doesn't match.</span>
                                        </div><!--end form-group-->

                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <button class="btn btn-primary w-100 waves-effect waves-light"
                                                        type="submit" name="register" id="registerBtn" disabled>Register <i class="fas fa-sign-in-alt ms-1"></i>
                                                </button>
                                            </div><!--end col-->
                                        </div> <!--end form-group-->
                                    </form><!--end form-->
                                    <p class="my-3 text-muted">Already have an account ?<a class='text-primary ms-2'
                                                                                           href='Login'>Log
                                            in</a></p>
                                </div>
                            </div>
                        </div><!--end card-body-->
                        <div class="card-body bg-light-alt text-center">
                                    <span class="text-muted d-none d-sm-inline-block">Food Mart © <script>
                                        document.write(new Date().getFullYear())
                                    </script></span>
                        </div>
                    </div><!--end card-->
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end col-->
    </div><!--end row-->
</div>
<!--end container-->
<!-- End Log In page -->


<!-- jQuery  -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/waves.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/js/simplebar.min.js"></script>
<script src="vendor/toaster/js/toastr.min.js"></script>
<script src="plugins/toastr-init.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('conf_password');
        const registerButton = document.getElementById('registerBtn');
        const msg = document.getElementById('msg');

        // Function to check if passwords match
        function checkPasswords() {
            const password = passwordField.value;
            const confirmPassword = confirmPasswordField.value;

            if (password === confirmPassword) {
                registerButton.disabled = false;
                msg.style.display = 'none';
            } else {
                registerButton.disabled = true;
                msg.style.display = 'block';
            }
        }

        passwordField.addEventListener('input', checkPasswords);
        confirmPasswordField.addEventListener('input', checkPasswords);
    });
</script>
</body>

</html>