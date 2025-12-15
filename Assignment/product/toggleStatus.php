<?php
require '../_base.php';
// make sure only logged-in admins can access this page
if (!isset($_SESSION['adminID'])) {
    header('Location: index.php');
    exit;
}

$id = intval($_POST['productID'] ?? 0);
if ($id <= 0) die('Invalid ID');

$sql = "
UPDATE product
SET productStatus =
    CASE
        WHEN productStatus = 1 THEN 0
        ELSE 1
    END
WHERE productID = ?
";

$stmt = $_db->prepare($sql);
$stmt->execute([$id]);

set_popup('Product status updated');
redirect('list.php');

