<?php

require '../_base.php';
show_popup();

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
} else {
    // handle not logged in
    header('Location:/page/login.php');
    exit;
}

// Fetch cart items for current user
function get_user_cart_items() {
    global $_db, $userID;
    $sql = "SELECT c.id, c.productID, c.quantity, p.productName, p.productPrice, p.productPhoto
            FROM cart c
            JOIN product p ON c.productID = p.productID
            WHERE c.userID = ?";
    $stmt = $_db->prepare($sql);
    $stmt->execute([$userID]);
    return $stmt->fetchAll(PDO::FETCH_OBJ);

}
if (isset($_POST['add_to_cart'])) {
    $productID = $_POST['productID'];
    $quantity = intval($_POST['quantity']);
    // Validate and add to cart
    update_cart($productID, $quantity);
    set_popup('Product added to cart.');
    redirect('/product/cart.php');
    exit;
}

$cartItems = get_user_cart_items();

// delete actions
if (is_post()) {

    // Remove item from cart
    if (isset($_POST['remove'])) {
        $idToRemove = intval($_POST['remove']);
        $stm = $_db->prepare(
            "DELETE FROM cart WHERE id = ? AND userID = ?"
        );
        $stm->execute([$idToRemove, $userID]);
        set_popup('Item removed from cart.');
        redirect('/product/cart.php');
        exit;
    }
}
//Edit Quantity (+ / -)
if (is_post()) {
    if (isset($_POST['increase']) || isset($_POST['decrease'])) {
        $cartID = intval($_POST['cartID']);
        $newQty = intval($_POST['quantity']);
        if ($newQty < 1) $newQty = 1;

        $stmt = $_db->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND userID = ?");
        $stmt->execute([$newQty, $cartID, $userID]);

        redirect('/product/cart.php'); // refresh page
        exit;
    }
}

//checklist for cart to proceed checkout
if (isset($_POST['checkout'])) {
    if (!empty($_POST['selected'])) {
        $selectedItems = $_POST['selected']; // array of cart IDs
        $_SESSION['checkout_items'] = $selectedItems; // store in session
        redirect('/product/checkout.php');
        exit;
    } else {
        set_popup('Please select at least one item to checkout.');
        redirect('/product/cart.php');
        exit;
    }
}



$_title = "Your Cart | TechNest";
include '../_head.php';

?>

<style>
input[type="checkbox"] {
    transform: scale(1.5);
    transition: transform 0.2s;
    margin: 5px;
}
</style>

<main>
<h1 class="cart-title">🛒 Shopping Cart 🛒</h1>

<form method="post" action="/product/cart.php">
    <div class="cart-wrapper">
        <table class="cart-table">
            <thead>
                <tr>
                    <th> <input type="checkbox" id="select-all" /></th>
                    <th>Product</th>
                    <th>Price</th>
                    <th style="width:140px;">Quantity</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
            <?php
            $totalAmount = 0;
            foreach ($cartItems as $item):
                $itemTotal = $item->productPrice * $item->quantity;
                $totalAmount += $itemTotal;
            ?>
                <tr>
                    <td>
                        <input type="checkbox" name="selected[]" value="<?= $item->id ?>" />
                    </td>

                    <td class="product-cell">
                        <img src="/photos/<?= $item->productPhoto ?>" alt="">
                        <span><?= htmlspecialchars($item->productName) ?></span>
                    </td>

                    <td class="price">
                        RM <?= number_format($item->productPrice, 2) ?>
                    </td>

                    <td style="text-align:center; height:60px; vertical-align: middle;">
                        <div style="display:inline-flex; align-items:center; gap:5px;">
                            <form method="post" action="/product/cart.php" style="margin:0;">
                            <input type="hidden" name="cartID" value="<?= $item->id ?>">
                            <input type="hidden" name="quantity" value="<?= max($item->quantity - 1, 1) ?>">
                            <button type="submit" name="decrease" class="btn-dec">-</button>
                            </form>

                            <input class="qty-input"
                            type="number"
                            value="<?= $item->quantity ?>"
                            readonly
                            style="width:40px; text-align:center; border:none; background:none;">

                            <form method="post" action="/product/cart.php" style="margin:0;">
                            <input type="hidden" name="cartID" value="<?= $item->id ?>">
                            <input type="hidden" name="quantity" value="<?= $item->quantity + 1 ?>">
                            <button type="submit" name="increase" class="btn-inc">+</button>
                            </form>
                        </div>
                    </td>


                    <td class="total">
                        RM <?= number_format($itemTotal, 2) ?>
                    </td>

                    <td>
                        <button class="btn-remove"
                                type="submit"
                                name="remove"
                                value="<?= $item->id ?>">
                            ✖
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="cart-summary">
        <div class="cart-total">
            Total: <span>RM <?= number_format($totalAmount, 2) ?></span>
        </div>

        <div class="cart-actions">
            <button type="submit" name="checkout" class="btn-checkout">
                Proceed to Checkout →
            </button>
        </div>
    </div>
</form>
</main>

<script>
document.getElementById('select-all').addEventListener('change', function () {
    const isChecked = this.checked;
    document.querySelectorAll('input[name="selected[]"]').forEach(cb => {
        cb.checked = isChecked;
    });
});
</script>