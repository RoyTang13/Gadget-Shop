        <?php
        require '../_base.php';
        show_popup();

        // Must be logged in
        if (!isset($_SESSION['userID'])) {
            redirect('/login.php');
            exit;
        }

        $userID = $_SESSION['userID'];

        // Validate orderID
        if (empty($_GET['orderID']) || !ctype_digit($_GET['orderID'])) {
            redirect('/product/cart.php');
            exit;
        }

        $orderID = intval($_GET['orderID']);

        // Fetch order (security: userID check)
        $stmt = $_db->prepare(
            "SELECT * FROM orders WHERE orderID = ? AND userID = ?"
        );
        $stmt->execute([$orderID, $userID]);
        $order = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$order) {
            redirect('/product/cart.php');
            exit;
        }

        // Fetch order items
        $stmt = $_db->prepare(
            "SELECT oi.productID, oi.quantity, oi.price, p.productName, p.productPhoto
             FROM order_items oi
             JOIN product p ON oi.productID = p.productID
             WHERE oi.orderID = ?"
        );
        $stmt->execute([$orderID]);
        $orderLineItems = $stmt->fetchAll(PDO::FETCH_OBJ);  

        // --- MARK ORDER AS COMPLETE ---
        $stmt = $_db->prepare("UPDATE orders SET status = 'Complete' WHERE orderID = ? AND userID = ?");
        $stmt->execute([$orderID, $userID]);


        unset($_SESSION['checkout_items']);


        $_title = "Order Successful | TechNest";
        include '../_head.php';

        ?>

        <main class="order-success">

            <div class="success-card">
                <h1>🎉 Order Confirmed!</h1>
                <p class="order-id">Order ID: <strong>#<?= $orderID ?></strong></p>
                <p class="order-date">Date: <?= date('d M Y, h:i A', strtotime($order->orderDate)) ?></p>

                <div class="items">
                    <?php foreach ($orderLineItems as $item): ?>
                        <div class="item">
                            <img src="/photos/<?= htmlspecialchars($item->productPhoto) ?>" alt="">
                            <div>
                                <h4><?= htmlspecialchars($item->productName) ?></h4>
                                <p>Qty: <?= $item->quantity ?></p>
                                <p>RM <?= number_format($item->price, 2) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="total">
                    Total Paid:
                    <span>RM <?= number_format($order->totalAmount, 2) ?></span>
                </div>

                <div class="actions">
                    <a href="/product/product.php" class="btn">Continue Shopping</a>
                    <a href="/product/order_history.php" class="btn outline">View My Orders</a>
                </div>
            </div>

        </main>

        <style>
        .order-success {
            display: flex;
            justify-content: center;
            padding: 60px 20px;
        }

        .success-card {
            max-width: 700px;
            width: 100%;
            background: #fff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0,0,0,.15);
            text-align: center;
        }

        .success-card h1 {
            margin-bottom: 10px;
            color: #5b21b6;
        }

        .order-id, .order-date {
            color: #555;
            margin-bottom: 10px;
        }

        .items {
            margin: 30px 0; 
            border-top: 1px solid #eee;
        }

        .item {
            display: flex;
            gap: 15px;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .item img {
            width: 80px;
            border-radius: 10px;
        }

        .item h4 {
            margin: 0;
            font-size: 16px;
        }

        .total {
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
        }

        .total span {
            color: #7c3aed;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            margin-top: 20px;
            background: #7c3aed;
            color: #fff;
            padding: 12px 22px;
            border-radius: 10px;
            text-decoration: none;
        }

        .btn.outline {
            background: transparent;
            color: #7c3aed;
            border: 2px solid #7c3aed;  
        }
        </style>
