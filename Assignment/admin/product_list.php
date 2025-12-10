<?php
require '../_base.php';
$_title = 'Product List';
include 'admin_head.php';

$stm = $_admin_db->query("
    SELECT productID, productName, productPrice, productQty, 
           productPhoto, productCat1, productCat2, productCat3, productDesc
    FROM product 
    ORDER BY productID ASC
");
?>
<section>
<main>
    <h1>Product List</h1>
    <p><?= $stm->rowCount() ?> product(s)</p>
    <table class ="table">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Product Price(RM)</th>
                <th>Product Quantity</th>
                <th>Product Photo</th>
                <th>Product Category</th>
                <th>Product decription</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stm = $_admin_db->query("SELECT productID, productName, productPrice, productQty,productPhoto, productCat1, productCat2, productCat3, productDesc FROM product ORDER BY productID ASC");
            while ($product = $stm->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($product['productID']) . "</td>";
                echo "<td>" . htmlspecialchars($product['productName']) . "</td>";
                echo "<td>" . htmlspecialchars($product['productPrice']) . "</td>";
                echo "<td>" . htmlspecialchars($product['productQty']) . "</td>";
                echo "<td><img src=\"/photos/" . htmlspecialchars($product['productPhoto']) . "\" alt=\"Product Photo\" style=\"max-width:100px; max-height:100px;\"></td>";
                    $cats = array_filter([$product['productCat1'], $product['productCat2'], $product['productCat3']]);
                    echo "<td>" . htmlspecialchars(implode(', ', $cats)) . "</td>" ;
                echo "<td><a href=\"product_desc.php?id=" . urlencode($product['productID']) . "\" class=\"btn btn-primary\">Detail</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</main>
        </section>
</body>
</html>
