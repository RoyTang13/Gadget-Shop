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
<section class="container">
<html>
<head>
    <title>Update Product</title>
</head>
<body>

<h1>Update Product (ID: <?= htmlspecialchars($product->productID) ?>)</h1> 

<?php if ($errors): ?>
    <ul style="color:red">
        <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach ?>
    </ul>
<?php endif ?>

<form method="post" class="edit-layout">

    <!-- LEFT SIDE -->
    <div class="left-panel">
        <h3>Product Preview</h3>

        <img src="/photos/<?= htmlspecialchars($product->productPhoto) ?>" 
             alt="Product Image" 
             class="product-image">

        <p><strong>ID:</strong> <?= htmlspecialchars($product->productID) ?></p>

        <p><strong>Current Price:</strong> RM <?= htmlspecialchars($product->productPrice) ?></p>

        <p><strong>Stock:</strong> <?= htmlspecialchars($product->productQty) ?></p>
    </div>

    <!-- RIGHT SIDE -->
    <div class="right-panel">
        <h3>Edit Details</h3>

        <label>Product Name</label>
        <input type="text" name="productName"
               value="<?= htmlspecialchars($product->productName) ?>">

        <label>Price (RM)</label>
        <input type="number" step="0.01" name="productPrice"
               value="<?= htmlspecialchars($product->productPrice) ?>">

        <label>Quantity</label>
        <input type="number" name="productQty"
               value="<?= htmlspecialchars($product->productQty) ?>">

        <label>Description</label>
        <textarea name="productDesc" rows="5"><?= htmlspecialchars($product->productDesc) ?></textarea>

        <div class="buttons">
            <button type="submit">Save Changes</button>
            <a class="cancel" href="list.php">Cancel</a>
        </div>
    </div>

</form>


</body>
</html>
</section>


<style>
/* Page base */
body {
    font-family: Arial, sans-serif;
    background: linear-gradient(#e0e7ff, #e0e7ff);
    padding: 20px;
}

.container {
    width: 10000px;
    margin: auto;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 6px 14px rgba(0,0,0,0.1);
    border: 1px solid #000000ff;
}

h1 {
    text-align: center;
    margin-bottom: 25px;
}


/* Layout */
.edit-layout {
    display: flex;
    gap: 30px;
}

/* Left panel */
.left-panel {
    flex: 1;
    background: #fafafa;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    font-size: 18px;
}

.left-panel h3 {
    margin-bottom: 15px;
}

.product-image {
    max-width: 100%;
    max-height: 220px;
    border-radius: 6px;
    margin-bottom: 15px;
}

/* Right panel */
.right-panel {
    flex: 2;
}

.right-panel h3 {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
}

input,
textarea {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border-radius: 4px;
    border: 1px solid #ccc;
    font-size: 17px;
}

/* Buttons */
.buttons {
    margin-top: 25px;
    display: flex;
    gap: 10px;
}

button {
    background: #7b2ff7;
    color: #fff;
    border: none;
    padding: 12px 20px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

button:hover {
    background: #5a1fdc;
}

.cancel {
    background: #999;
    color: #fff;
    padding: 12px 20px;
    border-radius: 4px;
    text-decoration: none;
}

.cancel:hover {
    background: #555;
}

/* Responsive */
@media (max-width: 768px) {
    .edit-layout {
        flex-direction: column;
    }
}
</style>
