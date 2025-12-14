<?php
require '../_base.php';

$_title = 'Product List';

include '../admin/admin_head.php';

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

if (!empty($_GET['sort_price'])) {
    $order = "productPrice " . ($_GET['sort_price'] === 'desc' ? "DESC" : "ASC");
}

// Functionable Paging
$page = max(1, intval($_GET['page'] ?? 1));  // Default to page 1 if not set or invalid
$limit = 12;  // number of products per page

// Get total products first (to calculate totalPages)
$countSql = "SELECT COUNT(*) FROM product";
if ($where) {
    $countSql .= " WHERE " . implode(" AND ", $where);
}
$countStm = $_db->prepare($countSql);
$countStm->execute($params);
$totalProducts = (int)$countStm->fetchColumn();
$totalPages = max(1, ceil($totalProducts / $limit));

// Clamp page to valid range
$page = min($page, $totalPages);
$offset = ($page - 1) * $limit;  // Now offset will never be negative

// Build the main SQL
$sql = "SELECT * FROM product";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY $order LIMIT $limit OFFSET $offset";

$stm = $_db->prepare($sql);
$stm->execute($params);
$arr = $stm->fetchAll(PDO::FETCH_OBJ);

// Fetch total products
$countSql = "SELECT COUNT(*) FROM product";

if ($where) {
    $countSql .= " WHERE " . implode(" AND ", $where);
}

$countStm = $_db->prepare($countSql);
$countStm->execute($params);
$totalProducts = (int)$countStm->fetchColumn();

$totalPages = max(1, ceil($totalProducts / $limit));

// Helper function to build query string preserving current filters/search/sort
function buildQueryString(array $overrides = []): string {
    $keepKeys = ['search', 'connectivity', 'design', 'acoustic', 'fixedPrice', 'customMin', 'customMax', 'sort_name', 'sort_price', 'page'];
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
<h1 class = "text-center">Product List</h1> <!-- TO MODIFY -->
<div class = "browser"> 
<form method = "get" action = "product/list.php">
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
                    class = "search_bar-font"
                    >        
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
        ?>
        <h5>Sorting By: </h5>

        <!-- Sort by Name button -->
        <a href = "list.php<?= buildQueryString(['sort_name' => $nextName, 'sort_price' => null, 'page' => 1]) ?>" class = "sort-btn">
            Name <?= $nextName === 'asc' ? '⇓' : '⇑' ?>
        </a>
        
        <!-- Sort by Price button -->
        <a href = "list.php<?= buildQueryString(['sort_price' => $nextPrice, 'sort_name' => null, 'page' => 1]) ?>" class = "sort-btn">
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
    <p class="total_products"><?= $totalProducts ?> product(s)</p>
    <table class ="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Categories</th>
                <th>Price(RM)</th>
                <th>Stock quantity </th>
                <th class="text-center" colspan="2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($arr as $product) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($product->productID) . "</td>";
              echo "<td>" . htmlspecialchars($product->productName) . "</td>";
              $cats = array_filter([$product->productCat1, $product->productCat2, $product->productCat3]);
              echo '<td><ul class="cats-list">';
             foreach ($cats as $cat) {
                echo '<li>' . htmlspecialchars($cat) . '</li>';
              }
              echo '</ul></td>';

              echo "<td>" . htmlspecialchars($product->productPrice) . "</td>";
              echo "<td>" . htmlspecialchars($product->productQty) . "</td>";
              echo '<td> <a href="../product/Update.php?id=' . urlencode($product->productID) . '" class="button">Update</a>';
              echo '<form action="../product/Delete.php" method="post" onsubmit="return confirm(\'Are you sure you want to delete this product?\')" style="display:inline;">';
              echo '<input type="hidden" name="productID" value="' . htmlspecialchars($product->productID) . '">';
              echo '<button type="submit" class="button">Delete</button>';
              echo '</form>';
              echo '<img src="/photos/' . htmlspecialchars($product->productPhoto) . '" class="popup" style="max-width:80px; cursor:pointer;">';
              echo "</tr>";
            }
            ?>
        </tbody>
    </table>
  <td> <button class ="button"><a href="../product/Create.php" class="button">Add Product</a></button></td>
</main>
</section>

