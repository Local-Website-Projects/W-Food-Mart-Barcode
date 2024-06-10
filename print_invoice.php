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
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <title>Print Invoice - Food Mart</title>
    <link rel="stylesheet" href="assets/css/invoice.css">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
<button class="no-print"><a href="Home">Home</a></button>
<button class="no-print" id="printButton"><a href="javascript:void(0)">Print</a></button>
<!-- partial:index.partial.html -->
<div id="invoice-POS">

    <center id="top">
        <div class="logo"></div>
        <div class="info">
            <h2>Food Mart</h2>
            <h2 style="font-size: 1em">16 KDA Avenue, Khulna</h2>
            <h2 style="font-size: 1em">Printed On: <?php echo $today;?></h2>
        </div><!--End Info-->
    </center><!--End InvoiceTop-->

    <div id="mid">
        <div class="info">
            <h2>Customer Info</h2>
            <?php
            $fetch_invoice_data = $db_handle->runQuery("SELECT * FROM invoice_data where invoice_id='$id'");
            if ($fetch_invoice_data[0]['customer_id'] != 0) {
                $customer_fetch = $db_handle->runQuery("select * from customer_data where customer_id='{$fetch_invoice_data[0]['customer_id']}'");
                $customer_name = $customer_fetch[0]['customer_name'];
                $customer_contact = $customer_fetch[0]['contact_phone'];
            } else {
                $customer_name = '';
                $customer_contact = '';
            }
            ?>
            <p>
                Name : <?php echo $customer_name; ?></br>
                Phone   : <?php echo $customer_contact; ?></br>
                INV: <?php echo $id; ?></br>
                Payment Method: <?php
                echo $fetch_invoice_data[0]['payment_method'];
                ?></br>
            </p>
        </div>
    </div><!--End Invoice Mid-->

    <div id="bot">

        <div id="table">
            <table>
                <tr class="tabletitle">
                    <td class="item"><h2>Item</h2></td>
                    <td class="item"><h2>Price</h2></td>
                    <td class="Hours"><h2>Qty</h2></td>
                    <td class="Hours"><h2>Discount</h2></td>
                    <td class="Rate"><h2>Sub-Total</h2></td>
                </tr>
                <?php
                $fetch_product = $db_handle->runQuery("select * from invoice_product,product where invoice_id='$id' and invoice_product.product_code = product.product_id");
                for($i=0; $i<count($fetch_product); $i++){
                    ?>
                    <tr class="service">
                        <td  class="tableitem"><p class="itemtext"><?php echo $fetch_product[$i]['product_name'];?></p></td>
                        <td  class="tableitem"><p class="itemtext"><?php echo $fetch_product[$i]['selling_price'];?></p></td>
                        <td  class="tableitem"><p class="itemtext"><?php echo $fetch_product[$i]['quantity'];?></p></td>
                        <td  class="tableitem"><p class="itemtext"><?php echo $fetch_product[$i]['discount'];?></p></td>
                        <td  class="tableitem"><p class="itemtext"><?php echo $fetch_product[$i]['total_price'];?></p></td>
                    </tr>
                    <?php
                }
                $fetch_invoice_details = $db_handle->runQuery("select * from invoice_data where invoice_id='$id'");
                ?>


                <tr class="tabletitle">
                    <td class="Rate" colspan="4"><h2>Subtotal</h2></td>
                    <td class="payment"><h2><?php echo $fetch_invoice_details[0]['sub_total'];?></h2></td>
                </tr>

                <tr class="tabletitle">
                    <td class="Rate" colspan="4"><h2>Discount</h2></td>
                    <td class="payment"><h2><?php $d = $fetch_invoice_details[0]['discount'];
                            $discount = ($d/100) * $fetch_invoice_details[0]['sub_total'];
                            echo $discount;
                            ?></h2></td>
                </tr>
                <tr class="tabletitle">
                    <td class="Rate" colspan="4"><h2>Vat & Tax</h2></td>
                    <td class="payment"><h2>00</h2></td>
                </tr>
                <tr class="tabletitle">
                    <td class="Rate" colspan="4"><h2>Total</h2></td>
                    <td class="payment"><h2><?php echo $fetch_invoice_details[0]['grand_total'];?></h2></td>
                </tr>
                <tr class="tabletitle">
                    <td class="Rate" colspan="4"><h2>Money Received</h2></td>
                    <td class="payment"><h2><?php echo $fetch_invoice_details[0]['receive_amount'];?></h2></td>
                </tr>
                <tr class="tabletitle">
                    <td class="Rate" colspan="4"><h2>Money Returned</h2></td>
                    <td class="payment"><h2><?php echo $fetch_invoice_details[0]['return_amount'];?></h2></td>
                </tr>

            </table>
        </div><!--End Table-->

        <div id="legalcopy">
            <p class="legal"><strong>Thank you for your purchase!</strong>
            <p class="legal" style="font-size: 8px"><strong>Developed By FrogBid</strong>
            </p>
        </div>

    </div><!--End InvoiceBot-->
</div><!--End Invoice-->
<!-- partial -->
<script>
    document.getElementById("printButton").addEventListener("click", function() {
        window.print();
    });
</script>
</body>
</html>
