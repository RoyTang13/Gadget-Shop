<?php
require '../_base.php';
include '../admin/admin_head.php';

if (empty($_GET['id'])) {
    echo "Order not found";
    exit;
}

$orderID = $_GET['id'];

// Order + user
$sql = "
SELECT o.*, u.fname, u.lname, u.email, u.phoneNo
FROM orders o
JOIN user u ON o.userID = u.userID
WHERE o.orderID = ?
";
$stmt = $_db->prepare($sql);
$stmt->execute([$orderID]);
$order = $stmt->fetch(PDO::FETCH_OBJ);

if (!$order) {
    echo "Order not found";
    exit;
}

// Order items
$sql = "
SELECT oi.*, p.productName
FROM order_items oi
JOIN product p ON oi.productID = p.productID
WHERE oi.orderID = ?
";
$stmt = $_db->prepare($sql);
$stmt->execute([$orderID]);
$items = $stmt->fetchAll(PDO::FETCH_OBJ);

// Payment info
$sql = "SELECT * FROM payment_info WHERE orderID = ?";
$stmt = $_db->prepare($sql);
$stmt->execute([$orderID]);
$payment = $stmt->fetch(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order #<?= $orderID ?></title>

<style>
body{
    background:#eef2ff;
    font-family:system-ui,-apple-system,Segoe UI,sans-serif;
}

.container{
    max-width:1100px;
    margin:30px auto;
    background:#fff;
    border-radius:14px;
    box-shadow:0 8px 25px rgba(0,0,0,.08);
    padding:25px;
}

h2{
    margin-bottom:10px;
}

.section{
    margin-top:25px;
}

.section h3{
    margin-bottom:12px;
    border-left:5px solid #6c63ff;
    padding-left:10px;
}

.info-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:15px;
}

.info-box{
    background:#f5f7ff;
    padding:15px;
    border-radius:10px;
}

table{
    width:100%;
    border-collapse:collapse;
}

table thead{
    background:#6c63ff;
    color:#fff;
}

table th, table td{
    padding:10px;
    border-bottom:1px solid #ddd;
    text-align:center;
}

table tbody tr:hover{
    background:#f1f3ff;
}

.total{
    text-align:right;
    font-size:18px;
    font-weight:600;
    margin-top:10px;
}

.status-form{
    display:flex;
    gap:10px;
    align-items:center;
}

select{
    padding:6px 10px;
    border-radius:6px;
    border:1px solid #ccc;
}

button{
    background:#6c63ff;
    border:none;
    color:#fff;
    padding:6px 14px;
    border-radius:6px;
    cursor:pointer;
}

button:hover{
    background:#554ee0;
}

.back-link{
    display:inline-block;
    margin-top:20px;
    text-decoration:none;
    color:#6c63ff;
    font-weight:500;
}
</style>
</head>

<body>

<div class="container">

<h2>Order #<?= $orderID ?></h2>

<!-- Customer Info -->
<div class="section">
    <h3>Customer Information</h3>
    <div class="info-grid">
        <div class="info-box">
            <strong>Name</strong><br>
            <?= htmlspecialchars($order->fname) ?> <?= htmlspecialchars($order->lname) ?>
        </div>
        <div class="info-box">
            <strong>Email</strong><br>
            <?= htmlspecialchars($order->email) ?>
        </div>
        <div class="info-box">
            <strong>Phone</strong><br>
            <?= htmlspecialchars($order->phoneNo) ?>
        </div>
    </div>
</div>

<!-- Order Items -->
<div class="section">
    <h3>Order Items</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price (RM)</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $i): ?>
            <tr>
                <td><?= htmlspecialchars($i->productName) ?></td>
                <td><?= $i->quantity ?></td>
                <td><?= number_format($i->price,2) ?></td>
                <td><?= number_format($i->price * $i->quantity,2) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total">
        Total: RM <?= number_format($order->totalAmount,2) ?>
    </div>
</div>

<!-- Order Status -->
<div class="section">
    <h3>Order Status</h3>
    <form method="post" action="order_status_update.php" class="status-form">
        <input type="hidden" name="orderID" value="<?= $order->orderID ?>">

        <select name="status" required>
            <option value="Pending" <?= $order->status=='Pending'?'selected':'' ?>>Pending</option>
            <option value="Complete" <?= $order->status=='Complete'?'selected':'' ?>>Complete</option>
            <option value="Cancelled" <?= $order->status=='Cancelled'?'selected':'' ?>>Cancelled</option>
        </select>

        <button type="submit">Update</button>
    </form>
</div>

<!-- Payment Info -->
<div class="section">
    <h3>Payment Information</h3>
    <?php if ($payment): ?>
        <div class="info-grid">
            <div class="info-box"><strong>Bank</strong><br><?= htmlspecialchars($payment->bankName) ?></div>
            <div class="info-box"><strong>Card Holder</strong><br><?= htmlspecialchars($payment->cardHolder) ?></div>
            <div class="info-box"><strong>Card Number</strong><br><?= htmlspecialchars($payment->cardNumber) ?></div>
            <div class="info-box">
                <strong>Billing Address</strong><br>
                <?= htmlspecialchars($payment->billingName) ?><br>
                <?= htmlspecialchars($payment->address) ?><br>
                <?= htmlspecialchars($payment->postcode) ?> <?= htmlspecialchars($payment->city) ?>, <?= htmlspecialchars($payment->state) ?>
            </div>
        </div>
    <?php else: ?>
        <p>No payment record.</p>
    <?php endif; ?>
</div>

<a href="order_list.php" class="back-link">← Back to order list</a>

</div>
</body>
</html>
