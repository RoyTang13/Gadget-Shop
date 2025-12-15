<?php
require '../_base.php';

$_title = 'Update Product | Admin';

include '../admin/admin_head.php';


if (!isset($_SESSION['adminID'])) {
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    temp('info','Invalid product ID');
    redirect('product/list.php');
}

$stm = $_db->prepare('SELECT * FROM product WHERE productID = :id');
$stm->execute([':id' => $id]);
$product = $stm->fetch(PDO::FETCH_OBJ);

if (!$product) {
    temp('info','Product not found');
    redirect('product/list.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['productName'] ?? '');
    $price = filter_input(INPUT_POST, 'productPrice', FILTER_VALIDATE_FLOAT);
    $qty   = filter_input(INPUT_POST, 'productQty', FILTER_VALIDATE_INT);
    $desc  = trim($_POST['productDesc'] ?? '');

    if ($name === '') 
        $errors[] = 'Product name is required';

    if ($price === false || $price <= 0) 
        $errors[] = 'Invalid price';

    if ($qty === false || $qty < 0) 
        $errors[] = 'Invalid quantity';

    if (!$errors) {
        $stm = $_db->prepare('
            UPDATE product 
            SET
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

        temp('info', 'Product updated successfully!');
        header('Location: /product/list.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<section style = "display: flex; justify-content: center; align-items: center; min-height: 80vh; gap: 20px;">
<html>
<head>
    <title>Update Product</title>
    <meta charset="utf-8">
</head>
<body>

<h1>Update Product (ID: <?= htmlspecialchars($product->productID) ?>)</h1> <!-- TO MODIFY -->

<?php if ($errors): ?>
    <ul style="color:red">
        <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach ?>
    </ul>
<?php endif ?>

<form method = "post">
    <label>Product Name</label><br>
    <input name = "productName" 
           value = "<?= htmlspecialchars($product->productName) ?>"><br><br>

    <label>Price (RM)</label><br>
    <input type = "number" 
           step = "0.01" 
           name = "productPrice" 
           value = "<?= htmlspecialchars($product->productPrice) ?>"><br><br>

    <label>Quantity</label><br>
    <input type = "number" 
           name = "productQty" 
           value = "<?= htmlspecialchars($product->productQty) ?>"><br><br>

    <label>Description</label><br>
    <textarea name = "productDesc" 
              rows = "4" 
              cols = "50"><?= htmlspecialchars($product->productDesc) ?></textarea><br><br>

    <button type = "submit" href = "product/list.php">Save Changes</button>
    <a href = "list.php">Cancel</a>
</form>

</body>
</html>
</section>

<!-- TO MODIFY -->
<style>
 
</style>

