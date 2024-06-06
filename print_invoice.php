<?php
session_start();
require_once('config/dbConfig.php');
$db_handle = new DBController();
date_default_timezone_set("Asia/Dhaka");
$inserted_at = date("Y-m-d H:i:s");
$today = date("d M Y");

if (!isset($_SESSION['admin'])) {
    echo "
    <script>
    window.location.href = 'Login';
    </script>
    ";
}

if(isset($_GET['id'])){
    $id = $_GET['id'];
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centered Content</title>
    <!-- Include Bootstrap CSS (add this if not already included) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .main {
            background: #eee;
            width: 100%;
        }
        .company_name{
            font-size: 48px;
            font-weight: bold;
        }
        table {
            width: 90%;
        }
        tr, td, th {
            border: 1px solid black;
        }
        th {
            font-size: 40px;
        }
        td {
            font-size: 40px;
            color: black;
        }
        p{
            font-size: 32px;
        }
    </style>
</head>
<body class="text-center">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 d-flex justify-content-center">
            <div class="main">
                <div class="row">
                    <div class="col-12 mt-5">
                        <h3 class="company_name">********************************************</h3>
                        <h3 class="company_name">INVOICE</h3>
                        <h3 class="company_name">********************************************</h3>
                        <h3 class="company_name mt-5">Food Mart</h3>
                        <h3 style="font-size: 30px">16 KDA Avenue, Khulna</h3>
                        <h3 class="company_name">INV-<?php echo $id;?></h3>
                    </div>
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <table class="mb-5">
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $fetch_product = $db_handle->runQuery("select * from invoice_product,product where invoice_id='$id' and invoice_product.product_code = product.product_id");
                            for($i=0; $i<count($fetch_product); $i++){
                                ?>
                                <tr>
                                    <td><?php echo $fetch_product[$i]['product_name'];?></td>
                                    <td><?php echo $fetch_product[$i]['quantity'];?></td>
                                    <td><?php echo $fetch_product[$i]['selling_price'];?></td>
                                    <td><?php echo $fetch_product[$i]['total_price'];?></td>
                                </tr>
                                <?php
                            }
                            $fetch_invoice_details = $db_handle->runQuery("select * from invoice_data where invoice_id='$id'");
                            ?>
                            <tr>
                                <td colspan="3">Subtotal</td>
                                <td><?php echo $fetch_invoice_details[0]['sub_total'];?></td>
                            </tr>
                            <tr>
                                <td colspan="3">Discount</td>
                                <td><?php $d = $fetch_invoice_details[0]['discount'];
                                $discount = ($d/100) * $fetch_invoice_details[0]['sub_total'];
                                echo $discount;
                                ?></td>
                            </tr>
                            <tr>
                                <td colspan="3">Vat & Tax</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td colspan="3">Total</td>
                                <td><b><?php echo $fetch_invoice_details[0]['grand_total'];?></b></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mt-3 mb-3 text-center">
                    <div class="col-12">
                        <p style="font-size: 40px;">*** Thanks for shopping with us. ***</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p>Printed On: <?php echo $today;?></p>
                    </div>
                    <div class="col-6">
                        <p style="font-size: 24px">Developed With: FrogBid</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- jQuery  -->
<?php include('include/js.php'); ?>
</body>
</html>
