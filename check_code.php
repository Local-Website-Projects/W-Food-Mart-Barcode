<?php
session_start();
require_once('config/dbConfig.php');
$db_handle = new DBController();

if(!isset($_SESSION['admin'])){
    echo "
    <script>
    window.location.href = 'Login';
    </script>
    ";
}
$code = $_POST['code'];
$check_code = $db_handle->numRows("select * from product where product_code = '$code'");
if($check_code > 0){
    echo 'exists';
} else {
    echo 'not exists';
}