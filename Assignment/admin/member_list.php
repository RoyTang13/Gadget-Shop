<?php
require '../_base.php';
$_title = 'Member List';
include 'admin_head.php';

// Check if user is logged in as admin
if (!isset($_SESSION['adminID'])) {
    header('Location: index.php');
    exit;
}

// Functionable Sorting
$order = "productID ASC";

// Handle ID sorting
if (!empty($_GET['sort_id'])) {
    $order = "memberID " . ($_GET['sort_id'] === 'desc' ? "DESC" : "ASC");
}

if (!empty($_GET['sort_name'])) {
    $order = "name " . ($_GET['sort_name'] === 'desc' ? "DESC" : "ASC");
}


//Item per page
$itemsPerPage = 10;

$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int) $_GET['page'] : 1;

//search term
$search = trim($_GET['search'] ?? '');

// Build base SQL
$sqlCount = "SELECT COUNT(*) FROM member WHERE 1";
$sqlData = "SELECT * FROM member WHERE 1";
$params = [];

if ($search !== '') {
    $sqlCount .= " AND (name LIKE :s OR email LIKE :s)";
    $sqlData .= " AND (name LIKE :s OR email LIKE :s)";
    $params[':s'] = "%$search%";
}

// Get total records
$stmCount = $_db->prepare($sqlCount);
$stmCount->execute($params);
$totalMembers = (int) $stmCount->fetchColumn();

$totalPages = ceil($totalMembers / $itemsPerPage);
$offset = ($page - 1) * $itemsPerPage;

// Append LIMIT and OFFSET
$sqlData .= " ORDER BY memberID DESC LIMIT :limit OFFSET :offset";

$stm = $_db->prepare($sqlData);
$stm->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
$stm->bindValue(':offset', $offset, PDO::PARAM_INT);


// Bind search parameter if exists
if ($search !== '') {
    $stm->bindValue(':s', "%$search%", PDO::PARAM_STR);
}
$stm->execute();
$members = $stm->fetchAll(PDO::FETCH_OBJ);
function buildQueryString(array $overrides = []): string {
    $keepKeys = ['sort_name', 'sort_id' , 'page'];
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
<h1 class="text-center">Member List</h1>
<p class="text-center"><?= $totalMembers ?> member(s)</p>

    <form class="text-center" method="get" role="search" style="margin-bottom: 20px;">
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
                 <a href = "/admin/member_list.php<?= buildQueryString(['sort_id' => $nextId, 'sort_name' => null, 'sort_price' => null, 'page' => 1]) ?>" class = "sort-btn">
                    ID <?= $nextId === 'asc' ? '⇓' : '⇑' ?>
                </a>

                <!-- Sort by Name button -->
                <a href = "/admin/member_list.php<?= buildQueryString(['sort_name' => $nextName, 'sort_price' => null, 'page' => 1]) ?>" class = "sort-btn">
                    Name <?= $nextName === 'asc' ? '⇓' : '⇑' ?>
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
                <td><a href="../member/detail.php?id=<?= $m->memberID ?>">View</a></td>
                <td><a href="member_edit.php?id=<?= $m->memberID ?>">Edit</a></td>
                <td><a href="member_delete.php?id=<?= $m->memberID ?>" onclick="return confirm('Are you sure to delete this member?');">Delete</a></td>
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

<?php include '../_foot.php'; ?>
