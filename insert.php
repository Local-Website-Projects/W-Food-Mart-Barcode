<?php
session_start();
require('vendor/autoload.php');
require_once('config/dbConfig.php');
$db_handle = new DBController();
date_default_timezone_set("Asia/Dhaka");
$inserted_at = date("Y-m-d H:i:s");
$today = date("Y-m-d");

if (!isset($_SESSION['admin'])) {
    echo "
    <script>
    window.location.href = 'Login';
    </script>
    ";
}


if (isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    $add_category = $db_handle->insertQuery("INSERT INTO `category`(`category_name`, `inserted_at`) VALUES ('$category_name','$inserted_at')");
    if ($add_category) {
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


if (isset($_POST['add_product'])) {
    $product_name = $db_handle->checkValue($_POST['product_name']);
    $product_code = $db_handle->checkValue($_POST['product_code']);
    $product_cat = $db_handle->checkValue($_POST['product_cat']);
    $product_varieties = $db_handle->checkValue($_POST['product_varieties']);
    $company_name = $db_handle->checkValue($_POST['company_name']);

    $insert_product = $db_handle->insertQuery("INSERT INTO `product`(`product_name`,`product_code`, `cat_id`, `variety`, `company_name`, `inserted_at`) VALUES ('$product_name','$product_code','$product_cat','$product_varieties','$company_name','$inserted_at')");
    if ($insert_product) {
        echo "
        <script>
        document.cookie = 'alert = 4;';
        window.location.href='Product';
</script>
        ";
    } else {
        echo "
        <script>
        document.cookie = 'alert = 5;';
        window.location.href='Product';
</script>
        ";
    }
}


if (isset($_POST['add_primary_stock'])) {
$product_id = $db_handle->checkValue($_POST['product_id']);
$stock_in_quantity = $db_handle->checkValue($_POST['stock_in_quantity']);
$purchase_price = $db_handle->checkValue($_POST['purchase_price']);
$selling_price = $db_handle->checkValue($_POST['selling_price']);
$stock_in_date = $db_handle->checkValue($_POST['stock_in_date']);

function generate_unique_id()
{
    $db_handle = new DBController();
    $id = '';
    for ($i = 0; $i < 8; $i++) {
        $id .= rand(0, 9);
    }
    $check_id = $db_handle->runQuery("SELECT * FROM primary_stock WHERE stock_unique_id = '$id'");
    if (isset($check_id[0]['stock_unique_id'])) {
        return generate_unique_id();
    } else {
        return $id;
    }
}

// Generate the ID
$unique_id = generate_unique_id();
$fetch_product = $db_handle->runQuery("SELECT product_name FROM product WHERE product_id='$product_id'");
$product_name = $fetch_product[0]['product_name'];
$redColor = [0, 0, 0];
$generator = new Picqer\Barcode\BarcodeGeneratorJPG();
$barcode = $generator->getBarcode($unique_id, $generator::TYPE_CODE_128, 2, 50, $redColor);
$barcodeFile = 'assets/barcode/' . $unique_id . '.jpg';

// Save barcode image to a file
file_put_contents($barcodeFile, $barcode);

// Load the barcode image
$barcodeImage = imagecreatefromjpeg($barcodeFile);

// Get dimensions of the barcode image
$barcodeWidth = imagesx($barcodeImage);
$barcodeHeight = imagesy($barcodeImage);

// Create a new image canvas with extra space for text at top, bottom, and padding on sides
$padding = 30; // Padding on left and right
$canvasHeight = $barcodeHeight + 60; // Extra space for text
$canvasWidth = $barcodeWidth + $padding * 2; // Extra space for text and padding
$canvas = imagecreatetruecolor($canvasWidth, $canvasHeight);

// Allocate colors
$white = imagecolorallocate($canvas, 255, 255, 255);
$black = imagecolorallocate($canvas, 0, 0, 0);

// Fill the canvas with white background
imagefill($canvas, 0, 0, $white);

// Copy the barcode image onto the canvas
imagecopy($canvas, $barcodeImage, $padding, 30, 0, 0, $barcodeWidth, $barcodeHeight);

// Set font size
$fontSize = 4;

// Calculate text positions
$nameX = ($canvasWidth - imagefontwidth($fontSize) * strlen($product_name)) / 2;
$nameY = 10;
$bottomText = $unique_id . ' | ' . $selling_price . ' BDT+VAT';
$bottomTextX = ($canvasWidth - imagefontwidth($fontSize) * strlen($bottomText)) / 2;
$bottomTextY = $barcodeHeight + 40;

// Add text to the canvas
imagestring($canvas, $fontSize, $nameX, $nameY, $product_name, $black);
imagestring($canvas, $fontSize, $bottomTextX, $bottomTextY, $bottomText, $black);

// Save the final image
imagejpeg($canvas, $barcodeFile);

// Free memory
imagedestroy($barcodeImage);
imagedestroy($canvas);


// Add primary stock and store the barcode file path
$add_primary_stock = $db_handle->insertQuery("INSERT INTO `primary_stock`(`product_id`, `quantity`, `buying_cost`, `selling_cost`, `date`, `inserted_at`, `stock_unique_id`, `barcode`) VALUES ('$product_id','$stock_in_quantity','$purchase_price','$selling_price','$stock_in_date','$inserted_at','$unique_id','$barcodeFile')");

if ($add_primary_stock) {
    echo "
    <script>
    document.cookie = 'alert = 4;';
    window.location.href='Stock';
    </script>
    ";
} else {
    echo "
    <script>
    document.cookie = 'alert = 5;';
    window.location.href='Stock';
    </script>
    ";
}
}


if (isset($_POST['transfer_primary_stock'])) {
    $stock_id = $db_handle->checkValue($_POST['stock_id']);
    $transfer_quantity = $db_handle->checkValue($_POST['transfer_quantity']);

    $transfer_to_shop = $db_handle->insertQuery("INSERT INTO `shop_stock`(`stock_id`, `quantity`, `date`, `inserted_at`) VALUES ('$stock_id','$transfer_quantity','$today','$inserted_at')");
    if ($transfer_to_shop) {
        echo "
        <script>
        document.cookie = 'alert = 4;';
        window.location.href='Stock';
</script>
        ";
    } else {
        echo "
        <script>
        document.cookie = 'alert = 5;';
        window.location.href='Stock';
</script>
        ";
    }
}


if (isset($_POST['add_customer'])) {
    $customer_name = $db_handle->checkValue($_POST['customer_name']);
    $contact_no = $db_handle->checkValue($_POST['contact_no']);
    $insert_customer = $db_handle->insertQuery("INSERT INTO `customer_data`(`customer_name`, `contact_phone`, `inserted_at`) VALUES ('$customer_name','$contact_no','$inserted_at')");
    if ($insert_customer) {
        echo "
        <script>
        document.cookie = 'alert = 4;';
        window.location.href='Customer';
</script>
        ";
    } else {
        echo "
        <script>
        document.cookie = 'alert = 5;';
        window.location.href='Customer';
</script>
        ";
    }
}


if (isset($_POST['add_invoice'])) {
    $shop_stocks = $_POST['shop_stock'];
    $prices = $_POST['price'];
    $quantities = $_POST['quantity'];
    $subtotals = $_POST['sub_total'];

    $invoice_id = substr(md5(uniqid()), 0, 6);
    $check = 0;
    while ($check == 0) {
        $check_id = $db_handle->numRows("select * from invoice_data where invoice_id='$invoice_id'");
        if ($check_id == 0) {
            $check = 1;
            break;
        } else {
            $invoice_id = substr(md5(uniqid()), 0, 6);
        }
    }

    // Loop through the arrays to process the form data
    for ($i = 0; $i < count($shop_stocks); $i++) {
        $shop_stock = $shop_stocks[$i];
        $price = $prices[$i];
        $quantity = $quantities[$i];
        $total = $subtotals[$i];

        $product_fetch = $db_handle->runQuery("select * from shop_stock where shop_stock_id='$shop_stock'");
        $product = $product_fetch[0]['stock_id'];

        $fetch_product_code = $db_handle->runQuery("select product_id from primary_stock where p_stock_id='$product'");
        $product_id = $fetch_product_code[0]['product_id'];
        if ($fetch_product_code) {
            $insert_product = $db_handle->insertQuery("INSERT INTO `invoice_product`(`product_code`, `selling_price`, `quantity`, `total_price`, `inserted_at`,`invoice_id`) VALUES ('$product_id','$price','$quantity','$total','$inserted_at','$invoice_id')");
            $new_quantity = $product_fetch[0]['quantity'] - $quantity . '<br>';
            $update_stock = $db_handle->insertQuery("update shop_stock set sell_quantity='$quantity' where shop_stock_id='$shop_stock'");
        }

    }
    $customer = $db_handle->checkValue($_POST['customer']);
    $subtotal = $db_handle->checkValue($_POST['subtotal']);
    $discount = $db_handle->checkValue($_POST['discount']);
    $grand_total = $db_handle->checkValue($_POST['grand_total']);
    $insert_invoice = $db_handle->insertQuery("INSERT INTO `invoice_data`(`invoice_id`, `customer_id`, `sub_total`, `grand_total`, `discount`, `inserted_at`) VALUES ('$invoice_id','$customer','$subtotal','$grand_total','$discount','$inserted_at')");
    if ($insert_invoice) {
        echo "<script>
alert ('Invoice Created Successfully');
window.location.href = 'Print_Invoice?id=$invoice_id';
</script>";
    }
}