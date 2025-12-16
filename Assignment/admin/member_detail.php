<?php
require '../_base.php';
$_title = 'Member Detail';
include 'admin_head.php';

// ===============================
// Validate ID
// ===============================
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    set_popup('Invalid member ID');
    redirect('member_list.php');
}

// ===============================
// Fetch member
// ===============================
$stm = $_db->prepare('SELECT * FROM member WHERE memberID = ?');
$stm->execute([$id]);
$m = $stm->fetch(PDO::FETCH_OBJ);

if (!$m) {
    set_popup('Member not found');
    redirect('member_list.php');
}
?>

<section class="admin-container">
    <h1 class="page-title">Member Detail</h1>

    <div class="member-card">
        <!-- Profile Photo -->
        <div class="member-photo">
            <?php if ($m->memberPhoto): ?>
                <img src="/uploads/<?= htmlspecialchars($m->memberPhoto) ?>" alt="Member Photo">
            <?php else: ?>
                <div class="photo-placeholder">No Photo</div>
            <?php endif ?>
        </div>

        <!-- member Info -->
        <div class="member-info">
            <p><span>Member ID</span><?= htmlspecialchars($m->memberID) ?></p>
            <p><span>Name</span><?= htmlspecialchars($m->name) ?></p>
            <p><span>Email</span><?= htmlspecialchars($m->email) ?></p>
            <p><span>Phone</span><?= htmlspecialchars($m->phone) ?></p>
            <p><span>Registered:</span> <?= $m->createdAt ?></p>
        </div>
    </div>

    <!-- Actions -->
    <div class="actions">
        <a href="member_list.php" class="btn btn-secondary">← Back to member List</a>
    </div>
</section>

<style>
/* ===== Layout ===== */
.admin-container {
    max-width: 900px;
    margin: 40px auto;
    padding: 20px;
}

.page-title {
    text-align: center;
    margin-bottom: 30px;
}

/* ===== Card ===== */
.member-card {
    display: flex;
    gap: 30px;
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 6px 20px rgba(0,0,0,.08);
}

/* ===== Photo ===== */
.member-photo img {
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
.member-info {
    flex: 1;
    font-size: 18px;
}

.member-info p {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.member-info span {
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
</style>

<?php include '../_foot.php'; ?>
