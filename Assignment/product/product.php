
<?php
require '../_base.php';
$_title = 'Product | TechNest';

include '../_head.php';

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

    // 4a. Fixed Price
if (!empty($_GET['fixedPrice'])) {
    list($min, $max) = explode('-', $_GET['fixedPrice']);
    $where[] = "productPrice BETWEEN :min AND :max";
    $params[':min'] = $min;
    $params[':max'] = $max;
}

    // 4b. Custom Price
if (!empty($_GET['customMin']) && !empty($_GET['customMax'])) {
    $where[] = "productPrice BETWEEN :cmin AND :cmax";
    $params[':cmin'] = $_GET['customMin'];
    $params[':cmax'] = $_GET['customMax'];
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
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 12;  // number of products per page
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM product";

if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY $order LIMIT $limit OFFSET $offset";

$stmt = $_db->prepare($sql);
$stmt->execute($params);
$arr = $stmt->fetchAll(PDO::FETCH_OBJ);

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

<style>
    /* Search Bar */
    .search-box {
        display: flex;
        align-items: center;
    }

    .search-box input {
        padding: 8px 12px;
        border: none;
        border-radius: 4px 0 0 4px;
        outline: none;
        width: 200px;
    }

    .search-box button {
        padding: 8px 14px;
        border: none;
        background-color: #ff6f61;
        color: #fff;
        border-radius: 0 4px 4px 0;
        cursor: pointer;
        z-index: 100;
    }

    /* Hero Banner */
    .banner {
        background-image: url("/images/banner3.png");
        background-size: cover;
        background-position: center;
        height: 500px;
    }

    /* Main Content */
    .product-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
        flex: 1;
    }

    /* Filters inline */
    .filters-inline {
        display: flex;
        justify-content: space-between;  /* Sorting on left, paging on right */
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;  /* Wrap on small screens if needed */
    }

    /* Sorting */
    .sorting {
        width: 250px;
        margin-left: 10px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sorting select {
        flex: 1;  /* Make select take remaining space */
        padding: 6px 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: #fff;
        font-size: 14px;
        cursor: pointer;
    }

    .sorting select:focus {
        outline: none;
        border-color: #ff6f61;
        box-shadow: 0 0 4px rgba(255, 111, 97, 0.3);
    }

    /* Ensure the product grid uses a flexible layout */
    .product-grid {
        border: 3px solid #ffa69eff;
        display: grid;
        grid-template-columns: repeat(4, minmax(220px, 1fr));
        gap: 30px; 
        padding: 10px;
        justify-content: center;
    }

    .product-card {
        display: flex;
        flex-direction: column;
        height: 500px; /* fixed height for consistency */
        border: 1px solid #ff9990;
        border-radius: 8px;
        transition: box-shadow 0.3s, border-color 0.3s;
        overflow: hidden; 
        cursor: pointer;
    }

    .product-card img {
        padding: 2.5%;
        width: 95%;
        height: auto;
        object-fit: contain;
        max-height: 300px;
    }

    .product-card:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4); 
        border-color: #ff6f61; 
    }

    /* Make the card-body flexible so the button stays at the bottom */
    .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 10px; 
    }

    /* Adjust product tag styling */
    .tag {
        margin-top: 10px;
        display: flex;
        justify-content: space-between;
        height: 45px;
        overflow: hidden;
        gap: 5px;
    }

    .tag1, .tag2, .tag3 {
        display: inline-block;
        padding: 5px 10px 5px 10px;
        border: #000 solid 0.6px;
        border-radius: 5%;
        font-family: 'Courier New', Courier, monospace;
        font-size: 12px;
        text-align: left;
    }

    /* Adjust product name styling */
    .name {
        margin-top: 10px;
        font-weight: 600;
        text-align: left;
        height: 60px;
        overflow: hidden;
    }

    .name a {
        text-decoration: none;
        color: #000000;
    }

    .name a:hover {
        text-decoration: underline;
        cursor: pointer;
    }

    /* Adjust product price styling */
    .price {
        color: #ff6f61;
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 12px;
    }

    /* Actions button spacing and styling */
    .actions {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: auto;
    }

    .btn {
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.3s;
    }

    .btn-add {
        background: linear-gradient(to bottom, #ff9990, #ff6f61);
        color: #fff;
    }

    .btn-add:hover {
        background: linear-gradient(to bottom, #e7766e, #e55b50);
    }

    .layout-container {
        display: flex;
        flex-direction: row;
        gap: 20px;
    }

    .filter-sidebar {
        flex: 0 0 250px;
        max-width: 250px;
        padding: 20px;
        background-color: #f5f5f5;
        border: 2.6px solid #ff6f61;
        border-radius: 8px;
    }

    .filter-sidebar h3 {
        text-align: center;
        margin-top: 10px;
    }

    .filter-sidebar h4 {
        text-decoration: underline;
    }

    .filter-sidebar h3, .filter-sidebar h4, 
    .filter-sidebar input, .filter-sidebar label, .filter-sidebar button {
        font-family: 'Courier New', Courier, monospace;
    }

    /* Paging button */
    .pagination-btn {
        width: 53px;
        height: 28px;
        padding: 0px 0px 3px 0px;
        border: 1.6px solid #000;
        background: #fff;
        color: #560065;
        cursor: pointer;
        font-size: 20.8px;
        font-weight: bold;
    }

    /* After cursor hover the paging button */
    .pagination-btn:hover {
        border-color: #424242;
    }

    /* Paging input */
    .page-input {
        width: 158px;
        height: 26px;
        padding: 2px 0px 0px 0px;
        border: 1.6px solid #000;
        background: #fff;
        color: #560065;
        cursor: text;
        text-align: center;
        font-family: 'Courier New', Courier, monospace;
    }

    /* After hover the page input */
    .page-input:focus {
        outline: none;
        border-color: #424242;
        box-shadow: 0 0 4px rgba(199, 100, 224, 0.3);
    }

    /* Hide the spin-number-scroller from page input */
    .page-input::-webkit-inner-spin-button,
    .page-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>

<body>
<main >
<!-- Hero Banner -->
<div class = "banner"></div>

<!-- Main Content -->
<div class = "layout-container">
    <div id = "filterSidebar" class = "filter-sidebar">
        <h3>Filters</h3>
        <form id = "filterForm" method = "get" action = "/product/product.php">

        <!-- Type filters -->
        <div>
            <h4>🔌Connection</h4>
            <label><input type = "checkbox" name = "connectivity[]" value = "wired"  <?= in_array('Type1', $_GET['type'] ?? []) ? 'checked' : '' ?>> Wired</label><br><br>
            <label><input type = "checkbox" name = "connectivity[]" value = "wireless"  <?= in_array('Type2', $_GET['type'] ?? []) ? 'checked' : '' ?>> Wireless</label><br><br>
        </div>

        <!-- Fit Type -->
        <div>
        <h4>🎧Fit Type</h4>
            <label><input type = "checkbox" name = "design[]" value = "in-ear"<?= in_array('in-ear', $_GET['design'] ?? []) ? 'checked' : '' ?>> In-ear</label><br><br>
            <label><input type = "checkbox" name = "design[]" value = "over-ear" <?= in_array('over-ear', $_GET['design'] ?? []) ? 'checked' : '' ?>> Over-ear</label><br><br>
        </div>

        <!-- Acoustic -->
        <div>
            <h4>🎶Acoustic</h4>
            <label><input type = "checkbox" name = "acoustic[]" value = "noise-canceled" <?= in_array('noise-canceled', $_GET['acoustic'] ?? []) ? 'checked' : '' ?>> Noise-canceled</label><br><br>
            <label><input type = "checkbox" name = "acoustic[]" value = "balanced" <?= in_array('balanced', $_GET['acoustic'] ?? []) ? 'checked' : '' ?>> Balanced</label><br><br>
            <label><input type = "checkbox" name = "acoustic[]" value = "clear vocals" <?= in_array('clear vocals', $_GET['acoustic'] ?? []) ? 'checked' : '' ?>> Clear Vocals</label><br><br>
        </div>

        <!-- Price Range -->
        <div style = "margin-top:15px;">
            <h4>Price</h4>
            <label><input type = "radio" name = "fixedPrice" value = "0.01-300.00">RM 0.01 - RM 300.00</label><br><br>
            <label><input type = "radio" name = "fixedPrice" value = "300.01-600.00">RM 300.01 - RM 600.00</label><br><br>
            <label><input type = "radio" name = "fixedPrice" value = "600.01-900.00">RM 600.01 - RM 900.00</label><br><br>
            <label><input type = "radio" name = "fixedPrice" value = "900.01-1200.00">RM 900.01 - RM 1200.00</label><br><br>
        <input type = "number" name = "priceMin" min = 0.01 max = 1199.99 step = 0.01 placeholder = "Min" value = "<?= $_GET['priceMin'] ?? '' ?>" style = "width: 100px;">
        <input type = "number" name = "priceMax" min = 0.02 max = 1200.00 step = 0.02 placeholder = "Max" value = "<?= $_GET['priceMax'] ?? '' ?>" style = "width: 100px;">
        </div>

        <div style = "margin-top: 15px;">
            <button type = "submit">Apply Filters</button>
        </div>
    </form>
    </div>

    <div class = "product-container">
    <div class = "filters-inline" style = "display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <!-- Sorting -->
        <div class = "sorting">
            <label for = "sort">Sort by: </label>
            <select id ="sort">
                <option value = "price-asc" <?= (isset($_GET['sort_price']) && $_GET['sort_price'] === 'asc') ? 'selected' : '' ?>>Price ↑</option>
                <option value = "price-desc" <?= (isset($_GET['sort_price']) && $_GET['sort_price'] === 'desc') ? 'selected' : '' ?>>Price ↓</option>
                <option value = "name-asc" <?= (isset($_GET['sort_name']) && $_GET['sort_name'] === 'asc') ? 'selected' : '' ?>>Name ↑</option>
                <option value = "name-desc" <?= (isset($_GET['sort_name']) && $_GET['sort_name'] === 'desc') ? 'selected' : '' ?>>Name ↓</option>
            </select>
        </div>

        <!-- Paging -->
        <div class = "paging">
            <div class = "pagination">
                <button class = "pagination-btn" id = "prevBtn" type = "button">‹</button>
                <input type = "number" id = "pageInput" class = "page-input" min = "1" value = "<?= $page ?>" placeholder="Page">
                <button class = "pagination-btn" id = "nextBtn" type = "button">›</button>
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div class = "product-grid">
        <?php foreach ($arr as $p): ?>
        <div class = "product-card">
            <img src = "/photos/<?= $p->productPhoto ?>" alt="<?= htmlspecialchars($p->productName) ?>">
            <div class = "card-body">
                <div class = "tag">
                    <div class = "tag1">
                        <span><?= htmlspecialchars($p->productCat1) ?></span>
                    </div>

                    <div class = "tag2">
                        <span><?= htmlspecialchars($p->productCat2) ?></span>
                    </div>

                    <div class = "tag3">
                        <span><?= htmlspecialchars($p->productCat3) ?></span>
                    </div>
                </div>

                <div class = "name">
                    <a href = "/product/details.php?name=<?= urlencode($p->productName) ?>">
                        <?= htmlspecialchars($p->productName) ?>
                    </a>
                </div>

                <div class = "price_wishlist">
                    <div class = "price">
                    RM <?= number_format($p->productPrice, 2) ?>
                    </div>

                    <button class = "btn btn-add">Add to Cart</button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
</div>

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

</main>
</body>
</html>
