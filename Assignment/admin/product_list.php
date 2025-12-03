<?php
require '../_base.php';
$_title = 'product List';
include 'admin_head.php';
?>

<main>
    <h1>Product List</h1>
    <table class ="table">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Product Price</th>
                <th>Product Quantity</th>
                <th colspan="3">Product Category</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stm = $_admin_db->query("SELECT productID, productName, productPrice, productQty, productCat1, productCat2, productCat3 FROM product ORDER BY productID ASC");
            while ($product = $stm->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($product['productID']) . "</td>";
                echo "<td>" . htmlspecialchars($product['productName']) . "</td>";
                echo "<td>" . htmlspecialchars($product['productPrice']) . "</td>";
                echo "<td>" . htmlspecialchars($product['productQty']) . "</td>";
                    echo "<td>" . htmlspecialchars($product['productCat1']) . "</td>";
                    echo "<td>" . htmlspecialchars($product['productCat2']) . "</td>";
                    echo "<td>" . htmlspecialchars($product['productCat3']) . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</main>
</body>
</html>
