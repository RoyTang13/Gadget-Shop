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
<h1 class = "text-center">Product List</h1> 
<div class = "browser"> 
<form method = "get">
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
    <p class="total_products"><?= $totalProducts ?> total product(s)</p>
    <div class="table-wrapper">
    <table class ="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Photo</th>
                <th>Name</th>
                <th>Categories</th>
                <th>Price(RM)</th>
                <th>Stock quantity </th>
                <th>Status</th>
                <th class="text-center" colspan="2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($arr as $product) {
                $statusLabel = 'Hidden';
                $statusClass = 'status-hidden';
                    if ($product->productStatus === 1) {
                        $statusLabel = 'Public';
                        $statusClass = 'status-public';
                    }
              echo "<tr>";
              echo "<td>" . htmlspecialchars($product->productID) . "</td>";
              echo '<td><img src="/photos/' . htmlspecialchars($product->productPhoto) . '" style="max-width:80px;"></td>';
              echo "<td>" . htmlspecialchars($product->productName) . "</td>";
              
              $cats = array_filter([$product->productCat1, $product->productCat2, $product->productCat3]);
              echo '<td><ul class="cats-list">';
             foreach ($cats as $cat) {
                echo '<li>' . htmlspecialchars($cat) . '</li>';
              }
              echo '</ul></td>';

              echo "<td>" . htmlspecialchars($product->productPrice) . "</td>";
              echo "<td>" . htmlspecialchars($product->productQty) . "</td>";
              echo "<td><span class='$statusClass'>$statusLabel</span></td>";
              echo '<td> <a href="../product/Update.php?id=' . urlencode($product->productID) . '" class="button">Update</a>';
              
              /*  TOGGLE STATUS BUTTON  */
              echo '<form action="../product/ToggleStatus.php" method="post" style="display:inline;">';
              echo '<input type="hidden" name="productID" value="' . htmlspecialchars($product->productID) . '">';
              echo '<button type="submit" class="button">';
              echo ($product->productStatus === 1) ? 'Hide' : 'Publish';
              echo '</button>';
              echo '</form> ';
              echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    </div>
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
                
                // Apply overrides to params
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
 
/* -------------------- */
/* Part 7 - Table Style */
/* ==================== */
/* Base Layout */

body {
    background: linear-gradient(#e0e7ff, #e0e7ff);
    font-family: system-ui, -apple-system, Segoe UI, sans-serif;
}

main {
    max-width: 1400px;
    margin: auto;
    padding: 20px;
}

h1 {
    margin-bottom: 15px;
    font-weight: 600;
}

/* ================================================= */
/* Filter / Search Bar */

.browser {
    background: #fff;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    margin-bottom: 15px;
}

.search {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    align-items: center;
}

.dropdown {
    position: relative;
}

.dropbtn {
    background: #6c63ff;
    color: #fff;
    border: none;
    padding: 8px 14px;
    border-radius: 6px;
    cursor: pointer;
}

.dropdown-content {
    display: none;
    position: absolute;
    background: #fff;
    min-width: 180px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    border-radius: 8px;
    padding: 10px;
    z-index: 10;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown-content label {
    display: block;
    margin-bottom: 6px;
    font-size: 14px;
}

.search_bar {
    display: flex;
    gap: 8px;
}

.search_bar-font {
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
}

.search_bar-button {
    background: #6c63ff;
    border: none;
    color: #fff;
    padding: 8px 14px;
    border-radius: 6px;
    cursor: pointer;
}

/* ================================================= */
/* Sort + Paging */

.sort_bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 15px 0;
}

.sorting_left {
    display: flex;
    gap: 10px;
    align-items: center;
}

.sort-btn {
    padding: 6px 12px;
    border-radius: 6px;
    background: #eee;
    text-decoration: none;
    color: #333;
    font-size: 14px;
}

.sort-btn:hover {
    background: #ddd;
}

.pagination {
    display: flex;
    gap: 6px;
    align-items: center;
}

.pagination-btn {
    border: none;
    background: #6c63ff;
    color: #fff;
    padding: 6px 10px;
    border-radius: 6px;
    cursor: pointer;
}

.page-input {
    width: 60px;
    padding: 6px;
    border-radius: 6px;
    border: 1px solid #ccc;
    text-align: center;
}

/* ================================================= */
/* Table Wrapper */

.table-wrapper {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    padding: 10px;
    overflow-x: auto;
}

/* ================================================= */
/* Table */

.table {
    width: 100%;
    border-collapse: collapse;
    font-size: 15px;
}

.table thead {
    background: #5a189a;
    color: #fff;
}

.table th {
    padding: 12px;
    font-weight: 600;
}

.table td {
    padding: 10px;
    border-bottom: 1px solid #eee;
    text-align: center;
    vertical-align: middle;
}

.table tbody tr:hover {
    background: #f7e9ff;
}

.table img {
    width: 65px;
    height: 65px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #ddd;
}

/* ================================================= */
/* Buttons */

.button {
    background: #6c63ff;
    color: #fff;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
}

.button:hover {
    background: #554ee0;
}

/* ================================================= */
/* Categories */

.cats-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 6px;
    justify-content: center;
    flex-wrap: wrap;
}

.cats-list li {
    background: #f1c4ff;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 13px;
}

/* ================================================= */
/* Description Toggle */

.desc-short { display: inline; }
.desc-full { display: none; }

.show-more {
    background: none;
    border: none;
    color: #6c63ff;
    cursor: pointer;
    font-size: 13px;
    text-decoration: underline;
}

/* ================================================= */
/* Misc */

.total_products {
    margin: 10px 0;
    opacity: 0.85;
}
