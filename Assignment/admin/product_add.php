<?php
require '../_base.php';

// Check if user is logged in as admin
if (!isset($_SESSION['adminID'])) {
    header('Location: index.php');
    exit;
}

$message = '';

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
        redirect('/admin/product_list.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Add product</title>
<!-- Include your CSS here -->
</head>
<body>
<h1>Add New product</h1>

<?php if ($message): ?>
    <p style="color: red;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="post" action="">
    <label for="productName">Product Name:</label><br/>
    <input type="text" id="productName" productName="productName" required /><br/><br/>
    
    <label for="email">Email:</label><br/>
    <input type="email" id="email" productName="email" required /><br/><br/>
    
    <button type="submit">Add product</button>
</form>

<p><a href="product_list.php">Back to product List</a></p>
</body>
</html>