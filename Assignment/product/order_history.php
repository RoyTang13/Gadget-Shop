<?php
require '../_base.php';
show_popup();

// Make sure user is logged in
if (!isset($_SESSION['userID'])) {
    redirect('/page/login.php');
    exit;
}

$userID = $_SESSION['userID'];

// Fetch order history
$stmt = $_db->prepare("
    SELECT o.orderID, o.orderDate, o.status, o.totalAmount
    FROM orders o
    WHERE o.userID = ?
    ORDER BY o.orderDate DESC
");
$stmt->execute([$userID]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$_title = 'Order History | TechNest';
include '../_head.php';
?>

<main class="order-history">
    <h1>My Orders</h1>

    <?php if (!$orders): ?>
        <p>You have no orders yet.</p>
    <?php else: ?>
        <table class="order-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Total Amount (RM)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['orderID']) ?></td>
                        <td><?= date('d M Y H:i', strtotime($order['orderDate'])) ?></td>
                        <td><?= htmlspecialchars($order['status']) ?></td>
                        <td><?= number_format($order['totalAmount'], 2) ?></td>
                        <td>
                            <a href="/product/order_detail.php?orderID=<?= $order['orderID'] ?>">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<style>
.order-history {
    padding: 140px 20px 80px;
    max-width: 900px;
    margin: 0 auto;
}

.order-history h1 {
    margin-bottom: 25px;
    color: #5b21b6;
}

.order-table {
    width: 100%;
    border-collapse: collapse;
}

.order-table th,
.order-table td {
    padding: 12px 10px;
    border: 1px solid #ccc;
    text-align: left;
}

.order-table th {
    background-color: #f3e8ff;
}

.order-table tr:nth-child(even) {
    background-color: #f9f7fd;
}

.order-table a {
    color: #4c1d95;
    text-decoration: none;
}

.order-table a:hover {
    text-decoration: underline;
}
</style>

<?php include '../_foot.php'; ?>
