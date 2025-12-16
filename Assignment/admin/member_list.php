<?php
require '../_base.php';
$_title = 'Member List';
include 'admin_head.php';

// Check if user is logged in as admin
if (!isset($_SESSION['adminID'])) {
    header('Location: index.php');
    exit;
}

// Initialize default order
$order = "memberID ASC";

// Handle sorting from GET params
$currentId = $_GET['sort_id'] ?? null;
$currentName = $_GET['sort_name'] ?? null;

// Determine next toggle for ID
$nextId = ($currentId === 'asc') ? 'desc' : 'asc';
// Determine next toggle for Name
$nextName = ($currentName === 'asc') ? 'desc' : 'asc';

// Set order based on current sorting
if ($currentId !== null) {
    $order = "memberID " . ($currentId === 'asc' ? "ASC" : "DESC");
} elseif ($currentName !== null) {
    $order = "name " . ($currentName === 'asc' ? "ASC" : "DESC");
}

// Pagination
$itemsPerPage = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int) $_GET['page'] : 1;

// Search
$search = trim($_GET['search'] ?? '');

// Build count query
$sqlCount = "SELECT COUNT(*) FROM member WHERE 1";
$params = [];
if ($search !== '') {
    $sqlCount .= " AND (name LIKE :s OR email LIKE :s)";
    $params[':s'] = "%$search%";
}

// Get total records
$stmCount = $_db->prepare($sqlCount);
$stmCount->execute($params);
$totalMembers = (int) $stmCount->fetchColumn();
$totalPages = ceil($totalMembers / $itemsPerPage);
$offset = ($page - 1) * $itemsPerPage;

// Build main query with dynamic order
$sqlData = "SELECT * FROM member WHERE 1";

if ($search !== '') {
    $sqlData .= " AND (name LIKE :s OR email LIKE :s)";
}

$sqlData .= " ORDER BY $order LIMIT :limit OFFSET :offset";

$stm = $_db->prepare($sqlData);
if ($search !== '') {
    $stm->bindValue(':s', "%$search%", PDO::PARAM_STR);
}
$stm->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
$stm->bindValue(':offset', $offset, PDO::PARAM_INT);
$stm->execute();

$members = $stm->fetchAll(PDO::FETCH_OBJ);

// Build query string helper
function buildQueryString(array $overrides = []): string {
    $keepKeys = ['sort_name', 'sort_id', 'page', 'search'];
    $parts = [];
    foreach ($keepKeys as $key) {
        if (array_key_exists($key, $overrides)) {
            $val = $overrides[$key];
            if ($val === null) continue;
            if (is_array($val)) {
                foreach ($val as $v) {
                    $parts[] = urlencode($key) . '=' . urlencode((string)$v);
                }
            } else {
                $parts[] = urlencode($key) . '=' . urlencode((string)$val);
            }
            continue;
        }
        if (!isset($_GET[$key])) continue;
        $val = $_GET[$key];
        if (is_array($val)) {
            foreach ($val as $v) {
                $parts[] = urlencode($key) . '=' . urlencode((string)$v);
            }
        } else {
            $parts[] = urlencode($key) . '=' . urlencode((string)$val);
        }
    }
    return $parts ? '?' . implode('&', $parts) : '';
}
?>


<section>
<h1 class="text-center">Member List</h1>
<p class="text-center"><?= $totalMembers ?> member(s)</p>

    <form class= "search-box" class="text-center" method="get" role="search" style="margin-bottom: 20px;">
        <label for = "search-input" class="visually-hidden">Search name / email: </label>
        <input id ="serch-input" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search name / email" type = "text">
        <button type="sumbit">Search</button>
        <a href="member_add.php" class="button">Add Member</a>
        <!-- Sort Bar + Paging -->
        <div class = "sort_bar">
            <div class = "sorting_left">
               <?php
                 // Toggle for ID sort
                  $currentId = $_GET['sort_id'] ?? null;
                 $nextId = ($currentId === 'asc') ? 'desc' : 'asc';

                    // Toggle for name sort
                    $currentName = $_GET['sort_name'] ?? null;
                    $nextName = ($currentName === 'asc') ? 'desc' : 'asc';
                ?>
                <h5>Sorting By: </h5>

                <!-- Sort by ID button -->
                 <a href="/admin/member_list.php<?= buildQueryString(['sort_id' => $nextId, 'sort_name' => null, 'page' => 1, 'search' => $search]) ?>" class="sort-btn">
                    ID <?= $currentId === 'asc' ? '⇓' : '⇑' ?>
                </a>

                <!-- Sort by Name button -->
                 <a href="/admin/member_list.php<?= buildQueryString(['sort_name' => $nextName, 'sort_id' => null, 'page' => 1, 'search' => $search]) ?>" class="sort-btn">
                    Name <?= $currentName === 'asc' ? '⇓' : '⇑' ?>
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

    <table class="table" border="1" cellpadding="5" cellspacing="0" style="margin: 20px auto; border-collapse: collapse;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>View Details</th>
            <th colspan ="2">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($members as $m): ?>
            <tr>
                <td><?= $m->memberID ?></td>
                <td><?= htmlspecialchars($m->name) ?></td>
                <td><?= htmlspecialchars($m->email) ?></td>
                <td><a href="../member/detail.php?id=<?= $m->memberID ?>"class = "button">View</a></td>
                <td><a href="member_edit.php?id=<?= $m->memberID ?>"class = "button">Edit</a></td>
                <td><a href="member_delete.php?id=<?= $m->memberID ?>" class = "button" onclick="return confirm('Are you sure to delete this member?');">Delete</a></td>
            </tr>
        <?php endforeach ?>
    </tbody>
    </table>

<!-- Pagination Links -->
<?php if ($totalPages > 1): ?>
    <div style="text-align: center;">
        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <?php
                $queryString = http_build_query(['search' => $search, 'page' => $p]);
            ?>
            <a href="?<?= $queryString ?>" style="margin: 0 5px; <?= ($p == $page) ? 'font-weight: bold;' : '' ?>"><?= $p ?></a>
        <?php endfor; ?>
    </div>
<?php endif; ?>

</section>

<style> 
    /* ===== Search ===== */
.search-box {
    display: flex;
    gap: 10px;
    position: center;
    margin-bottom: 15px;
}

.search-box input {
    flex: 1;
    padding: 9px 12px;
    border-radius: 8px;
    border: 1px solid #c7d2fe;
}

.search-box button {
    padding: 9px 16px;
    border: none;
    border-radius: 8px;
    background: #be06ec;
    color: #fff;
    cursor: pointer;
}

.search-box button:hover {
    background: #d17de6;
}
    .table {
    width: 1100px;
    border-collapse: collapse;
    font-size: 20px;
    text-align: center;
    vertical-align: middle;
}
  .button {
    background-color: #be06ec; 
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
    background-color: #d17de6;
  }
</style>

<?php include '../_foot.php'; ?>
