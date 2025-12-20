<?php
require '../_base.php';
show_popup();

// Validate user login
if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit;
}

$userID = $_SESSION['userID'];

// Validate request method as POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/product/cart.php');
    exit;
}

// Check the number of selected cart items
if (empty($_SESSION['checkout_items'])) {
    set_popup('No items selected for checkout.');
    redirect('/product/cart.php');
    exit;
}

// ================================
// 3. Prevent duplicate checkout
// ================================
if (isset($_SESSION['checkout_lock'])) {
    set_popup('Checkout already processed.');
    redirect('/product/cart.php');
    exit;
}

// Lock only AFTER validation
$_SESSION['checkout_lock'] = true;

$selectedIDs = $_SESSION['checkout_items'];
$placeholders = implode(',', array_fill(0, count($selectedIDs), '?'));

$sql = "
    SELECT c.id, c.productID, c.quantity,
           p.productPrice, p.productQty
    FROM cart c
    JOIN product p ON c.productID = p.productID
    WHERE c.userID = ?
      AND c.id IN ($placeholders)
";

$stmt = $_db->prepare($sql);
$stmt->execute(array_merge([$userID], $selectedIDs));
$cartItems = $stmt->fetchAll(PDO::FETCH_OBJ);

if (!$cartItems) {
    unset($_SESSION['checkout_lock']);
    set_popup('Selected items not found.');
    redirect('/product/cart.php');
    exit;
}

$_db->beginTransaction();
try {

    $totalAmount = 0;
    foreach ($cartItems as $item) {
        $totalAmount += $item->productPrice * $item->quantity;
    }

    $orderStmt = $_db->prepare("
        INSERT INTO orders (userID, orderDate, totalAmount)
        VALUES (?, NOW(), ?)
    ");
    $orderStmt->execute([$userID, $totalAmount]);
    $orderID = $_db->lastInsertId();

    $checkStockStmt = $_db->prepare("
        SELECT productQty
        FROM product
        WHERE productID = ?
        FOR UPDATE
    ");

    $updateStockStmt = $_db->prepare("
        UPDATE product
        SET productQty = productQty - ?
        WHERE productID = ?
    ");

    $insertItemStmt = $_db->prepare("
        INSERT INTO order_items (orderID, productID, quantity, price)
        VALUES (?, ?, ?, ?)
    ");

    foreach ($cartItems as $item) {

        $checkStockStmt->execute([$item->productID]);
        $currentStock = (int)$checkStockStmt->fetchColumn();

        if ($currentStock < $item->quantity) {
            throw new Exception(
                "Insufficient stock for product ID {$item->productID}"
            );
        }

        // Deduct stock
        $updateStockStmt->execute([
            $item->quantity,
            $item->productID
        ]);

        // Insert order item
        $insertItemStmt->execute([
            $orderID,
            $item->productID,
            $item->quantity,
            $item->productPrice
        ]);
    }

    $_db->commit();

    $cartIDs = array_column($cartItems, 'id');
    $placeholders = implode(',', array_fill(0, count($cartIDs), '?'));

    $deleteCartStmt = $_db->prepare("
        DELETE FROM cart WHERE id IN ($placeholders)
    ");
    $deleteCartStmt->execute($cartIDs);

    // Clear checkout session and lock
    unset($_SESSION['checkout_items']);
    unset($_SESSION['checkout_lock']);

    redirect("/product/payment.php?orderID=$orderID");
    exit;

} catch (Exception $e) {

    $_db->rollBack();
    unset($_SESSION['checkout_lock']);

    set_popup('Checkout failed: ' . $e->getMessage());
    redirect('/product/cart.php');
    exit;
}