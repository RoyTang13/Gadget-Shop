<?php
require '../_base.php';

if (empty($_GET['name'])) {
    echo "Product not found";
    redirect('/product/page.php');
}

$productName = $_GET['name'];

// Query product details
$sql = "SELECT * FROM product WHERE productName = :name LIMIT 1";
$stmt = $_db->prepare($sql);
$stmt->execute([':name' => $productName]);
$product = $stmt->fetch(PDO::FETCH_OBJ);

if (!$product) {
    redirect('/product/page.php');
}

$_title = $product->productName . " | TechNest";
include '../_head.php';
?>

<div class = "product-page">
    <div class = "product-left">
        <div class = "product-image-box">
            <a href = "/product/product.php" class = "back-btn">← Back</a>

            <img class = "product-photo"
            src = "/photos/<?= htmlspecialchars($product->productPhoto) ?>" 
            alt = "<?= htmlspecialchars($product->productName) ?>">
        </div>
    </div>

    <div class = "product-right">
        <h1><?= htmlspecialchars($product->productName) ?></h1>

        <div class = "description-box">
            <p class = "product-description">
                <?= nl2br(htmlspecialchars($product->productDesc ?? "No description provided.")) ?>
            </p>
        </div>

        <div class = "product-tags">
            <span class = "tag"><?= htmlspecialchars($product->productCat1) ?></span>
            <span class = "tag"><?= htmlspecialchars($product->productCat2) ?></span>
            <span class = "tag"><?= htmlspecialchars($product->productCat3) ?></span>
        </div>

        <div class = "product-price">
            RM <?= number_format($product->productPrice, 2) ?>
        </div>

        <div class="quantity-section">
    <button type="button" class="qty-btn" id="qtyMinus">−</button>

        <input
            type="number"
            id="qtyInput"
            name="quantity"
            value="1"
            min="1">

        <button type="button" class="qty-btn" id="qtyPlus">+</button>
    </div>

        <div class = "action-buttons">
        <div class="action-buttons">
            <form method="post" action="/product/add_to_cart.php">
                <input type="hidden" name="productID" value="<?= $product->productID ?>">
                <button type="submit" name="add_to_cart">Add to Cart</button>
            </form>
        </div>
    </div>  
</div>

<style>
/* Product Page Layout */
.product-page {
    width: 90%;
    margin: 60px auto;
    display: flex;
    gap: 40px;
}

.product-left {
    flex: 1;
}

/* Image Box with Border */
.product-image-box {
    position: relative;
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 12px;
}

.back-btn {
    position: absolute;
    top: 10px;
    left: 10px;
    background: linear-gradient(to bottom, #e1c8ff, #c494ff);
    padding: 6px 12px;
    border-radius: 12px;
    text-decoration: none;
    font-family:'Courier New', Courier, monospace;
    font-size: 16px;
    color: black;
    border: 1px solid #a154ff;
    box-shadow: 0 4px 8px rgba(164, 17, 255, 0.379);
    z-index: 8;
}

.back-btn:hover {
    background: rgba(255,255,255,1);
}

.product-photo {
    width: 100%;
    border-radius: 8px;
}

/* Right Column */
.product-right {
    background: linear-gradient(to bottom, #e1c8ff, #c494ff);
    box-shadow: 0 4px 8px rgba(164, 17, 255, 0.379);
    border: 1px solid #a154ff;
    border-radius: 12px;
    padding: 15px;
    flex: 1;
}

/* Product Name */
.product-right h1 {
    margin-bottom: 20px;
    text-align: center;
    font-family: 'Courier New', Courier, monospace;
}

/* Description Box */
.description-box {
    background: linear-gradient(to bottom, #68077dd5, #440552d5);
    box-shadow: 0 4px 8px rgba(164, 17, 255, 0.379);
    border: 1px solid #a154ff;
    border-radius: 12px;
}

/* Product Description */
.product-description {
    padding: 15px;
    font-size: 16px;
    line-height: 1.5;
    color: #d4d4d4ff;
    font-family: 'Courier New', Courier, monospace;
}

/* Product Tags */
.product-tags .tag {
    display: inline-block;
    padding: 5px 15px 5px 15px;
    margin-top: 8px;
    margin-right: 8px;
    font-family: 'Courier New', Courier, monospace;
    font-size: 16px;
    font-weight: 600;
    background-color: #ebc8ff;
    border: #000 solid 0.6px;
    border-radius: 8px;
}

/* Product Price */
.product-price {
    font-size: 24px;
    margin: 15px 0;
    font-family: 'Courier New', Courier, monospace;
    color: #000000ff;
}

/* Product Quantity Box */
.quantity-section {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 20px 0;
}

/* Product Quantity Buttons */
.qty-btn {
        width: 80px;
        height: 32px;
        padding: 0px 0px 3px 0px;
        border: 1.6px solid #b357ff;
        background: #f1b9ff;
        color: #560065;
        cursor: pointer;
        font-size: 20.8px;
        font-weight: bold;
}

.qty-btn:hover {
    background: #f4c9ff;
    border-color: #aa43ff;
}

/* Product Quantity Input */
#qtyInput {
    width: 275px;
    height: 26px;
    padding: 2px 0px 0px 0px;
    border: 1.6px solid #b357ff;
    background: #f1b9ff;
    color: #560065;
    cursor: text;
    font-family:'Courier New', Courier, monospace;
    font-size: 14px;
    font-weight: 600;
    text-align: center;
}

#qtyInput::-webkit-inner-spin-button,
#qtyInput::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

#qtyInput:focus {
    outline: none;
    background: #f4c9ff;
    border-color: #aa43ff;
}

/* Wishlist & Cart Buttons */
.action-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
    margin-bottom: 20px;
}

.order-btn, .cart-btn {
    padding: 12px 25px;
    border: #000 solid 2px;
    border-radius: 8px;
    background-color: #be06ec;
    color: #fff;
    padding: 2px 13px;
    width: 210px;
    height: 45px;
    font-family: 'Courier New', Courier, monospace;
    font-size: 16px;
    cursor: pointer;
}

body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url(/images/purple\ wave\ background.jpg);
        background-size: cover;
        background-attachment: fixed;
        opacity: 0.6;
        z-index: -1;
        pointer-events: none;
    }
</style>

<script>
const qtyInput = document.getElementById("qtyInput");

document.getElementById("qtyMinus").addEventListener("click", () => {
    if (qtyInput.value > 1) qtyInput.value--;
});

document.getElementById("qtyPlus").addEventListener("click", () => {
    qtyInput.value++;
});
</script>
