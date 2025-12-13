<?php 
require '../_base.php';

$_title = 'Product Form | TechNest';

include '../_head.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $productID = $_POST['productID'];
        $productName = $_POST['productName'];
        $productPrice = $_POST['productPrice'];
        $productDesc = $_POST['productDesc'];
        $productQty = $_POST['productQty'];
        $productPhoto = $_FILES['productPhoto'];
    }
    $productID = $productName = $productPrice = $productDesc = $productQty = $productCat1 = $productCat2 = $productCat3 = $productPhoto = '';

    // Input
    if(is_post()){
        $productID    = req('productID');
        $productName  = req('productName');
        $productDesc  = req('productDesc');
        $productCat1  = req('productCat1');
        $productCat2  = req('productCat2');
        $productCat3  = req('productCat3');
        $productPrice = req('productPrice');
        $productQty   = req('productQty');
        $productPhoto = get_file('productPhoto');
    }

    // Validate product ID uniqueness
    if ($productID == '') {
        $_err['productID'] = 'Product ID is required.';
    } 
    else if (!is_unique($productID, 'product', 'productID')) {
        $_err['productID'] = 'Product ID has been existed. Please try another one.';
    }
    else if (strlen($productID) > 5) {
        $_err['productID'] = 'Maximum length is 5.';
    }
    else if (!preg_match('/^P[0-9]{4}$/', $productID)) {
        $_err['productID'] = 'Invalid product ID format. Example: P0001';
    }

    // Validate product name
    if ($productName == '') {
        $_err['productName'] = 'Product name is required.';
    }
    else if (strlen($productName) > 150) {
        $_err['productName'] = 'Maximum length is 150.';
    }

    // Validate product description
    if ($productDesc == '') {
        $_err['productDesc'] = 'Product description is required.';
    }
    else if (strlen($productDesc) > 1000) {
        $_err['productDesc'] = 'Maximum length is 1000.';
    }

    // Validate product price
    if ($productPrice == '') {
        $_err['productPrice'] = 'Product price is required.';
    }
    else if ($productPrice < 0.01 || $productPrice > 1200.00) {
        $_err['productPrice'] = 'Product price must be between 0.01 and 1200.00.';
    }
    else if (!is_numeric($productPrice)) {
        $_err['productPrice'] = 'Product price must be a number.';
    }
    else if (!preg_match('/^\-?\d+(\.\d{1,2})?$/', $productPrice)) {
        $_err['productPrice'] = 'Product price must be a number with up to 2 decimal places.';
    }
    
    // Validate product quantity
    if ($productQty == '') {
        $_err['productQty'] = 'Product quantity is required.';
    }
    else if ($productQty < 1 || $productQty > 999) {
        $_err['productQty'] = 'Product quantity requires between 1 - 999.';
    }
    else if (!is_numeric($productQty)) {
        $_err['productQty'] = 'Product quantity must be a number.';
    }
    else if (!preg_match('/^\d+$/', $productQty)) {
        $_err['productQty'] = 'Product quantity must be a whole number.';
    }

    // Validate photo (file)
    if (!$productPhoto) {
        $_err['productImg'] = 'Product photo is required.';
    }
    else if (!str_starts_with($productPhoto -> type, 'image/')) {
        $_err['productImg'] = 'Photo must be image.';
    }
    else if ($productPhoto -> size > 3 * 1024 * 1024) {
        $_err['productImg'] = 'Photo maximum 3MB.';
    }

    // DB operation
    if (!$_err) {
        $photoFile = $productPhoto;
        $productPhoto = uniqid() . '.jpg';

        require_once '../lib/SimpleImage.php';
        $img = new SimpleImage();
        $img->fromFile($photoFile->tmp_name)
            ->thumbnail(300, 300)
            ->toFile("photos/$productPhoto", 'image/jpeg');

        $stm = $_db->prepare('
            INSERT INTO product (productID, productName, productPrice, productDesc, productQty, productCat1, productCat2, productCat3, productPhoto)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $stm->execute([$productID, $productName, $productPrice, $productDesc, $productQty, $productCat1, $productCat2, $productCat3, $productPhoto]);

        temp('info', 'Record successfully inserted!');
        redirect('product.php');
    }

?>

<div class = "product_container">
    <div class = "product_form">
        <h2 class = "product_form_title">Product Form</h2>
        <form method = "post" enctype = "multipart/form-data">
            
            <!--Input Product ID-->
            <div class = "product_form_input">
                <label for = "productID">Product ID</label>
                <?= html_text('productID','maxlength = "5" data-upper placeholder = "Example: P0001"') ?>
                <?= err('productID') ?>
            </div>

            <!-- Input Product Name -->
            <div class = "product_form_input">
                <label for = "productName">Product Name</label>
                <?= html_text('productName','maxlength = "150"') ?>
                <?= err('productName') ?>
            </div>

            <!-- Input Product Description -->
            <div class = "product_form_input_long">
                <label for = "productDesc">Product Description</label>
                <textarea name = "productDesc" id = "productDesc" maxlength = "1000" placeholder = "Maximum 1000 characters"></textarea>
                <?= err('productDesc') ?>
            </div>

            <!-- Input Product Price -->
            <div class = "product_form_input">
                <label for = "productPrice">Product Price (RM) </label>
                <?= html_text('productPrice','min = "0.01" max = "1200.00" step = "0.01" placeholder = "Between 0.01 - 1200.00"') ?>
                <?= err('productPrice') ?>
            </div>

            <!-- Input Product Quantity -->
            <div class = "product_form_input">
                <label for = "productQty">Product Quantity</label>
                <?= html_text('productQty','min = "1" max = "999"') ?>
                <?= err('productQty') ?>
            </div>

            <!-- Radio Type - Connectivity -->
            <div class = "product_form_select">
                <label for = "productCat1">Connectivity</label>
                <input type = "radio" name = "productCat1" value = "Wired">Wired
                <input type = "radio" name = "productCat1" value = "Wireless">Wireless
            </div>

            <!-- Radio Type - Fit Type -->
            <div class = "product_form_select">
                <label for = "productCat2">Fit Type</label>
                <input type = "radio" name = "productCat2" value = "In-ear">In-ear
                <input type = "radio" name = "productCat2" value = "Over-ear">Over-ear
            </div>

            <!-- Radio Type - Acoustic -->
            <div class = "product_form_select">
                <label for = "productCat3">Acoustic</label>
                <input type = "radio" name = "productCat3" value = "Noise-canceled">Noise-canceled
                <input type = "radio" name = "productCat3" value = "Balanced">Balanced
                <input type = "radio" name = "productCat3" value = "Clear vocals">Clear vocals
            </div>

            <!-- Input Product Photo -->
            <div class = "product_form_input">
                <label for = "productPhoto">Product Photo</label>
                <label class = "upload" tabindex = "0">
                    <?= html_file('productPhoto', 'image/*', 'hidden') ?>
                    <img src = "../images/photo.jpg">
                <?= err('productPhoto') ?>
            </div>

            <!-- Submit Button -->
            <div class = "product_form_button">
                <button type = "submit" value = "Submit">Submit</button>
                <button type = "reset" value = "Reset">Reset</button>
            </div>
        </form>
    </div>

</div> 
