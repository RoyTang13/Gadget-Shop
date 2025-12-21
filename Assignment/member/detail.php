<?php
require '../_base.php';
$_title = 'Member Detail';
include '../admin/admin_head.php';

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$id) die('Invalid ID');

    $stm = $_db->prepare('SELECT * FROM member WHERE memberID = ?');
    $stm->execute([$id]);
    $m = $stm->fetch(PDO::FETCH_OBJ);

    if (!$m) die('Member not found');
?>

    <h2>Member Detail</h2>

    <?php if ($m->photo): ?>
    <img src="/uploads/<?= htmlspecialchars($m->photo) ?>" width="120"><br>
    <?php endif ?>

    <p><b>Name:</b> <?= htmlspecialchars($m->name) ?></p>
    <p><b>Email:</b> <?= htmlspecialchars($m->email) ?></p>
    <p><b>Phone:</b> <?= htmlspecialchars($m->phone) ?></p>
    <p><b>Registered:</b> <?= $m->createdAt ?></p>

    <a href="../admin/member_list.php">Back</a>

<?php include '../_foot.php'; ?>