<?php
session_start();
require_once('config/dbConfig.php');
$db_handle = new DBController();

if (isset($_POST['invoiceId'])) {
$invoiceId = $_POST['invoiceId'];

// Fetch data based on the invoice ID
$products = $db_handle->runQuery("select * from invoice_product,product where invoice_product.invoice_id = '$invoiceId' and invoice_product.product_code = product.product_id and invoice_product.discount = 0");

// Prepare the response data
$response = array();
if ($products) {
    foreach ($products as $product) {
        $response[] = array(
            'product_id' => $product['product_id'],
            'product_name' => $product['product_name']
        );
    }
}

// Return the response as JSON
echo json_encode($response);
}