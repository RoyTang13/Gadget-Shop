<?php
require '../_base.php';
include 'admin_head.php';

if (!isset($_SESSION['adminID'])) redirect('/admin/login.php');

/* Search + Pagination */

$search = $_GET['search'] ?? '';
$page   = max(1, intval($_GET['page'] ?? 1));
$limit  = 10;
$offset = ($page - 1) * $limit;

$where = '';
$params = [];

if ($search) {
    $where = "WHERE o.orderID LIKE ? 
              OR u.fname LIKE ? 
              OR u.lname LIKE ? 
              OR u.email LIKE ?";
    $params = array_fill(0, 4, "%$search%");
}

/* Total rows */
$countSQL = "
SELECT COUNT(*) FROM orders o
JOIN user u ON o.userID = u.userID
$where
";
$stmt = $_db->prepare($countSQL);
$stmt->execute($params);
$totalRows = $stmt->fetchColumn();
$totalPages = ceil($totalRows / $limit);

/* Orders */
$sql = "
SELECT o.orderID, o.orderDate, o.totalAmount, o.status,
       u.fname, u.lname, u.email
FROM orders o
JOIN user u ON o.userID = u.userID
$where
ORDER BY o.orderDate DESC
LIMIT $limit OFFSET $offset
";
$stmt = $_db->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin - Orders</title>

</head>

<body>
    <section>
<div class="page-wrap">
<div class="order-card">
<h2>Customer Orders</h2>

<form class="search-box" method="get">
    <input type="text" name="search" placeholder="Search order / customer / email" value="<?= htmlspecialchars($search) ?>">
    <button>Search</button>
</form>

<table class="order-table">
<thead>
<tr>
    <th>ID</th>
    <th>Customer</th>
    <th>Email</th>
    <th>Date</th>
    <th>Total</th>
    <th>Status</th>
    <th>Action</th>
</tr>
</thead>

<tbody>
<?php foreach ($orders as $o): ?>
<tr>
    <td data-label="Order ID">#<?= $o->orderID ?></td>
    <td data-label="Customer"><?= $o->fname ?> <?= $o->lname ?></td>
    <td data-label="Email"><?= $o->email ?></td>
    <td data-label="Date"><?= $o->orderDate ?></td>
    <td data-label="Total"><?= number_format($o->totalAmount,2) ?></td>
    <td data-label="Status">
        <span class="status <?= strtolower($o->status) ?>">
            <?= $o->status ?>
        </span>
    </td>
    <td data-label="Action">
        <a class="view-btn" href="order_detail.php?id=<?= $o->orderID ?>">View</a>
    </td>
</tr>
<?php endforeach; ?>
    </tbody>
        </table>

            <div class="pagination">
            <?php for ($i=1; $i<=$totalPages; $i++): ?>
                <a class="<?= $i==$page ? 'active':'' ?>" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
            </div>
</div>
</div>
</section>
</body>
</html>

<style>
body {
    font-family: Arial, sans-serif;
    min-height: 100vh;
    margin: 0;
}

/* ===== Layout ===== */
.page-wrap {
    max-width: 1100px;
    margin: 40px auto;
    padding: 20px;
}

.order-card {
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(8px);
    border-radius: 14px;
    padding: 25px;
    box-shadow: 0 10px 25px rgba(0,0,0,.12);
}

h2 {
    margin-bottom: 15px;
    color: #4338ca;
}

/* ===== Search ===== */
.search-box {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.search-box input {
    flex: 1;
    padding: 9px 12px;
    border-radius: 8px;
    border: 1px solid #c7d2fe;
}

.search-box button {
    padding: 9px 16px;
    border: none;
    border-radius: 8px;
    background: #6366f1;
    color: #fff;
    cursor: pointer;
}

.search-box button:hover {
    background: #4f46e5;
}

/* ===== Table ===== */
.order-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
}

.order-table th {
    background: #eef2ff;
    padding: 12px;
    font-size: 14px;
}

.order-table td {
    padding: 11px;
    font-size: 14px;
    border-bottom: 1px solid #e5e7eb;
    text-align: center;
}

.order-table tr:hover {
    background: #f9fafb;
}

/* ===== Status ===== */
.status {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: bold;
}

.status.pending {
    background: #fde68a;
    color: #92400e;
}

.status.complete {
    background: #bbf7d0;
    color: #065f46;
}

.status.cancelled {
    background: #fecaca;
    color: #7f1d1d;
}

/* ===== Button ===== */
.view-btn {
    padding: 6px 12px;
    background: #818cf8;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
}

.view-btn:hover {
    background: #6366f1;
}

/* ===== Pagination ===== */
.pagination {
    margin-top: 15px;
    text-align: center;
}

.pagination a {
    display: inline-block;
    padding: 6px 10px;
    margin: 0 3px;
    background: #e0e7ff;
    color: #3730a3;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
}

.pagination a.active {
    background: #6366f1;
    color: white;
}

/* ===== Mobile ===== */
@media (max-width: 768px) {
    .order-table thead {
        display: none;
    }

    .order-table tr {
        display: block;
        margin-bottom: 12px;
        border-radius: 12px;
        box-shadow: 0 6px 14px rgba(0,0,0,.1);
    }

    .order-table td {
        display: flex;
        justify-content: space-between;
        padding: 10px 12px;
        text-align: left;
    }

    .order-table td::before {
        content: attr(data-label);
        font-weight: bold;
        color: #4b5563;
    }
    
}


</style>