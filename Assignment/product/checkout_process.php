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

$selectedIDs = $_SESSION['checkout_items'];

// Fetch selected cart items using cart IDs
$placeholders = implode(',', array_fill(0, count($selectedIDs), '?'));
$sql = "SELECT c.id, c.productID, c.quantity, p.productPrice
        FROM cart c 
        JOIN product p ON c.productID = p.productID
        WHERE c.userID = ? AND c.id IN ($placeholders)";
$stmt = $_db->prepare($sql);
$stmt->execute(array_merge([$userID], $selectedIDs));
$cartItems = $stmt->fetchAll(PDO::FETCH_OBJ);

if (!$cartItems) {
    set_popup('Selected items not found.');
    redirect('/product/cart.php');
    exit;
}

// Start transaction
$_db->beginTransaction();

try {
    // Insert new order
    $stmt = $_db->prepare("INSERT INTO orders (userID, orderDate, totalAmount) VALUES (?, NOW(), ?)");
    $totalAmount = 0;
    foreach ($cartItems as $item) {
        $totalAmount += $item->productPrice * $item->quantity;
    }
    $stmt->execute([$userID, $totalAmount]);
    $orderID = $_db->lastInsertId();

    // Insert order items
    $stmt = $_db->prepare("INSERT INTO order_items (orderID, productID, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($cartItems as $item) {
        $stmt->execute([$orderID, $item->productID, $item->quantity, $item->productPrice]);
    }

    // Commit transaction
    $_db->commit();

    // Clear checkout session
    redirect("/product/payment.php?orderID=$orderID");
    exit;

} catch (Exception $e) {
    $_db->rollBack();
    set_popup('Checkout failed: ' . $e->getMessage());
    redirect('/product/cart.php');
    exit;
}
