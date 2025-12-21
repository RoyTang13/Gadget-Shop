<?php
require '../_base.php';
include 'admin_head.php';

    if (!isset($_SESSION['adminID'])) redirect('/admin/login.php');

    /* ===== Filters ===== */
    $search = $_GET['search'] ?? '';
    $status = $_GET['status'] ?? '';
    $from   = $_GET['from'] ?? '';
    $to     = $_GET['to'] ?? '';

    $page   = max(1, intval($_GET['page'] ?? 1));
    $limit  = 10;
    $offset = ($page - 1) * $limit;

    $where  = [];
    $params = [];

        /* Search */
        if ($search) {
            $where[] = "(o.orderID LIKE ? OR u.fname LIKE ? OR u.lname LIKE ? OR u.email LIKE ?)";
            $params = array_merge($params, array_fill(0, 4, "%$search%"));
        }

        /* Status filter */
        if ($status) {
            $where[] = "o.status = ?";
            $params[] = $status;
        }

        /* Date range */
        if ($from && $to) {
            $where[] = "DATE(o.orderDate) BETWEEN ? AND ?";
            $params[] = $from;
            $params[] = $to;
        }

        $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        /* ===== Summary ===== */
        $summarySQL = "
        SELECT COUNT(*) totalOrders,
            SUM(totalAmount) totalRevenue
        FROM orders o
        JOIN user u ON o.userID = u.userID
        $whereSQL
        ";
        $stmt = $_db->prepare($summarySQL);
        $stmt->execute($params);
        $summary = $stmt->fetch(PDO::FETCH_OBJ);

        /* ===== Count ===== */
        $countSQL = "
        SELECT COUNT(*)
        FROM orders o
        JOIN user u ON o.userID = u.userID
        $whereSQL
        ";
        $stmt = $_db->prepare($countSQL);
        $stmt->execute($params);
        $totalRows  = $stmt->fetchColumn();
        $totalPages = ceil($totalRows / $limit);

        /* ===== Orders ===== */
        $sql = "
        SELECT o.orderID, o.orderDate, o.totalAmount, o.status,
            u.fname, u.lname, u.email
        FROM orders o
        JOIN user u ON o.userID = u.userID
        $whereSQL
        ORDER BY o.orderDate DESC
        LIMIT $limit OFFSET $offset
        ";
        $stmt = $_db->prepare($sql);
        $stmt->execute($params);
        $orders = $stmt->fetchAll(PDO::FETCH_OBJ);

        /* ===== Top Selling Products ===== */
        $topSQL = "
        SELECT 
            p.productID,
            p.productName,
            p.productPhoto,
            SUM(oi.quantity) AS totalSold,
            SUM(oi.quantity * oi.price) AS totalRevenue
        FROM order_items oi
        JOIN orders o ON oi.orderID = o.orderID
        JOIN product p ON oi.productID = p.productID
        WHERE o.status = 'Complete'
        GROUP BY p.productID, p.productName, p.productPhoto
        ORDER BY totalSold DESC
        LIMIT 5
        ";

        $stmt = $_db->prepare($topSQL);
        $stmt->execute();
        $topProducts = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<section class="page-wrap">

<h2>📊 Order Report</h2>
    <!-- ===== Filters ===== -->
    <form class="filter-box" method="get">
        <input type="text" name="search" placeholder="Order / Customer / Email" value="<?= htmlspecialchars($search) ?>">

        <select name="status">
            <option value="">All Status</option>
            <option value="Pending" <?= $status=='Pending'?'selected':'' ?>>Pending</option>
            <option value="Complete" <?= $status=='Complete'?'selected':'' ?>>Complete</option>
            <option value="Cancelled" <?= $status=='Cancelled'?'selected':'' ?>>Cancelled</option>
        </select>

        <input type="date" name="from" value="<?= $from ?>">
        <input type="date" name="to" value="<?= $to ?>">

        <button>Apply</button>
    </form>

    <!-- ===== Summary ===== -->
    <div class="summary">
        <div>
            <h4>Total Orders</h4>
            <p><?= $summary->totalOrders ?? 0 ?></p>
        </div>
        <div>
            <h4>Total Revenue</h4>
            <p>RM <?= number_format($summary->totalRevenue ?? 0, 2) ?></p>
        </div>
    </div>

    <!-- ===== Top 5 Product ===== -->
    <h3 style="margin-top:30px">🔥 Top 5 Selling Products</h3>
    <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:15px;margin-top:20px">
        <?php foreach ($topProducts as $i => $p): ?>
            <div style="background:#fff;border-radius:12px;padding:10px;text-align:center">
                <img src="../photos/<?= htmlspecialchars($p->productPhoto) ?>" style="width:100%;height:120px;object-fit:cover;border-radius:8px">
                <h5>#<?= $i+1 ?> <?= $p->productName ?></h5> 
                <p><?= $p->totalSold ?> sold</p>
            </div>
        <?php endforeach ?>
    </div>



    <!-- ===== Table ===== -->
    <table class="order-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Date</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $o): ?>
                <tr>
                    <td>#<?= $o->orderID ?></td>
                    <td><?= $o->fname ?> <?= $o->lname ?></td>
                    <td><?= $o->email ?></td>
                    <td><?= date('d M Y', strtotime($o->orderDate)) ?></td>
                    <td><?= number_format($o->totalAmount,2) ?></td>
                    <td><span class="status <?= strtolower($o->status) ?>"><?= $o->status ?></span></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <!-- ===== Pagination ===== -->
    <div class="pagination">
        <?php for ($i=1;$i<=$totalPages;$i++): ?>
            <a class="<?= $i==$page?'active':'' ?>"
            href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= $status ?>&from=<?= $from ?>&to=<?= $to ?>">
            <?= $i ?>
            </a>
        <?php endfor ?>
    </div>

</section>

<style>
    .page-wrap{
        max-width:1100px;
        margin:40px auto
    }
    .filter-box{
        display:flex;
        gap:10px;
        margin-bottom:20px
    }
    .filter-box input,.filter-box select{
        padding:8px;
        border-radius:8px;
        border:1px solid #c7d2fe
    }
    .filter-box button{
        background:#6366f1;
        color:#fff;
        border:none;
        padding:8px 14px;
        border-radius:8px
    }
    .summary{
        display:flex;
        gap:20px;
        margin-bottom:20px
    }
    .summary div{
        flex:1;
        background:#eef2ff;
        padding:15px;
        border-radius:12px;
        text-align:center
    }
    .order-table{
        width:100%;
        border-collapse:collapse;
        background:#fff
    }
    .order-table th{
        background:#eef2ff;
        padding:12px
    }
    .order-table td{
        padding:10px;
        border-bottom:1px solid #e5e7eb;
        text-align:center
    }
    .status{
        padding:4px 10px;
        border-radius:12px;
        font-size:12px
    }
    .status.pending{
        background:#fde68a
    }
    .status.complete{
        background:#bbf7d0
    }
    .status.cancelled{
        background:#fecaca
    }
    .pagination{
        text-align:center;
        margin-top:15px
    }
    .pagination a{
        padding:6px 10px;
        background:#e0e7ff;
        border-radius:6px;
        margin:0 3px;
        text-decoration:none
    }
    .pagination a.active{
        background:#6366f1;
        color:white
    }
</style>
<?php include '../_foot.php'; ?>
