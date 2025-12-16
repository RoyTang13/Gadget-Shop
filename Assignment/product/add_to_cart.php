<?php
require '../_base.php';
show_popup();

if (!isset($_SESSION['userID'])) {
    redirect('../page/login.php');
}

$userID    = $_SESSION['userID'];
$productID = $_POST['productID'];
$qty       = max(1, (int)($_POST['quantity'] ?? 1));

// check if product already in cart
$check = $_db->prepare(
    "SELECT id, quantity FROM cart WHERE userID=? AND productID=?"
);
$check->execute([$userID, $productID]);

if ($row = $check->fetch()) {
    // ADD selected quantity
    $upd = $_db->prepare(
        "UPDATE cart SET quantity = quantity + ? WHERE id=?"
    );
    $upd->execute([$qty, $row->id]);
} else {
    // insert with selected quantity
    $ins = $_db->prepare(
        "INSERT INTO cart (userID, productID, quantity)
         VALUES (?, ?, ?)"
    );
    $ins->execute([$userID, $productID, $qty]);
}

set_popup('Product added to cart');
redirect('/product/cart.php');
