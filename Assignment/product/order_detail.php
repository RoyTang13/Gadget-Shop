<?php
require '../_base.php';
show_popup();

// Make sure user is logged in
if (!isset($_SESSION['userID'])) {
    redirect('/login.php');
    exit;
}

$userID = $_SESSION['userID'];

if (empty($_GET['orderID']) || !ctype_digit($_GET['orderID'])) {
    redirect('/product/order_history.php');
    exit;
}

$orderID = (int)$_GET['orderID'];

// Fetch order info
$stmt = $_db->prepare("
    SELECT o.orderID, o.orderDate, o.status, o.totalAmount,
           pi.bankName, pi.cardHolder, pi.cardNumber, pi.expiryDate,
           pi.billingName, pi.address, pi.city, pi.postcode, pi.state
    FROM orders o
    LEFT JOIN payment_info pi ON o.orderID = pi.orderID
    WHERE o.userID = ? AND o.orderID = ?
");
$stmt->execute([$userID, $orderID]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    set_popup('Order not found.');
    redirect('/product/order_history.php');
    exit;
}

// Fetch order items
$stmtItems = $_db->prepare("
    SELECT oi.productID, p.productName, oi.quantity, oi.price
    FROM order_items oi
    JOIN product p ON oi.productID = p.productID
    WHERE oi.orderID = ?
");
$stmtItems->execute([$orderID]);
$items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

$_title = "Order #{$orderID} | TechNest";
include '../_head.php';
?>

<main class="order-detail">
    <h1>Order #<?= $order['orderID'] ?></h1>
    <p><strong>Date:</strong> <?= date('d M Y H:i', strtotime($order['orderDate'])) ?></p>
    <p>
    <strong>Status:</strong> 
    <span class="status <?= strtolower($order['status']) ?>">
        <?= htmlspecialchars($order['status']) ?>
    </span></p>

    <h2>Items</h2>
    <?php if ($items): ?>
        <table class="order-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price (RM)</th>
                    <th>Subtotal (RM)</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['productName']) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= number_format($item['price'], 2) ?></td>
                        <td><?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

            <tfoot>
                <tr class="total-row">
                    <td colspan="3" style="text-align:right;"><strong>Total Price: </strong></td>
                    <td><strong> <?= number_format($order['totalAmount'], 2) ?></strong></td>
                </tr>
            </tfoot>
        </table>
    <?php else: ?>
        <p>No items found for this order.</p>
    <?php endif; ?>

    <h2>Billing & Payment</h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($order['billingName'] ?? '') ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($order['address'] ?? '') ?>, <?= htmlspecialchars($order['city'] ?? '') ?>, <?= htmlspecialchars($order['postcode'] ?? '') ?>, <?= htmlspecialchars($order['state'] ?? '') ?></p>
    <p><strong>Bank:</strong> <?= htmlspecialchars($order['bankName'] ?? '') ?></p>
    <p><strong>Card:</strong> <?= htmlspecialchars($order['cardNumber'] ?? '') ?> (<?= htmlspecialchars($order['cardHolder'] ?? '') ?>)</p>
    <p><strong>Expiry:</strong> <?= htmlspecialchars($order['expiryDate'] ?? '') ?></p>

    <a href="/product/order_history.php" class="back-btn">← Back to Order History</a>
</main>

<style>
.order-detail {
    padding: 140px 20px 80px;
    max-width: 900px;
    margin: 0 auto;
}

.order-detail h1 {
    margin-bottom: 10px;
    color: #5b21b6;
}

.order-detail h2 {
    margin-top: 25px;
    margin-bottom: 10px;
    color: #4c1d95;
}

.status.complete {
    color: #10b981; /* green */
    font-weight: bold;
}

.status.pending {
    color: #f59e0b; /* orange */
    font-weight: bold;
}

.status.cancelled {
    color: #ef4444; /* red */
    font-weight: bold;
}

.order-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.order-table th,
.order-table td {
    padding: 10px;
    border: 1px solid #ccc;
    text-align: left;
}

.order-table th {
    background-color: #f3e8ff;
}

.order-table tr:nth-child(even) {
    background-color: #f9f7fd;
}

.order-table tfoot td {
    background-color: #ede9fe;
    font-size: 16px;
    border-top: 1px solid #7c3aed;
}

.total-row td {
    font-weight: bold;
}

.back-btn {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 15px;
    border-radius: 8px;
    background: #693b9f;
    color: #fff;
    text-decoration: none;
}

.back-btn:hover {
    background: #de87ee;
}
</style>

<?php include '../_foot.php'; ?>
