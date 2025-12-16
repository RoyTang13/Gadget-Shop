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

    // Handle photo upload
    $photoSQL = '';
    $photoParams = [];
        if (!empty($_FILES['productPhoto']['tmp_name'])) {
           $upload = $_FILES['productPhoto'];
           if (str_starts_with($upload['type'], 'image/')) {
               $newPhoto = uniqid() . '.jpg';
               move_uploaded_file($upload['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/photos/' . $newPhoto);
               $photoSQL = ", productPhoto = :photo";
               $photoParams[':photo'] = $newPhoto;
           }
       }   

    if (!$errors) {
         $sql = "
            UPDATE product 
            SET
                productName = :name,
                productPrice = :price,
                productQty = :qty,
                productDesc = :desc
                $photoSQL
            WHERE productID = :id
        ";

        $params = array_merge([
            ':name'  => $name,
            ':price' => $price,
            ':qty'   => $qty,
            ':desc'  => $desc,
            ':id'    => $id
        ], $photoParams);

        $stm = $_db->prepare($sql);
        $stm->execute($params);


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

<form method="post" class="edit-layout" enctype="multipart/form-data">  

    <!-- LEFT SIDE -->
    <div class="left-panel">
        <h3>Product Preview</h3>

        <!-- Insert Photo -->
        <?php
        $img = $product->productPhoto && file_exists($_SERVER['DOCUMENT_ROOT'] . '/photos/' . $product->productPhoto)
            ? '/photos/' . $product->productPhoto
            : '/photos/no-image.png';
        ?>

        <img id="previewImg"
            src="<?= $img ?>"
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

        <label>Product Photo</label>
        <input type="file" name="productPhoto" accept="image/*">

        <div class="buttons">
            <button type="submit">Save Changes</button>
            <a class="cancel" href="list.php">Cancel</a>
        </div>
    </div>

</form>


</body>
</html>
</section>

<script>
document.querySelector('input[name="productPhoto"]').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;

    const img = document.getElementById('previewImg');
    img.src = URL.createObjectURL(file);
});
</script>
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
