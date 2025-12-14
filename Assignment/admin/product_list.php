<?php
require '../_base.php';
$_title = 'Product List';
include 'admin_head.php';
// make sure only logged-in admins can access this page
if (!isset($_SESSION['adminID'])) {
    header('Location: index.php');
    exit;
}
$where = [];
$params = [];
// Functionable Searching
if (!empty($_GET['search'])) {
    $where[] = "productName LIKE :search";
    $params[':search'] = '%' . $_GET['search'] . '%';
}

// Functionable Filtering
    // 1. Connectivity
if (!empty($_GET['connectivity'])) {
    $placeholders = [];
    foreach ($_GET['connectivity'] as $i => $c) {
        $key = ":conn$i";
        $placeholders[] = $key;
        $params[$key] = $c;
    }
    $where[] = "productCat1 IN (" . implode(",", $placeholders) . ")";
}

    // 2. Fit Type
if (!empty($_GET['design'])) {
    $placeholders = [];
    foreach ($_GET['design'] as $i => $d) {
        $key = ":design$i";
        $placeholders[] = $key;
        $params[$key] = $d;
    }
    $where[] = "productCat2 IN (" . implode(",", $placeholders) . ")";
}

    // 3. Acoustic
if (!empty($_GET['acoustic'])) {
    $placeholders = [];
    foreach ($_GET['acoustic'] as $i => $a) {
        $key = ":acoustic$i";
        $placeholders[] = $key;
        $params[$key] = $a;
    }
    $where[] = "productCat3 IN (" . implode(",", $placeholders) . ")";
}

// Functionable Sorting
$order = "productID ASC";

if (!empty($_GET['sort_name'])) {
    $order = "productName " . ($_GET['sort_name'] === 'desc' ? "DESC" : "ASC");
}
// Handle price sorting
if (!empty($_GET['sort_price'])) {
    $order = "productPrice " . ($_GET['sort_price'] === 'desc' ? "DESC" : "ASC");
}
// Handle ID sorting
if (!empty($_GET['sort_id'])) {
    $order = "productID " . ($_GET['sort_id'] === 'desc' ? "DESC" : "ASC");
}

// Functionable Paging
$page = max(1, intval($_GET['page'] ?? 1));// Default to page 1 if not set or invalid
$limit = 6;  // number of products per page
$offset = ($page - 1) * $limit;// Calculate offset

$sql = "SELECT * FROM product";

if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY $order LIMIT $limit OFFSET $offset";

$stm = $_db->prepare($sql);
$stm->execute($params);
$arr = $stm->fetchAll(PDO::FETCH_OBJ);

// Helper function to build query string preserving current filters/search/sort
function buildQueryString(array $overrides = []): string {
    $keepKeys = ['search', 'connectivity', 'design', 'acoustic', 'sort_name', 'sort_price', 'sort_id' , 'page'];
    $parts = [];

    foreach ($keepKeys as $key) {
        // If override explicitly provided
        if (array_key_exists($key, $overrides)) {
            $val = $overrides[$key];
            if ($val === null) {
                continue; // remove this param
            }
            if (is_array($val)) {
                foreach ($val as $v) {
                    $parts[] = urlencode($key) . '[]=' . urlencode((string)$v);
                }
            } else {
                $parts[] = urlencode($key) . '=' . urlencode((string)$val);
            }
            continue;
        }

        // Otherwise take from current GET if present
        if (!isset($_GET[$key])) continue;
        $val = $_GET[$key];
        if (is_array($val)) {
            foreach ($val as $v) {
                $parts[] = urlencode($key) . '[]=' . urlencode((string)$v);
            }
        } else {
            $parts[] = urlencode($key) . '=' . urlencode((string)$val);
        }
    }

    return $parts ? '?' . implode('&', $parts) : '';
}

?>

<section>
<main>
<h1 class="text-center">Product List</h1>
<div class = "browser"> 
<form method = "get" action = "/admin/product_list.php">
      <div class = "search">
        <!-- Connection Filter -->
        <div class = "dropdown">
            <button class = "dropbtn">🔌Connection</button>
            <div class = "dropdown-content">
                <label><input type = "checkbox" name = "connectivity[]" value = "wired"> Wired</label>
                <label><input type = "checkbox" name = "connectivity[]" value = "wireless"> Wireless</label>
            </div>
        </div>

        <!-- Fit Type Filter -->
        <div class = "dropdown">         
            <button class = "dropbtn">🎧Fit Type</button>
            <div class = "dropdown-content">
                <label><input type = "checkbox" name = "design[]" value = "in-ear"> In-ear</label>
                <label><input type = "checkbox" name = "design[]" value = "over-ear"> Over-ear</label>
            </div>              
        </div>

        <!-- Acoustic Filter -->
        <div class = "dropdown">            
            <button class = "dropbtn">🎶Acoustic</button>
            <div class = "dropdown-content">
                <label><input type = "checkbox" name = "acoustic[]" value = "noise-canceled"> Noise-canceled</label>
                <label><input type = "checkbox" name = "acoustic[]" value = "balanced"> Balanced</label>
                <label><input type = "checkbox" name = "acoustic[]" value = "clear vocals"> Clear Vocals</label>
            </div>
        </div>

        <div class = "search_bar">
            <input type = "search"
                name = "search" 
                placeholder = "Type the product name..." 
                value = "<?= ($_GET['search'] ?? '') ?>"
                class = "search_bar-font">        
            <button type = "Submit" class = "search_bar-button">Search</button>
        </div>
    </div>
