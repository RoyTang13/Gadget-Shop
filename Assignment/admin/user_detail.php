<?php
require '../_base.php';
$_title = 'User Detail';
include 'admin_head.php';

// ===============================
// Validate ID
// ===============================
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    set_popup('Invalid user ID');
    redirect('user_list.php');
    exit;
}

// ===============================
// Fetch user
// ===============================
$stm = $_db->prepare('SELECT * FROM user WHERE userID = ?');
$stm->execute([$id]);
$m = $stm->fetch(PDO::FETCH_OBJ);

if (!$m) {
    set_popup('User not found');
    redirect('user_list.php');
    exit;
}
if ($m->status === 'banned') {
    set_popup('⚠ This user is currently banned');
}

// ===============================
// Fetch Order History
// ===============================
$stm = $_db->prepare("
    SELECT orderID, totalAmount, status, orderDate
    FROM orders
    WHERE userID = ?
    ORDER BY orderDate DESC
");
$stm->execute([$id]);
$orders = $stm->fetchAll(PDO::FETCH_OBJ);

?>

<section class="admin-container">
    <h1 class="page-title">User Detail</h1>

    <div class="user-card">
        <!-- Profile Photo -->
        <div class="user-photo">
            <?php if ($m->userPhoto): ?>
                <img src="/uploads/<?= htmlspecialchars($m->userPhoto) ?>" alt="User Photo">
            <?php else: ?>
                <div class="photo-placeholder">No Photo</div>
            <?php endif ?>
        </div>

        <!-- User Info -->
        <div class="user-info">
            <p><span>User ID</span><?= htmlspecialchars($m->userID) ?></p>
            <p><span>First Name</span><?= htmlspecialchars($m->fname) ?></p>
            <p><span>Last Name</span><?= htmlspecialchars($m->lname) ?></p>
            <p><span>Email</span><?= htmlspecialchars($m->email) ?></p>
            <p><span>Phone</span><?= htmlspecialchars($m->phoneNo) ?></p>
            <p><span>Last Login</span>
            <?= $m->lastLogin 
            ? date('d M Y, h:i A', strtotime($m->lastLogin)) 
            : 'Never logged in' ?>
            </p>

            <p>
                <span>Status</span>
                <strong class="<?= $m->status === 'banned' ? 'status-banned' : 'status-active' ?>">
                    <?= htmlspecialchars($m->status) ?>
                </strong>
            </p>
        </div>
    </div>

    <!-- Actions -->
    <div class="actions">
        <a href="user_list.php" class="btn btn-secondary">← Back to User List</a>
    </div>
    <div class="user-card">
        <hr style="margin: 40px 0">
        <h2 style="text-align:center;">Order History</h2>
        <?php if ($orders): ?>
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Total (RM)</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $o): ?>
                        <tr>
                            <td data-label="Order ID">#<?= $o->orderID ?></td>
                            <td><?= date('d M Y', strtotime($o->orderDate)) ?></td>
                            <td><?= number_format($o->totalAmount, 2) ?></td>
                            <td>
                                <span class="badge <?= strtolower($o->status) ?>">
                                    <?= htmlspecialchars($o->status) ?>
                                </span>
                            </td>
                            <td>
                                <a href="/admin/order_detail.php?id=<?= $o->orderID ?>" class="btn-small">
                                    View
                                </a>
                            </td>
                        <tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        
        <?php else: ?>
            <p style="text-align:center; color:#888;">No orders found for this user.</p>
        <?php endif; ?>
    </div>
</section>

<style>
/* ===== Layout ===== */
.admin-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 20px;
}

.page-title {
    text-align: center;
    margin-bottom: 30px;
}

/* ===== Card ===== */
.user-card {
    display: flex;
    gap: 30px;
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 6px 20px rgba(0,0,0,.08);
}

/* ===== Photo ===== */
.user-photo img {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #be06ec;
}

.photo-placeholder {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #888;
    font-size: 14px;
}

/* ===== Info ===== */
.user-info {
    flex: 1;
    font-size: 18px;
}

.user-info p {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.user-info span {
    font-weight: 600;
    color: #555;
}

/* ===== Status ===== */
.status-active {
    color: green;
}

.status-banned {
    color: red;
}

/* ===== Actions ===== */
.actions {
    margin-top: 25px;
    text-align: center;
}

.btn {
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 16px;
}

.btn-secondary {
    background: #be06ec;
    color: #fff;
}

.btn-secondary:hover {
    background: #d17de6;
}
/* ===== Order Table ===== */
.order-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    font-size: 16px;
}

.order-table th, .order-table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    text-align: center;
}

.order-table th {
    background: #f9f5ff;
}

/* ===== Status Badges ===== */
.badge {
    padding: 5px 10px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 14px;
}

.badge.pending {
    background: #fff3cd;
    color: #856404;
}

.badge.complete {
    background: #d4edda;
    color: #155724;
}

.badge.cancelled {
    background: #f8d7da;
    color: #721c24;
}

/* ===== Buttons ===== */
.btn-small {
    padding: 6px 12px;
    background: #be06ec;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
}

.btn-small:hover {
    background: #d17de6;
}

</style>

<?php include '../_foot.php'; ?>