<!-- Script for Paging buttons -->
<script>
    window.addEventListener('load', function() {
        // Handle sorting dropdown change
        const sortSelect = document.getElementById('sort');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                const value = this.value;
                let overrides = { page: 1 };  // Always reset to page 1 on sort change

                if (value === 'price-asc') {
                    overrides.sort_price = 'asc';
                    overrides.sort_name = null;  // Clear name sort
                } 
                else if (value === 'price-desc') {
                    overrides.sort_price = 'desc';
                    overrides.sort_name = null;
                } 
                else if (value === 'name-asc') {
                    overrides.sort_name = 'asc';
                    overrides.sort_price = null;  // Clear price sort
                }       
                else if (value === 'name-desc') {
                    overrides.sort_name = 'desc';
                    overrides.sort_price = null;
                }

                // Build new URL preserving filters/search using URLSearchParams
                const params = new URLSearchParams(window.location.search);
                
                // Apply overrides
                for (const [key, val] of Object.entries(overrides)) {
                    if (val === null) {
                        params.delete(key);
                    }  
                    else if (Array.isArray(val)) {
                        params.delete(key);
                        val.forEach(v => params.append(key, v));
                    }  
                    else {
                        params.set(key, val);
                    }
                }

                const newUrl = window.location.pathname + '?' + params.toString();
                window.location.href = newUrl;
            });
        }

        // Handle pagination: preserve all filters/sorts
        function goToPage(n) {
            // Disable buttons to prevent multiple clicks
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const pageInput = document.getElementById('pageInput');
            if (prevBtn) prevBtn.disabled = true;
            if (nextBtn) nextBtn.disabled = true;
            if (pageInput) pageInput.disabled = true;

            const params = new URLSearchParams(window.location.search);
            params.set('page', String(Math.max(1, parseInt(n) || 1)));
            window.location.href = window.location.pathname + '?' + params.toString();
        }

        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const pageInput = document.getElementById('pageInput');

        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                const current = parseInt(pageInput.value) || 1;
                if (current > 1) goToPage(current - 1);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                const current = parseInt(pageInput.value) || 1;
                goToPage(current + 1);
            });
        }

        if (pageInput) {
            pageInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    goToPage(this.value);
                }
            });
        }
    });
</script>

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

<style>
/* Search bar style */
.search_bar-font {
    font-style: italic;
    font-size: 12px;
    font-family: 'Courier New', Courier, monospace;
    font-weight: lighter;
    width: 300px;
    height: 30px;
    padding: 3px 0px 2px 6px;
    border: #000 solid 2px;
}

