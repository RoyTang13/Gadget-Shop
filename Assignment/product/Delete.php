<?php
require '../_base.php';
// ----------------------------------------------------------------------------

if (is_post()) {
    $productID = req('productID');

    $stm = $_db->prepare('SELECT productPhoto FROM product WHERE productID = ?');
    $stm->execute([$productID]);
    $photo = $stm->fetchColumn();
        
    if ($photo && file_exists("../photos/$photo")) {
        unlink("../photos/$photo");
    }

    $stm = $_db->prepare('DELETE FROM product WHERE productID = ?');
    $stm->execute([$productID]);

    temp('info', 'The product is deleted.');
}

redirect('/product/list.php');
