<?php
require '../_base.php';

// OPTIONAL: admin login check
 if (!isset($_SESSION['adminID'])) redirect('/admin/login.php');

if (is_post()) {
    $orderID = $_POST['orderID'] ?? null;
    $status  = $_POST['status'] ?? null;

    if (!$orderID || !$status) {
        redirect('order_list.php');
        exit;
    }

    $sql = "UPDATE orders SET status = ? WHERE orderID = ?";
    $stmt = $_db->prepare($sql);
    $stmt->execute([$status, $orderID]);

    set_popup('Order status updated successfully.');
    redirect("order_detail.php?id=$orderID");
}