/* "Search" button style */
.search_bar-button {
    background: #be06ec;
    color: #fff;
    padding: 4px 17px;
    width: 100px;
    font-family: 'Courier New', Courier, monospace;
    font-size: 16px;
    border: #000 solid 2px;
    cursor: pointer;
}

    /* Change background color of "Search" button */
    .search_bar-button:hover {
        background: #d17de6;
    }

    /* Distance between buttons and search bar */
    .search {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 25px;
        margin-left: 40px;
        margin-top: 15px;
      }

    /* Adjust dropdown position */
    .browser .search .dropdown {
        position: relative;
        z-index: 10;
    }

    /* Style the dropdown button */
    .browser .search .dropbtn {
        background-color: #be06ec;
        color: #fff;
        padding: 4px 17px;
        width: 185px;
        font-family: 'Courier New', Courier, monospace;
        font-size: 16px;
        border: #000 solid 2px;
        cursor: pointer;
        z-index: 1px;
    }

    /* Change background color of dropdown button on hover */
    .browser .search .dropdown:hover .dropbtn {
        background-color: #d17de6;
    }

    /* Dropdown content */
    .browser .search .dropdown-content {
        display: none;
        position: absolute;
        background-color: #fccdfb;
        border: #341738 ridge 2px;
        width: 205px;
        font-family: 'Courier New', Courier, monospace;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    }

    /* Links inside dropdown content */
    .browser .search .dropdown-content label {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 30px 8px 10px;
        cursor: pointer;
    }

    /* Change selection style from Dropdowns */
    .browser .search .dropdown-content input[type="checkbox"] {
        cursor: pointer;
        width: 15px;
        height: 15px;
    }

    /* Show the dropdown content on hover */
    .browser .search .dropdown:hover .dropdown-content {
        display: block;
    }

    /* ---------------------------------------------- */
    /* Part 3 - Price Range */

    /* Dropdown Row */
    .browser .search .dropdown-row {
        display: block;
        color: #000;
        white-space: nowrap;
        position: relative;
        padding: 12px 16px;
        cursor: pointer;
    }

    /* Change color of dropdown row links on hover */
    .browser .search .dropdown-row:hover span {
        background-color: #fff
    }

    /* Links inside dropdown content */
    .browser .search .dropdown-content .dropdown-row {
        color: #000;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    /* Change color of dropdown links on hover */
    .browser .search .dropdown-content .dropdown-row:hover {
        background-color: #fff
    }

    /* Dropdown subcontent */
    .browser .search .dropdown-subcontent {
        display: none;
        position: absolute;
        left: 100%;
        top: 0;
        background-color: #fccdfb;
        border: #341738 ridge 2px;
        min-width: 10px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    }

    /* Show the dropdown subcontent on hover */
    .browser .search .dropdown-row:hover .dropdown-subcontent {
        display: block;
    }

    /* Links inside dropdown subcontent */
    .browser .search .dropdown-subcontent label {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 30px 8px 10px;
        cursor: pointer;
    }

    /* Change color of dropdown subcontent links on hover */
    .browser .search .dropdown-subcontent:hover span {
        background-color: #fff
    }

    /* Change style inside box from Custom Range */
    .browser .search .dropdown-subcontent input[type="number"] {
        font-family: 'Courier New', Courier, monospace;
        font-size: 12px;
        padding: 4px;
        border: 1px solid #888;
        border-radius: 4px;
    }

    /* Change "Apply" button style inside Custom Range */
    .browser .search .dropdown-subcontent button {
        padding: 6px 15px;
        background-color: #be06ec;
        color: #fff;
        border: #000 solid 2px;
        cursor: pointer;
        border-radius: 6px;
        font-family: 'Courier New', Courier, monospace;
    }

    /* ---------------------------------------------- */
    /* Part 4 - Browser Frame */
    .browser {
        border: 15px solid #c764e0;
        border-radius: 15px;
        padding: 10px 0px 15px 0%;
        margin-top: 10px 150px;
        background-color: #440552d5;
        background-position: top;
        top: 155px;
        z-index: 8;
    }

    /* ---------------------------------------------- */
    /* Part 5 - Sorting Section */

    /* Style the sorting title */
    .sort_bar .sorting_left h5 {
        color: #fff;
        font-family: 'Courier New', Courier, monospace;
        font-size: 16px;
        font-weight: 600;
        margin: 0px;
    }

    /* Style sorting buttons */
    .sort_bar .sorting_left .sort-btn {
        background-color: #be06ec;
        color: #fff;
        padding: 4px 14px;
        width: 135px;
        font-family: 'Courier New', Courier, monospace;
        font-size: 16px;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        border: #000 solid 2px;
        border-radius: 5px;
        cursor: pointer;
    }

    .sort_bar .sorting_left .sort-btn:hover {
        background-color: #d17de6;
    }

    /* Paging button */
    .sort_bar .sorting_right .pagination-btn {
        width: 33px;
        height: 28px;
        padding: 0px 0px 3px 0px;
        border: 1.6px solid #b357ff;
        background: #f1b9ff;
        color: #560065;
        cursor: pointer;
        font-size: 20.8px;
        font-weight: bold;
    }

    /* After cursor hover the paging button */
    .sort_bar .sorting_right .pagination-btn:hover {
        background: #f4c9ff;
        border-color: #aa43ff;
    }

    /* Paging input */
    .sort_bar .sorting_right .page-input {
        width: 148px;
        height: 26px;
        padding: 2px 0px 0px 0px;
        border: 1.6px solid #b357ff;
        background: #f1b9ff;
        color: #560065;
        cursor: text;
        font-family:'Courier New', Courier, monospace;
        font-size: 14px;
        font-weight: 600;
        text-align: center;
    }

    /* After hover the page input */
    .sort_bar .sorting_right .page-input:focus {
        outline: none;
        border-color: #c764e0;
        box-shadow: 0 0 4px rgba(199, 100, 224, 0.3);
    }

    /* Hide the spin-number-scroller from page input */
    .page-input::-webkit-inner-spin-button,
    .page-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* ---------------------------------------------- */
    /* Part 6 - Sorting Frame */

    /* Sorting alignment left & right*/
    .sort_bar .sorting_left {
        display: flex;
        justify-content: left;
        align-items: center;
        gap: 5px 25px;
        margin-top: 10px;
        margin-left: 40px;
    }

    .sort_bar .sorting_right {
        display: flex;
        justify-content: right;
        align-items: center;
        margin-right: 20px;
        margin-top: 15px;
    }

    /* Sorting bar style */
    .sort_bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 4px solid #c764e0;
        border-radius: 5px;
        padding: 10px 20px;
        margin: 10px 20px;
        background: linear-gradient(to bottom, #68077dd5, #440552d5);
    }

    .total_products{
        text-align: center;
        color: #fff;
        font-family: 'Courier New', Courier, monospace;
        font-size: 16px;
        font-weight: 600;
        margin: 10px 20px;
        flex:1;
    }

    /* ---------------------------------------------- */
    /* Part 7 - Table Style */

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

  .button:hover {
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

  .table td:has(.popup) {
    position: relative;
}

.table .popup {
    position: absolute;
    top: 50%;
    left: 100%;
    translate: 5px -50%;
    z-index: 1;
    border: 1px solid #333;
    display: none;
}

.table tr:hover .popup {
    display: block;
}

  .table th, .table td { text-align: center; vertical-align: middle; }
  .desc-short { display: inline; }
  .desc-full { display: none; }
  .show-more { background: none; border: none; color: #007bff; cursor: pointer; padding: 0; font-size: 0.95em; text-decoration: none; }
</style>
