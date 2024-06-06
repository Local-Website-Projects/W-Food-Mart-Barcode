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

$fetch_primary_stock_id = $db_handle->runQuery("select * from shop_stock,product where unique_id = '$stock_id' and shop_stock.product = product.product_id");

if($fetch_primary_stock_id){
    $response = array(
        'selling_price' => $fetch_primary_stock_id[0]['selling_price'],
        'product_name' => $fetch_primary_stock_id[0]['product_name']
    );
    echo json_encode($response);
}