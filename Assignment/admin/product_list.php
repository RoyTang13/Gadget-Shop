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
    <p class="total_products"><?= count($arr) ?> product(s)</p>
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
  <td> <button class ="button"><a href="../admin/product_add.php" class="button">Add Product</a></button></td>
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
    /* Part 1 - Searching */
    /* Search bar style */
    .search_bar-font {
        font-style: italic;
        font-size: 12px;
        font-family: 'Courier New', Courier, monospace;
        font-weight: lighter;
        width: 200px;
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

  /* Part 2 - Filtering and Category*/

    /* Adjust dropdown position */
    .browser .search .dropdown {
        position: relative;
        z-index: 7;
    }

    /* Style the dropdown button */
    .browser .search .dropbtn {
        background-color: #be06ec;
        color: #fff;
        padding: 4px 17px;
        width: 155px;
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
        z-index: 5;
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
        flex:1;
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