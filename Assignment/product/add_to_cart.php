<?php
require '../_base.php';
show_popup();

$qty = max(1, intval($_POST['quantity']));

if (!isset($_SESSION['userID'])) {
    redirect('/page/login.php');
}

$userID = $_SESSION['userID'];
$productID = $_POST['productID'];
$qty = 1;

// check if product already in cart
$check = $_db->prepare(
    "SELECT id, quantity FROM cart WHERE userID=? AND productID=?"
);
$check->execute([$userID, $productID]);

if ($row = $check->fetch()) {
    // update quantity
    $upd = $_db->prepare(
        "UPDATE cart SET quantity = quantity + 1 WHERE id=?"
    );
    $upd->execute([$row->id]);
} else {
    // insert new
    $ins = $_db->prepare(
        "INSERT INTO cart (userID, productID, quantity)
         VALUES (?, ?, 1)"
    );
    $ins->execute([$userID, $productID]);
}

set_popup('Product added to cart');
redirect('/product/cart.php');
