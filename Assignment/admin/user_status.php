<?php
require '../_base.php';

if (!isset($_SESSION['adminID'])) {
    redirect('index.php');
}

$userID = $_POST['userID'] ?? null;
if (!$userID) redirect('user_list.php');

// Toggle status
$sql = "
UPDATE user
SET status = IF(status='banned','active','banned')
WHERE userID = ?
";
$_db->prepare($sql)->execute([$userID]);

set_popup('User status updated');
redirect('user_list.php');
