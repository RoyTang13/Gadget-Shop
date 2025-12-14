<?php
require '../_base.php';
// ----------------------------------------------------------------------------

if (is_post()) {
    $productID = req('productID');

    // Validate productID
    if (!$productID) {
        temp('error', 'Product ID is missing.');
        redirect('/admin/product_list.php');
        exit;
    }

    // Fetch and delete photo if it exists
    $stm = $_db->prepare('SELECT productPhoto FROM product WHERE productID = ?');
    $stm->execute([$productID]);
    $photo = $stm->fetchColumn();
        
    if ($photo && file_exists("../photos/$photo")) {
        unlink("../photos/$photo");
    }

    // Delete from database
    $stm = $_db->prepare('DELETE FROM product WHERE productID = ?');
    $stm->execute([$productID]);

    temp('info', 'The product has been deleted successfully.');
}

redirect('/product/list.php');
