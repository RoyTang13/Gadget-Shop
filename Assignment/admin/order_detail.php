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
<html>
<head>
    <title>Order #<?= $orderID ?></title>
</head>
<body>

<h2>Order #<?= $orderID ?></h2>

<h3>Customer Info</h3>
<p>
    Name: <?= $order->fname ?> <?= $order->lname ?><br>
    Email: <?= $order->email ?><br>
    Phone: <?= $order->phoneNo ?>
</p>

<h3>Order Items</h3>
<table border="1" cellpadding="8">
    <tr>
        <th>Product</th>
        <th>Qty</th>
        <th>Price (RM)</th>
        <th>Subtotal</th>
    </tr>
    <?php foreach ($items as $i): ?>
    <tr>
        <td><?= $i->productName ?></td>
        <td><?= $i->quantity ?></td>
        <td><?= number_format($i->price,2) ?></td>
        <td><?= number_format($i->price * $i->quantity,2) ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<p><strong>Total: RM <?= number_format($order->totalAmount,2) ?></strong></p>
<h3>Order Status</h3>

<form method="post" action="order_status_update.php">
    <input type="hidden" name="orderID" value="<?= $order->orderID ?>">

    <select name="status" required>
        <option value="Pending"  <?= $order->status=='Pending' ? 'selected' : '' ?>>Pending</option>
        <option value="Complete" <?= $order->status=='Complete' ? 'selected' : '' ?>>Complete</option>
        <option value="Cancelled" <?= $order->status=='Cancelled' ? 'selected' : '' ?>>Cancelled</option>
    </select>

    <button type="submit">Update Status</button>
</form>

<h3>Payment Info</h3>
<?php if ($payment): ?>
<p>
    Bank: <?= $payment->bankName ?><br>
    Card Holder: <?= $payment->cardHolder ?><br>
    Card Number: <?= $payment->cardNumber ?><br>
    Billing Name: <?= $payment->billingName ?><br>
    Address: <?= $payment->address ?>, <?= $payment->postcode ?> <?= $payment->city ?>, <?= $payment->state ?>
</p>
<?php else: ?>
<p>No payment record.</p>
<?php endif; ?>

<p><a href="order_list.php">← Back to order list</a></p>

</body>
</html>
