
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
        margin-left: 30px;
        margin-right: 60px;
        display: flex;
        align-items: center;
        width: 300px;
    }

    .search-box input {
        padding: 8px 12px;
        border: 1px solid #000;
        border-radius: 6px;
        outline: none;
        font-family: 'Courier New', Courier, monospace;
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
    padding:0 20px;
    flex: 1;
  }

  /* Filters & Sorting */
  .filters {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
    flex-wrap: wrap;

  }

  .filters .filter-group {
    display: flex;
    align-items: center;
    gap: 10px;
  }

/* Ensure the product grid uses a flexible layout */
.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 30px; 
  padding: 10px;

}

.product-card {
  display: flex;
  flex-direction: column;
  height: 400px; /* fixed height for consistency */
  border: 1px solid #ddd;
  border-radius: 8px;
  transition: box-shadow 0.3s, border-color 0.3s;
  overflow: hidden; 
  cursor: pointer;
}

.product-card img {
  width: 100%;
  height: auto;
  object-fit: contain;
  max-height: 300px;
}

.product-card:hover {
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4); /* subtle shadow on hover */
  border-color: #ff6f61; /* change border color on hover */
}


/* Make the card-body flexible so the button stays at the bottom */
.card-body {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: space-between; /* pushes content to top and bottom */
  padding: 10px; /* optional for padding inside the card */
}

/* Adjust product name and price spacing */
.product-name {
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 8px;
  line-height: 1.2; /* Better line spacing */
  height: 40px; 
  line-height: 20px; 
}

.price {
  color: #ff6f61;
  font-size: 14px;
  font-weight: bold;
  margin-bottom: 12px;
}

/* Actions button spacing and styling */
.actions {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: auto; /* push it to the bottom of flex container */
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
  background-color: #ff6f61;
  color: #fff;
}

.btn-add:hover {
  background-color: #e55b50;
}

.layout-container {
  display: flex;
  flex-direction: row;
  gap: 20px; /* space between sidebar and products */
}

.filter-sidebar {
  flex: 0 0 250px; /* fixed width sidebar */
  max-width: 250px;
  padding: 20px;
  background-color: #f5f5f5; /* optional background */
  border-radius: 8px; /* optional rounded corners */
}

</style>
<script>

</script>
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

        <!-- Search Bar -->
        <form id = "filterForm" method = "get" action = "/product/product.php">
            <div class = "search-box">
                <input type = "text" name = "search" placeholder = "Search products..." value = "<?= ($_GET['search'] ?? '') ?>" />
                <button onclick="document.getElementById('filterForm').submit()">Search</button>
            </div>
        </form>

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
                    <form method = "post" action = "/product/add_to_cart.php">
                    <input type = "hidden" name = "productID" value ="<?= htmlspecialchars($p->productID) ?>">
                    <input type = "hidden" name = "quantity" value = "1" id = "addQty">
                    <button type = "submit" name = "add_to_cart">Add to Cart</button>
                </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
</div>

<!-- Main Content -->
 <div class="layout-container">
<div id="filterSidebar" class="filter-sidebar">
  <h3>Filters</h3>
  <form id="filterForm" method="get" action="/product/page.php">
    <!-- Type filters -->
    <div>
      <h4>🔌Connection</h4>
      <label><input type="checkbox" name="connectivity[]" value = "wired"  <?= in_array('Type1', $_GET['type'] ?? []) ? 'checked' : '' ?>> Wired</label><br>
      <label><input type="checkbox" name="connectivity[]" value = "wireless"  <?= in_array('Type2', $_GET['type'] ?? []) ? 'checked' : '' ?>> Wireless</label><br>
    </div>
      <!-- Fit Type -->
      <div>
      <h4>🎧Fit Type</h4>
      <label><input type = "checkbox" name = "design[]" value = "in-ear"<?= in_array('in-ear', $_GET['design'] ?? []) ? 'checked' : '' ?>> In-ear</label><br>
      <label><input type="checkbox" name="design[]" value="over-ear" <?= in_array('over-ear', $_GET['design'] ?? []) ? 'checked' : '' ?>> Over-ear</label><br>
    </div>
    <!-- Acoustic -->
    <div>
      <h4>🎶Acoustic</h4>
      <label><input type="checkbox" name="acoustic[]" value="noise-canceled" <?= in_array('noise-canceled', $_GET['acoustic'] ?? []) ? 'checked' : '' ?>> Noise-canceled</label><br>
      <label><input type="checkbox" name="acoustic[]" value="balanced" <?= in_array('balanced', $_GET['acoustic'] ?? []) ? 'checked' : '' ?>> Balanced</label><br>
      <label><input type="checkbox" name="acoustic[]" value="clear vocals" <?= in_array('clear vocals', $_GET['acoustic'] ?? []) ? 'checked' : '' ?>> Clear Vocals</label><br>
    </div>

    <!-- Price Range -->
    <div style="margin-top:15px;">
      <h4>Price</h4>
      <label><input type = "radio" name = "fixedPrice" value = "0.01-300.00">RM 0.01 - RM 300.00</label><br>
      <label><input type = "radio" name = "fixedPrice" value = "300.01-600.00">RM 300.01 - RM 600.00</label><br>
      <label><input type = "radio" name = "fixedPrice" value = "600.01-900.00">RM 600.01 - RM 900.00</label><br>
      <label><input type = "radio" name = "fixedPrice" value = "900.01-1200.00">RM 900.01 - RM 1200.00</label><br>
      <input type="number" name="priceMin" placeholder="Min" value="<?= $_GET['priceMin'] ?? '' ?>" style="width:80px;">
      <input type="number" name="priceMax" placeholder="Max" value="<?= $_GET['priceMax'] ?? '' ?>" style="width:80px;">
    </div>
    <div style="margin-top:15px;">
      <button type="submit">Apply Filters</button>
    </div>
  </form>
</div>

<div class="product-container">
  <!-- Filters & Sorting -->
  <div class="filters">
    <div class="filter-group">
      <label for="category">Category:</label>
      <select id="category">
        <option>All</option>
        <option>Clothing</option>
        <option>Electronics</option>
        <option>Home</option>
      </select>
    </div>
    <div class="filter-group">
      <label for="sort">Sort by:</label>
      <select id="sort">
        <option>Price: Low to High</option>
        <option>Price: High to Low</option>
        <option>Newest</option>
      </select>
    </div>
  </div>

  <!-- Product Grid -->
<div class="product-grid">
<?php foreach ($arr as $p): ?>
  <div class="product-card">
      <img src="/photos/<?= $p->productPhoto ?>" alt="<?= htmlspecialchars($p->productName) ?>">
      <div class="card-body">
          <div class="product-name"><?= htmlspecialchars($p->productName) ?></div>
          <div class="price">RM <?= number_format($p->productPrice, 2) ?></div>
          <div class="actions">
          <button class="btn btn-add" data-product-id="<?= $p->productID ?>">Add to Cart</button>
          </div>
      </div>
  </div>
<?php endforeach; ?>
</div>
</div>

</main>
</body>
</html>