
<?php
require '../_base.php';

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

$_title = 'Product | TechNest';

include '../_head.php';
?>

<div class = "browser">
<form method = "get" action = "/product/page.php">
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

        <!-- Price Range Filter -->
        <div class = "dropdown">
            <button class = "dropbtn">Price Range</button>
            <div class = "dropdown-content">

                <!-- Fixed Price Range -->
                <div class = "dropdown-row">
                    <span>Quick Select</span>
                    <div class = "dropdown-subcontent">
                        <label><input type = "radio" name = "fixedPrice" value = "0.01-300.00">RM 0.01 - RM 300.00</label>
                        <label><input type = "radio" name = "fixedPrice" value = "300.01-600.00">RM 300.01 - RM 600.00</label>
                        <label><input type = "radio" name = "fixedPrice" value = "600.01-900.00">RM 600.01 - RM 900.00</label>
                        <label><input type = "radio" name = "fixedPrice" value = "900.01-1200.00">RM 900.01 - RM 1200.00</label>
                    </div>
                </div>

                <!-- Custom Price Input -->
                <div class = "dropdown-row"> 
                    <span>Custom Range</span>
                    <div class = "dropdown-subcontent" style = "padding: 10px 15px;">
                        <label>Minimum (RM): <input type = "number" 
                                                    id = "customMin" 
                                                    name = "customMin"
                                                    min = "0.01" 
                                                    step = "0.01" 
                                                    placeholder = "Start 0.01" 
                                                    style = "width: 100px;">
                        </label>
                        <label style = "margin-top: 8px;">Maximum (RM): <input type = "number" 
                                                                               id = "customMax" 
                                                                               name = "customMax"
                                                                               min = "0.01" 
                                                                               step = "0.01" 
                                                                               placeholder = "Start 0.02"
                                                                               style = "width: 100px;">
                        </label>
                        <button id = "applyCustomPrice" style = "margin-top: 10px;">Apply</button>
                    </div>
                </div>
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

<!-- Script for custom price function -->
<script>
    // When clicking fixed range, clear custom inputs
    document.querySelectorAll("input[name = 'fixedPrice']").forEach(radio => {
        radio.addEventListener("change", () => {
            document.getElementById("customMin").value = "";
            document.getElementById("customMax").value = "";
        });
    });

    // When applying custom range, clear fixed selection
    document.getElementById("applyCustomPrice").addEventListener("click", () => {
        let min = document.getElementById("customMin").value;
        let max = document.getElementById("customMax").value;

        if (min === "" || max === "") {
            // Warning for applied failure: Empty Input
            alert("Please enter both minimum and maximum values.");
        }
        else if (parseFloat(min) >= parseFloat(max)) {
            // Warning for applied failure: Minimum > Maximum
            alert("Minimum price cannot be greater or equal to maximum price.");
        }
        else if (min <= 0.00 ||min >= 1000.00){
            // Warning for applied failure: Minimum input wrongly
            alert("Please input minimum price correctly.");
        }
        else if (max <= 0.01 ||max > 1000.00){
            // Warning for applied failure: Maximum input wrongly
            alert("Please input maximum price correctly.");
        }
        else{
            // Clear fixed radios
            document.querySelectorAll("input[name = 'fixedPrice']").forEach(r => r.checked = false);

            // Notice for applied success
            alert("Custom price applied: RM " + min + " - RM " + max);
            return;
        }
    });
</script>

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
        <a href = "/product/page.php<?= buildQueryString(['sort_name' => $nextName, 'sort_price' => null, 'page' => 1]) ?>" class = "sort-btn">
            Name <?= $nextName === 'asc' ? '⇓' : '⇑' ?>
        </a>
        
        <!-- Sort by Price button -->
        <a href = "/product/page.php<?= buildQueryString(['sort_price' => $nextPrice, 'sort_name' => null, 'page' => 1]) ?>" class = "sort-btn">
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

<!-- Product Photo with Name and Price-->
<div class = "gallery">
<?php foreach ($arr as $p): ?>
    <div class = "gallery-item">
        <div class = "content">
            <img src = "/photos/<?= $p->productPhoto ?>
            " alt = "<?= htmlspecialchars($p->productName) ?>">
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

                <button class = "wishlist">Wishlist</button>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

<style>
    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url(/images/purple\ wave\ background.jpg);
        background-size: cover;
        background-attachment: fixed;
        opacity: 0.6;
        z-index: -1;
        pointer-events: none;
    }
</style>


<script>

</script>
