<?php
require '../_base.php';
show_popup();

// Make sure user is logged in
if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit;
}

$userID = $_SESSION['userID'];

// Make sure there are selected items
if (empty($_SESSION['checkout_items'])) {
    set_popup('No items selected for checkout.');
    redirect('/product/cart.php');
    exit;
}

// Fetch selected cart items from database
$selectedIDs = $_SESSION['checkout_items'];
$placeholders = implode(',', array_fill(0, count($selectedIDs), '?'));

$sql = "SELECT c.id, c.productID, c.quantity, p.productName, p.productPrice, p.productPhoto
        FROM cart c
        JOIN product p ON c.productID = p.productID
        WHERE c.userID = ? AND c.id IN ($placeholders)";
$stmt = $_db->prepare($sql);
$stmt->execute(array_merge([$userID], $selectedIDs));
$cartItems = $stmt->fetchAll(PDO::FETCH_OBJ);

$_title = "Checkout | TechNest";
include '../_head.php';
?>

<main>
<h1 class="cart-title">🧾 Checkout</h1>

<form method="post" action="/product/checkout_process.php">
    <div class="cart-wrapper">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $grandTotal = 0;
                foreach ($cartItems as $item):
                    $itemTotal = $item->productPrice * $item->quantity;
                    $grandTotal += $itemTotal;
                ?>
                <tr>
                    <td class="product-cell">
                        <img src="/photos/<?= $item->productPhoto ?>" alt="">
                        <span><?= htmlspecialchars($item->productName) ?></span>
                    </td>
                    <td>RM <?= number_format($item->productPrice, 2) ?></td>
                    <td><?= $item->quantity ?></td>
                    <td>RM <?= number_format($itemTotal, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align:right; font-weight:bold; font-size:1.1em;">Total for selected items:</td>
                    <td style="font-weight:bold; font-size:1.3em; color:#000; background: linear-gradient(to right, #c494ff, #e1c8ff); padding:5px 10px; border-radius:4px; text-align:center; box-shadow: 0 1px 3px rgba(0,0,0,0.2);">
                        RM <?= number_format($grandTotal, 2) ?>
                    </td>
                </tr>
            </tfoot>

        </table>
    </div>

    <div class="cart-actions" style="display:flex; gap:10px;">
    <!-- Return to Cart Button -->
    <a href="/product/cart.php" class="btn-return" style="padding:10px 20px; background: linear-gradient(to right, #c494ff, #e1c8ff); color:#000; border-radius:5px; text-decoration:none;">
        ← Return to Cart
    </a>

    <!-- Confirm and Pay Button -->
    <button type="submit" name="confirm_checkout" class="btn-checkout" style="padding:10px 20px; background: linear-gradient(to right, #c494ff, #e1c8ff); color:#000; border:none; border-radius:5px;">
        Confirm and Pay →
    </button>
</div>
</form>
</main>
