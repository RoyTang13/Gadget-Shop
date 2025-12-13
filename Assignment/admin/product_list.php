<style>
        .button {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 10px 12px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 2px 2px;
            cursor: pointer;
            border-radius: 2px;
        }
        .button:hover
        {
            background-color: #45a049;
        }
        .cats-list {
            list-style: none;
            padding: 5px;
            margin: 0;
        }
        .cats-list li {
            margin-bottom: 5px;
        }
        .center { text-align: center; }
        .table th, .table td { text-align: center; vertical-align: middle; }
        .desc-short { display: inline; }
        .desc-full { display: none; }
        .show-more { background: none; border: none; color: #007bff; cursor: pointer; padding: 0; font-size: 0.95em; text-decoration: none; }
</style>

<?php
require '../_base.php';
$_title = 'Product List';
include 'admin_head.php';
$where = [];
$params = [];

// Functionable Sorting
$order = "productID ASC";
// Default sorting by productID ascending
if (!empty($_GET['sort_name'])) {
    $order = "productName " . ($_GET['sort_name'] === 'desc' ? "DESC" : "ASC");
}

// Build the final query
$sql = "SELECT productID, productName, productPrice, productQty, productPhoto, productCat1, productCat2, productCat3, productDesc FROM product";  
$sql .= " ORDER BY $order";
$stm = $_admin_db->query("
    SELECT productID, productName, productPrice, productQty, 
           productPhoto, productCat1, productCat2, productCat3, productDesc
    FROM product 
    ORDER BY productID ASC
");
?>
<section>
<main>
    <h1 class="center">Product List</h1>
    <p class="center"><?= $stm->rowCount() ?> product(s)</p>
    <table class ="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Photo</th>
                <th>Name</th>
                <th>Description</th>
                <th>Categories</th>
                <th>Price(RM)</th>
                <th>Quantity in stock</th>
                <th class="text-center" colspan="2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stm = $_admin_db->query("SELECT productID, productName, productPrice, productQty,productPhoto, productCat1, productCat2, productCat3, productDesc FROM product ORDER BY productID ASC");
            while ($product = $stm->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($product['productID']) . "</td>";
                echo "<td><img src=\"/photos/" . htmlspecialchars($product['productPhoto']) . "\" alt=\"Product Photo\" style=\"max-width:100px; max-height:100px;\"></td>";
                echo "<td>" . htmlspecialchars($product['productName']) . "</td>";

                $desc = $product['productDesc'] ?? '';
                $short = mb_substr($desc, 0, 100);// Show first 100 characters
                $needs_more = mb_strlen($desc) > 100;// Check if full description is longer
                    echo '<td>';
                    echo '<div class="desc" id="desc-' . htmlspecialchars($product['productID']) . '">';
                    echo '<span class="desc-short">' . htmlspecialchars($short) . ($needs_more ? '...' : '') . '</span>';
                    if ($needs_more) {
                        echo '<span class="desc-full">' . htmlspecialchars($desc) . '</span> ';
                        echo '<button type="button" class="show-more" data-id="' . htmlspecialchars($product['productID']) . '">Show more</button>';
                    }

                echo '</div>';
                echo '</td>';
                
                $cats = array_filter([$product['productCat1'], $product['productCat2'], $product['productCat3']]);
                    echo '<td><ul class="cats-list">';
                    foreach ($cats as $cat) {
                        echo '<li>' . htmlspecialchars($cat) . '</li>';} 
                    echo '</ul></td>';
                echo "<td>" . htmlspecialchars($product['productPrice']) . "</td>";
                echo "<td>" . htmlspecialchars($product['productQty']) . "</td>";
                echo '<td> <button class ="button"><a href="product_edit.php?id=' . urlencode($product['productID']) . '" class="button">Edit</a></button></td>';
                echo '<td> <button class ="button"><a href="product_delete.php?id=' . urlencode($product['productID']) . '" class="button" onclick="return confirm(\'Are you sure you want to delete this product?\')">Delete</a></button></td>'; 
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
   <!-- Trigger the modal with a button -->
  <button data-toggle="modal" data-target="#myModal">
    Add Product
  </button>

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">New Product Item</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form action="../product/Create.php" enctype="multipart/form-data" method="POST">
            <div class="form-group">
              <label for="name">Product Name:</label>
              <input type="text" class="form-control" id="p_name" required>
            </div>
            <div class="form-group">
              <label for="price">Price:</label>
              <input type="number" class="form-control" id="p_price" required>
            </div>
            <div class="form-group">
              <label for="qty">Description:</label>
              <input type="text" class="form-control" id="p_desc" name="productDesc" required>
            </div>
            <div class="form-group">
              <label>Category:</label>
              <select id="category" name="productCat1">
                <option disabled selected value="">Select category</option>
                <?php
                  try {
                    $catStm = $_db->query("SELECT * FROM category");
                    while ($row = $catStm->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="' . htmlspecialchars($row['category_id'] ?? ($row['id'] ?? '')) . '">' . htmlspecialchars($row['category_name'] ?? ($row['name'] ?? '')) . '</option>';
                    }
                  } catch (Exception $ex) {
                    // category table may not exist or be empty — fail silently
                  }
                ?>
              </select>
            </div>
            <div class="form-group">
                <label for="file">Choose Image:</label>
                <input type="file" class="form-control-file" id="file" name="productPhoto">
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-secondary" id="upload" style="height:40px">Add Item</button>
            </div>
          </form>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" style="height:40px">Close</button>
        </div>
      </div>
      
    </div>
  </div>

</main>
</section>

<script>
document.addEventListener('click', function(e){
    if (!e.target.matches('.show-more')) return;
    var btn = e.target;
    var id = btn.getAttribute('data-id');
    var container = document.getElementById('desc-' + id);
    if (!container) return;
    var shortEl = container.querySelector('.desc-short');
    var fullEl = container.querySelector('.desc-full');
    if (!fullEl) return;
    if (fullEl.style.display === 'none' || fullEl.style.display === '') {
        fullEl.style.display = 'inline';
        shortEl.style.display = 'none';
        btn.textContent = 'Show less';
    } else {
        fullEl.style.display = 'none';
        shortEl.style.display = 'inline';
        btn.textContent = 'Show more';
    }
});
</script>
</body>
</html>
