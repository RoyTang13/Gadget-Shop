<?php
/******************************
 * FILE: admin/product_edit.php
 * PURPOSE: Secure admin-only edit page
 ******************************/

require '../_base.php';
$_title = 'Product Edit';
include 'admin_head.php';

// ================================
// 1. ADMIN-ONLY ACCESS CONTROL
// ================================
// Assumes admin login sets: $_SESSION['admin'] = true OR admin object
if (!isset($_SESSION['adminID'])) {
    header('Location: index.php');
    exit;
}

// ================================
// 2. ID VALIDATION
// ================================
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    http_response_code(400);
    die('Invalid product ID');
}

// ================================
// 3. FETCH PRODUCT SAFELY
// ================================
$stm = $_db->prepare('SELECT * FROM product WHERE productID = :id');
$stm->execute([':id' => $id]);
$product = $stm->fetch(PDO::FETCH_OBJ);

if (!$product) {
    http_response_code(404);
    die('Product not found');
}

// ================================
// 4. HANDLE UPDATE (POST)
// ================================
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['productName'] ?? '');
    $price = filter_input(INPUT_POST, 'productPrice', FILTER_VALIDATE_FLOAT);
    $qty   = filter_input(INPUT_POST, 'productQty', FILTER_VALIDATE_INT);
    $desc  = trim($_POST['productDesc'] ?? '');

    if ($name === '') $errors[] = 'Product name is required';
    if ($price === false || $price <= 0) $errors[] = 'Invalid price';
    if ($qty === false || $qty < 0) $errors[] = 'Invalid quantity';

    if (!$errors) {
        $stm = $_db->prepare('
            UPDATE product SET
                productName = :name,
                productPrice = :price,
                productQty = :qty,
                productDesc = :desc
            WHERE productID = :id
        ');

        $stm->execute([
            ':name'  => $name,
            ':price' => $price,
            ':qty'   => $qty,
            ':desc'  => $desc,
            ':id'    => $id
        ]);

        header('Location: product_list.php?updated=1');
        exit;
    }
}
?>

<!DOCTYPE html>
<section style="display:flex; justify-content:center; align-items:center; min-height:80vh; gap:20px;">
<html>
<head>
    <title>Edit Product</title>
    <meta charset="utf-8">
</head>
<body>

<h1>Edit Product (ID: <?= htmlspecialchars($product->productID) ?>)</h1>

<?php if ($errors): ?>
    <ul style="color:red">
        <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach ?>
    </ul>
<?php endif ?>

<form method="post">
    <label>Product Name</label><br>
    <input name="productName" value="<?= htmlspecialchars($product->productName) ?>"><br><br>

    <label>Price (RM)</label><br>
    <input type="number" step="0.01" name="productPrice" value="<?= htmlspecialchars($product->productPrice) ?>"><br><br>

    <label>Quantity</label><br>
    <input type="number" name="productQty" value="<?= htmlspecialchars($product->productQty) ?>"><br><br>

    <label>Description</label><br>
    <textarea name="productDesc" rows="4" cols="50"><?= htmlspecialchars($product->productDesc) ?></textarea><br><br>

    <button type="submit">Save Changes</button>
    <a href="product_list.php">Cancel</a>
</form>

</body>
</html>
</section>

<?php include '../_foot.php'; ?>