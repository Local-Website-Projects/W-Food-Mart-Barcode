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
$stock_id = $_POST['stockId'];

$fetch_primary_stock_id = $db_handle->runQuery("select * from shop_stock where shop_stock_id = '$stock_id'");


$check = $db_handle->runQuery("SELECT selling_cost FROM `primary_stock` where p_stock_id = '{$fetch_primary_stock_id[0]['stock_id']}'");
if($check){
    echo $check[0]['selling_cost'];
}