<?php
require '../_base.php';
$_title = 'Product Details';
include 'admin_head.php';

// ----------------------------------------------------------------------------

// HANDLE QUANTITY CHANGE
if (isset($_POST['quantity'])) {
    $id  = req('productID');
    $qty = req('quantity');

    $stm = $_db->prepare('UPDATE product SET quantity = ? WHERE productID = ?');
    $stm->execute([$qty, $id]);

    redirect("product_detail.php?productID=$id");
}
$id  = req('productID');
$stm = $_db->prepare('SELECT * FROM product WHERE productID = ?');
$stm->execute([$id]);
$p = $stm->fetch();
if (!$p) {
    redirect('product_list.php');
}

// ----------------------------------------------------------------------------

?>
<section>
<style>
    #photo {
        display: block;
        border: 1px solid #333;
        width: 200px;
        height: 200px;
    }
</style>
<p>
    <img src="/photos/<?= $p->photo ?>" productID="photo">
</p>

<table class="table">
    <tr>
        <th>productID</th>
        <td><?= $p->productID ?></td>
    </tr>
    <tr>
        <th>Name</th>
        <td><?= $p->productName ?></td>
    </tr>
    <tr>
        <th>Price</th>
        <td>RM <?= $p->productPrice ?></td>
    </tr>
    <tr>
        <th>quantity</th>
        <td>
            <?php 
            $id = $p->productID;
            $qty = $p->quantity ?? 0;
            $options = [];
            for ($i = 1; $i <= $qty; $i++) {
                $options[$i] = $i;
            }
            ?>
            <form method="post" id="qtyForm">
                <?= html_hidden('productID', $id) ?>
                <?= html_select('quantity', $options, '') ?>  
                <?=  $qty ? '✅':'' ?>
            </form>
        </td>
    </tr>
</table>

<p>
    <button data-get="product_list.php">List</button>
</p>

<script>
    document.querySelector('select')?.addEventListener('change', e => e.target.form.submit());
</script>
</section>
<?php include '../_foot.php'; ?>