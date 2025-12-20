<?php
require '../_base.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../lib/Exception.php';
require '../lib/PHPMailer.php';
require '../lib/SMTP.php';

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

//print receipt
if (isset($_POST['send_receipt'])) {

    $order_id = (int)($_POST['order_id'] ?? 0);
    if (!$order_id) {
        temp('error', 'Invalid order ID');
        redirect();
    }

    // 1️⃣ Get order + user info (EMAIL + NAME)
    $stmOrder = $_db->prepare("
        SELECT 
            o.orderID,
            o.orderDate,
            o.status,
            o.totalAmount,
            u.email,
            CONCAT(u.fname, ' ', u.lname) AS customerName
        FROM orders o
        JOIN user u ON o.userID = u.userID
        WHERE o.orderID = ?
    ");
    $stmOrder->execute([$order_id]);
    $orderMail = $stmOrder->fetch(PDO::FETCH_ASSOC);

    if (!$orderMail) {
        temp('error', 'Order not found');
        redirect();
    }

    // 2️⃣ Get order items (CORRECT TABLE NAME)
    $stmItems = $_db->prepare("
        SELECT 
            p.productName,
            oi.quantity,
            oi.price,
            (oi.quantity * oi.price) AS subtotal
        FROM order_items oi
        JOIN product p ON oi.productID = p.productID
        WHERE oi.orderID = ?
    ");
    $stmItems->execute([$order_id]);
    $orderItems = $stmItems->fetchAll(PDO::FETCH_ASSOC);

    // 3️⃣ Build item rows
    $itemRows = '';
    foreach ($orderItems as $item) {
        $itemRows .= "
            <tr>
                <td>{$item['productName']}</td>
                <td align='center'>{$item['quantity']}</td>
                <td align='right'>RM {$item['price']}</td>
                <td align='right'>RM {$item['subtotal']}</td>
            </tr>
        ";
    }

    // 4️⃣ Email content
    $subject = "Your Receipt - Order #{$orderMail['orderID']}";

    $body = "
    <h2>TechNest Receipt</h2>

    <p>Hello <strong>{$orderMail['customerName']}</strong>,</p>

    <table border='1' cellpadding='8' cellspacing='0' width='100%'>
        <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Price (RM)</th>
            <th>Subtotal (RM)</th>
        </tr>
        $itemRows
        <tr>
            <td colspan='3' align='right'><strong>Total</strong></td>
            <td align='right'><strong>RM {$orderMail['totalAmount']}</strong></td>
        </tr>
    </table>

    <p>Regards,<br><strong>TechNest</strong></p>
    ";

    // 5️⃣ Send email
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];
        $mail->Host       = "smtp.gmail.com";
        $mail->SMTPAuth   = true;
        $mail->Username   = "technest0123@gmail.com";
        $mail->Password   = "gmxj vniw ypjk dish";
        $mail->SMTPSecure = "tls";
        $mail->Port       = 587;

        $mail->setFrom("technest0123@gmail.com", "TechNest");
        $mail->addAddress($orderMail['email'], $orderMail['customerName']);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        temp('info', 'Receipt sent to customer email successfully.');

    } catch (Exception $e) {
        temp('error', 'Email error: ' . $mail->ErrorInfo);
    }

    redirect();
}


?>

<main class="order-detail" id="receipt">
    <h1>Order #<?= $order['orderID'] ?></h1>
    <p><strong>Date:</strong> <?= date('d M Y H:i', strtotime($order['orderDate'])) ?></p>
    <p><strong>Status:</strong> <span class="status <?= strtolower($order['status']) ?>"><?= htmlspecialchars($order['status']) ?></span></p>

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

    <!-- Print Button -->
    <div class="action-container">
    <button onclick="window.print()" class="print-btn"> Print Receipt</button>

    <form method="post" style="display: inline;">
        <input type="hidden" name="order_id" value="<?= $order['orderID'] ?>">
        <button type="submit" name="send_receipt" class="email-btn"> Send Email Receipt</button>
    </form>
</div>

<div style="text-align: center;">
    <a href="/product/order_history.php" class="back-btn">← Back to Order History</a>
</div>


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
.action-container {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin: 30px 0;
}
.print-btn, .email-btn {
    padding: 12px 24px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    font-size: 15px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
.print-btn {
    background: #5b21b6;
    color: #fff;
}

.print-btn:hover {
    background: #7c3aed;
    transform: translateY(-2px);
}
.email-btn {
    background: #4b6cff;
    color: #fff;
}
.email-btn:hover {
    background: #3753d6;
    transform: translateY(-2px);
}

.back-btn {
    display: inline-block;
    padding: 10px 20px;
    color: #693b9f;
    background: transparent;
    border: 2px solid #693b9f;
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s;
}

.back-btn:hover {
    background: #693b9f;
    color: #fff;
}

/* Print Styles */
@media print {
    body * {
        visibility: hidden; /* hide everything */
    }

    #receipt, #receipt * {
        visibility: visible; /* show receipt */
    }

    #receipt {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 20px;
    }

    .print-btn,
    .back-btn {
        display: none; /* hide buttons in print */
    }
}

/* Print Button Styling */
.print-btn {
    background: #5b21b6;
    color: #fff;
    padding: 10px 20px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
}

.print-btn:hover {
    background: #7c3aed;
}

</style>

<?php include '../_foot.php'; ?>
