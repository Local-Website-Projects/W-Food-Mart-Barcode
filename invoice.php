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


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Food Mart - Invoice</title>
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
                                <h4 class="page-title">Cart</h4>
                            </div><!--end col-->
                        </div><!--end row-->
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div><!--end row-->
            <!-- end page title end breadcrumb -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="Insert">
                                <div class="table-responsive shopping-cart">
                                    <table class="table mb-0">
                                        <thead>
                                        <tr>
                                            <th class="border-top-0">Product</th>
                                            <th class="border-top-0">Price</th>
                                            <th class="border-top-0">Quantity</th>
                                            <th class="border-top-0">Total</th>
                                            <th class="border-top-0">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="productRows">
                                        <tr class="productRow">
                                            <td>
                                                <select class="productSelect select2 form-control mb-3 custom-select" name="shop_stock[]" required
                                                        style="width: 100%; height:36px;">
                                                    <option>Select</option>
                                                    <?php
                                                    $fetch_product = $db_handle->runQuery("SELECT shop_stock.quantity,shop_stock.shop_stock_id, product.product_code, primary_stock.selling_cost, product.product_id, primary_stock.p_stock_id FROM `shop_stock`, primary_stock, product WHERE shop_stock.stock_id = primary_stock.p_stock_id AND primary_stock.product_id = product.product_id AND shop_stock.quantity > 0 and shop_stock.status = 1");
                                                    for ($i = 0; $i < count($fetch_product); $i++) {
                                                        ?>
                                                        <option value="<?php echo $fetch_product[$i]['shop_stock_id'];?>"><?php echo $fetch_product[$i]['product_code']; ?>
                                                            - <?php echo $fetch_product[$i]['quantity']; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input class="productPrice form-control form-control-sm w-25"
                                                       type="text" value="0" name="price[]" required readonly>
                                            </td>
                                            <td>
                                                <input class="quantity form-control form-control-sm w-25" type="number"
                                                       value="0" onchange="calculate(this);" required name="quantity[]">
                                            </td>
                                            <td>
                                                <input class="product_total form-control form-control-sm w-25" type="text" value="0" name="sub_total[]" required step="0.01">
                                            </td>
                                            <td>
                                                <a href="#" class="text-dark addRow"><i
                                                            class="mdi mdi-eye-circle-outline font-18"></i></a>
                                                <a href="#" class="text-dark removeRow"><i
                                                            class="mdi mdi-close-circle-outline font-18"></i></a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!--end row-->
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <div class="total-payment p-3">
                                            <h6 class="header-title font-14">Total Payment</h6>
                                            <table class="table">
                                                <tbody>
                                                <select class="selectCustomer select2 form-control mb-3 custom-select"
                                                        style="width: 100%; height:36px;" name="customer">
                                                    <option>Select Membership Number</option>
                                                    <?php
                                                    $fetch_member = $db_handle->runQuery("SELECT * FROM `customer_data`");
                                                    for ($i = 0; $i < count($fetch_member); $i++) {
                                                        ?>
                                                        <option value="<?php echo $fetch_member[$i]['customer_id']; ?>"><?php echo $fetch_member[$i]['contact_phone']; ?>
                                                            - <?php echo $fetch_member[$i]['discount_percentage']; ?> %</option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <tr>
                                                    <td class="payment-title">Subtotal</td>
                                                    <td><input id="grandTotalInput" class="form-control form-control-sm w-25" type="text" value="BDT 0.00" readonly required name="subtotal"></td>
                                                </tr>
                                                <tr>
                                                    <td class="payment-title">Discount (%)</td>
                                                    <td><input class="form-control form-control-sm w-25" type="number" id="discount" required
                                                               value="0" name="discount">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="payment-title">Total</td>
                                                    <td class="text-dark"><strong><input class="form-control form-control-sm w-25" type="text" id="totalAfterDiscount"
                                                                                         value="0" required name="grand_total"></strong></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <button type="submit" name="add_invoice" class="btn btn-primary">Print Invoice
                                            </button>
                                        </div>
                                    </div><!--end col-->
                                </div>
                            </form>
                        </div><!--end card-->
                    </div><!--end card-body-->
                </div><!--end col-->
            </div><!--end row-->

        </div><!-- container -->


        <?php include('include/footer.php'); ?>
        <!--end footer-->
    </div>
    <!-- end page content -->
</div>
<!-- end page-wrapper -->


<!-- jQuery  -->
<?php include('include/js.php'); ?>

<script>
    $(document).ready(function() {
        // Initialize Select2 on existing select elements
        initializeSelect2($('.productSelect'));
        initializeSelect2($('.selectCustomer'));

        function initializeSelect2(selectElement) {
            selectElement.select2();
        }

        function initRowEvents(row) {
            // Initialize Select2 on the new row's select element
            initializeSelect2(row.find('.productSelect'));

            row.find('.productSelect').change(function() {
                var stockId = $(this).val();
                var productPriceInput = $(this).closest('tr').find('.productPrice');
                $.ajax({
                    type: 'POST',
                    url: 'get_product_price.php',
                    data: {stockId: stockId},
                    success: function(response) {
                        productPriceInput.val(response);
                        calculate(productPriceInput); // Recalculate total in case quantity was already set
                        updateGrandTotal();
                    }
                });
            });

            row.find('.addRow').off('click').on('click', function(e) {
                e.preventDefault();
                var newRow = $('.productRow:first').clone(); // Clone the first row
                newRow.find('.select2-container').remove(); // Remove the Select2 container
                newRow.find('.productSelect').removeClass('select2-hidden-accessible').removeAttr('data-select2-id').val('').show(); // Reset the select element
                newRow.find('.productPrice').val('0'); // Reset price input value
                newRow.find('.quantity').val('0'); // Reset quantity input value
                newRow.find('.product_total').val('0'); // Reset the total text
                newRow.insertAfter('.productRow:last'); // Insert the new row after the last row
                initRowEvents(newRow); // Initialize events and Select2 for the new row
            });

            row.find('.removeRow').off('click').on('click', function(e) {
                e.preventDefault();
                if ($('#productRows .productRow').length > 1) {
                    $(this).closest('tr').remove();
                    updateGrandTotal(); // Update grand total after removing a row
                }
            });
        }

        // Initialize events for the existing row
        initRowEvents($('.productRow'));

        // Calculate subtotal and total payment when discount changes
        $('#discount').on('input', function() {
            totalAfterDiscount();
        });

        // Calculate initial total after discount
        totalAfterDiscount();
        $('#discount').on('input', function() {
            totalAfterDiscount(); // Make sure totalAfterDiscount is accessible from here
        });
    });

    function calculate(element) {
        let row = $(element).closest('tr');
        let quantity = row.find('.quantity').val();
        let productTotal = row.find('.product_total');
        let unitPrice = row.find('.productPrice').val();

        if (!isNaN(quantity) && quantity > 0 && !isNaN(unitPrice) && unitPrice > 0) {
            let total = quantity * unitPrice;
            productTotal.val(total.toFixed(2));
        } else {
            productTotal.val('0');
        }

        updateGrandTotal(); // Update grand total whenever an individual total is calculated
    }

    function updateGrandTotal() {
        let grandTotal = 0;
        $('.product_total').each(function() {
            let totalValue = parseFloat($(this).val());
            if (!isNaN(totalValue)) {
                grandTotal += totalValue;
            }
        });

        $('#grandTotalInput').val(grandTotal.toFixed(2));
        totalAfterDiscount();
    }

    function totalAfterDiscount() {
        let discount = parseFloat($('#discount').val());
        let subtotalText = $('#grandTotalInput').val();
        let subtotalValue = parseFloat(subtotalText);

        if (!isNaN(subtotalValue)) {
            let totalAfter = subtotalValue - (subtotalValue * (discount / 100));
            $('#totalAfterDiscount').val(totalAfter.toFixed(2));
        } else {
            // Handle the case where the subtotal value cannot be parsed
            $('#totalAfterDiscount').val('Invalid Subtotal');
        }
    }

</script>

</body>

</html>