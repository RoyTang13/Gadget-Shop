<?php
require '../_base.php';


$_title = 'Admin Dashboard';
include 'admin_head.php';

// make sure only logged-in admins can access this page
if (!isset($_SESSION['adminID'])) {
    header('Location: index.php');
    exit;
}
/* ===== Order Summary ===== */

// Overall summary
$stm = $_db->query("
    SELECT 
        COUNT(*) AS totalOrders,
        COALESCE(SUM(totalAmount), 0) AS totalRevenue
    FROM orders
");
$orderSummary = $stm->fetch(PDO::FETCH_OBJ);

?>

<body>

<section class="dashboard">
    <div class="dashboard-wrapper">
        <div class="dashboard-container">
        <div class="card">
            <a href="../admin/user_list.php">
                <h3>Total Users</h3>
                <p>
                <?php
                    global $_db;
                    $stm = $_db->query("SELECT COUNT(*) FROM user");
                    $count = $stm->fetchColumn();
                    echo $count ?: 0;
                ?></p>
            </a>
            </div>
            <div class="card">
                <a href="../product/list.php">
                <h3>Total Products</h3>
                <p>
                <?php
                    global $_db;
                    $stm = $_db->query("SELECT COUNT(*) FROM product");
                    $count = $stm->fetchColumn();
                    echo $count ?: 0;
                ?>
                </p>
                </a>
            </div>
            <div class="card">
                <a href="../admin/order_list.php">
                <h3>Total order</h3>
                <p>
                    <?php
                    global $_db;
                    $stm = $_db->query("SELECT COUNT(*) FROM orders");
                    $count = $stm->fetchColumn();
                    echo $count ?: 0;
                ?>
                </p>
            </div>
            <!-- ===== Order Summary ===== -->
            <div class="card">
                <a href="../admin/report.php">
                    <h3>Total Revenue</h3>
                    <p>RM <?= number_format($orderSummary->totalRevenue, 2) ?></p>
                </a>
            </div>
        </div>
    </div>
</section>


</body>
</html>

<?php include '../_foot.php'; ?>