</div>


<!-- Sort Bar + Paging -->
<div class = "sort_bar">
    <div class = "sorting_left">
        <?php
            // Toggle for name sort
            $currentName = $_GET['sort_name'] ?? null;
            $nextName = ($currentName === 'asc') ? 'desc' : 'asc';

            // Toggle for price sort
            $currentPrice = $_GET['sort_price'] ?? null;
            $nextPrice = ($currentPrice === 'asc') ? 'desc' : 'asc';

            // Toggle for ID sort
            $currentId = $_GET['sort_id'] ?? null;
            $nextId = ($currentId === 'asc') ? 'desc' : 'asc';
        ?>
        <h5>Sorting By: </h5>

        <!-- Sort by ID button -->
         <a href = "/admin/product_list.php<?= buildQueryString(['sort_id' => $nextId, 'sort_name' => null, 'sort_price' => null, 'page' => 1]) ?>" class = "sort-btn">
            ID <?= $nextId === 'asc' ? '⇓' : '⇑' ?>
        </a>

        <!-- Sort by Name button -->
        <a href = "/admin/product_list.php<?= buildQueryString(['sort_name' => $nextName, 'sort_price' => null, 'page' => 1]) ?>" class = "sort-btn">
            Name <?= $nextName === 'asc' ? '⇓' : '⇑' ?>
        </a>
        
        <!-- Sort by Price button -->
        <a href = "/admin/product_list.php<?= buildQueryString(['sort_price' => $nextPrice, 'sort_name' => null, 'page' => 1]) ?>" class = "sort-btn">
            Price <?= $nextPrice === 'asc' ? '⇓' : '⇑' ?>  
        </a>

    </div>

    <div class = "sorting_right">
        <!-- Paging with textable page number -->
        <div class = "pagination">
            <button class = "pagination-btn" id = "prevBtn" type = "button">‹</button>
            <input type = "number" 
                   id = "pageInput" 
                   class = "page-input" 
                   min = "1" 
                   value = "<?= $page ?>"
                   placeholder = "Page">
            <button class = "pagination-btn" id = "nextBtn" type = "button">›</button>
        </div>
    </div>
</div>
</form>
    <table class ="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Photo</th>
                <th>Name</th>
                <th>Categories</th>
                <th>Price(RM)</th>
                <th>Stock quantity </th>
                <th class="text-center" colspan="3">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($arr as $product) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($product->productID) . "</td>";
              echo "<td><img src=\"/photos/" . htmlspecialchars($product->productPhoto) . "\" alt=\"Product Photo\" style=\"max-width:100px; max-height:100px;\"></td>";
              echo "<td>" . htmlspecialchars($product->productName) . "</td>";

              $cats = array_filter([$product->productCat1, $product->productCat2, $product->productCat3]);
              echo '<td><ul class="cats-list">';
             foreach ($cats as $cat) {
                echo '<li>' . htmlspecialchars($cat) . '</li>';
              }
              echo '</ul></td>';

              echo "<td>" . htmlspecialchars($product->productPrice) . "</td>";
              echo "<td>" . htmlspecialchars($product->productQty) . "</td>";
              echo '<td> <a href="product_edit.php?id=' . urlencode($product->productID) . '" class="button">Edit</a></td>';
              echo '<td> <a href="../product/Delete.php?id=' . urlencode($product->productID) . '" class="button" onclick="return confirm(\'Are you sure you want to delete this product?\')">Delete</a></td>'; 
              echo "</tr>";
            }
            ?>
        </tbody>
    </table>
  <td> <button class ="button"><a href="../product/Create.php" class="button">Add Product</a></button></td>
</main>
</section>
<script>
    // Paging Script
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const pageInput = document.getElementById('pageInput');

    prevBtn.addEventListener('click', () => {
        let currentPage = parseInt(pageInput.value);
        if (currentPage > 1) {
            currentPage--;
            window.location.href = "/admin/product_list.php<?= buildQueryString(['page' => '']) ?>&page=" + currentPage;
        }
    });

    nextBtn.addEventListener('click', () => {
        let currentPage = parseInt(pageInput.value);
        currentPage++;
        window.location.href = "/admin/product_list.php<?= buildQueryString(['page' => '']) ?>&page=" + currentPage;
    });

    pageInput.addEventListener('change', () => {
        let desiredPage = parseInt(pageInput.value);
        if (desiredPage >= 1) {
            window.location.href = "/admin/product_list.php<?= buildQueryString(['page' => '']) ?>&page=" + desiredPage;
        }
    });
</script>
</body>
</html>

<style>
    
  /* ---------------------------------------------- */
    /* Table Style */
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
    z-index: 5;
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
    .table th, .table td { text-align: center; vertical-align: middle; }
</style>
<?php include '../_foot.php'; ?>