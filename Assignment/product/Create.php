<?php 
require '../_base.php';

$_title = 'Product Form';

include '../admin/admin_head.php';

    // Auto-generate productID
    $stmt = $_db->query('SELECT MAX(productID) as maxID FROM product');
    $row = $stmt->fetch(PDO::FETCH_OBJ);
    $nextNum = ($row->maxID ?? 0) + 1;
    $autoProductID = $nextNum;

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
        $productID    = $autoProductID;
        $productName  = req('productName');
        $productDesc  = req('productDesc');
        $productCat1  = req('productCat1');
        $productCat2  = req('productCat2');
        $productCat3  = req('productCat3');
        $productPrice = req('productPrice');
        $productQty   = req('productQty');
        $productPhoto = get_file('productPhoto');
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
        redirect('/product/list.php');
    }

    $displayPhoto = '../images/photo.jpg'; // default placeholder

    if (is_post() && isset($productPhoto) && $productPhoto != '') {
        $displayPhoto = 'photos/' . $productPhoto; // path to the uploaded photo
    }

?>

<div class = "product_container">
    <div class = "product_insert">
        <h2 class = "product_insert_title">Product Form</h2>
        <form method = "post" enctype = "multipart/form-data">
            
            <!-- Automated Input Product ID-->
            <div class = "product_insert_input">
                <label for = "productID">Product ID</label>
                <input type = "text" id = "productID" value = "<?= htmlspecialchars($autoProductID) ?>" readonly>
                <input type = "hidden" name = "productID" value = "<?= htmlspecialchars($autoProductID) ?>">
                <?= err('productID') ?>
            </div>

            <!-- Input Product Name -->
            <div class = "product_insert_input">
                <label for = "productName">Product Name</label>
                <?= html_text('productName','maxlength = "150"') ?>
                <?= err('productName') ?>
            </div>

            <!-- Input Product Description -->
            <div class = "product_insert_input_long">
                <label for = "productDesc">Product Description</label>
                <textarea name = "productDesc" id = "productDesc" maxlength = "1000" placeholder = "Maximum 1000 characters"></textarea>
                <?= err('productDesc') ?>
            </div>

            <!-- Input Product Price -->
            <div class = "product_insert_input">
                <label for = "productPrice">Product Price (RM) </label>
                <?= html_text('productPrice','min = "0.01" max = "1200.00" step = "0.01" placeholder = "Between 0.01 - 1200.00"') ?>
                <?= err('productPrice') ?>
            </div>

            <!-- Input Product Quantity -->
            <div class = "product_insert_input">
                <label for = "productQty">Product Quantity</label>
                <?= html_text('productQty','min = "1" max = "999"') ?>
                <?= err('productQty') ?>
            </div>

            <!-- Radio Type - Connectivity -->
            <div class="product_insert_select">
                <span class="radio-title">Connectivity</span>

                <div class="radio-group">
                    <input type="radio" id="wired" name="productCat1" value="Wired">
                    <label for="wired">Wired</label>

                    <input type="radio" id="wireless" name="productCat1" value="Wireless">
                    <label for="wireless">Wireless</label>
                </div>
            </div>

            <!-- Radio Type - Fit Type -->
            <div class="product_insert_select">
                <span class="radio-title">Fit Type</span>

                <div class="radio-group">
                    <input type="radio" id="in-ear" name="productCat2" value="In-ear">
                    <label for="in-ear">In-ear</label>

                    <input type="radio" id="over-ear" name="productCat2" value="Over-ear">
                    <label for="over-ear">Over-ear</label>
                </div>
            </div>


            <!-- Radio Type - Acoustic -->
            <div class="product_insert_select">
                <span class="radio-title">Acoustic</span>

                <div class="radio-group">
                    <input type="radio" id="noise" name="productCat3" value="Noise-canceled">
                    <label for="noise">Noise-canceled</label>

                    <input type="radio" id="balanced" name="productCat3" value="Balanced">
                    <label for="balanced">Balanced</label>

                    <input type="radio" id="clear" name="productCat3" value="Clear vocals">
                    <label for="clear">Clear vocals</label>
                </div>
            </div>


            <!-- Input Product Photo -->
            <div class = "product_insert_input">
                <label for = "productPhoto">Product Photo</label>
                <label class = "upload" tabindex = "0">
                <label class="upload" tabindex="0">
                    <?= html_file('productPhoto', 'image/*', 'hidden') ?>
                    <img id="previewImg" src="<?= htmlspecialchars($displayPhoto) ?>" alt="Product Photo">
                    <?= err('productPhoto') ?>
                </label>

            </div>

            <!-- Submit Button -->
            <div class = "product_insert_button">
                <button type = "submit" value = "Submit">Submit</button>
                <button type = "reset" value = "Reset">Reset</button>
            </div>
        </form>

        <!-- Add this after the form to display temp messages -->
        <div class = "message-container">
            <?php if (temp('info')): ?>
                <div class = "alert alert-success">
                    <?= temp('info') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div> 
<style>.product_insert_select {
    margin-bottom: 25px;
}

/* Title spacing */
.radio-title {
    display: block;
    font-weight: 600;
    color: #333;
}

/* make radio to one row */
.radio-group {
    margin-left: 10px;
    display: flex;
    gap: 12px;             
    flex-wrap: nowrap;    /* make one row */
    align-items: center;
}

/* hide default radio */
.radio-group input[type="radio"] {
    display: none;
}

/* button style */
.radio-group label {
    padding: 10px 18px;
    border-radius: 20px;
    border: 2px solid #d1d5db;
    background: #fff;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s ease;
    white-space: nowrap;  /* ⬅ prevent text wrapping */
}

/* hover */
.radio-group label:hover {
    border-color: #6366f1;
    color: #6366f1;
}

/* selected */
.radio-group input[type="radio"]:checked + label {
    background: #6366f1;
    border-color: #6366f1;
    color: #fff;
}

</style>

<script>
document.querySelector('input[name="productPhoto"]').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const img = document.getElementById('previewImg');
    img.src = URL.createObjectURL(file);
});
</script>
