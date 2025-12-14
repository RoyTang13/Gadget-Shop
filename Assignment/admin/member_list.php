<?php
require '../_base.php';
$_title = 'Member List';
include 'admin_head.php';
if (!isset($_SESSION['adminID'])) {
    header('Location: index.php');
    exit;
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
?>

<section>
<h1 class="text-center">Member List</h1>
<p class="text-center"><?= $totalMembers ?> member(s)</p>

    <form class="text-center" method="get" role="search" style="margin-bottom: 20px;">
        <label for = "search-input" class="visually-hidden">Search name / email: </label>
        <input id ="serch-input" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search name / email" type = "text">
        <button type="sumbit">Search</button>
        <a href="member_add.php" class="button">Add Member</a>
    </form>

    <table class="table" border="1" cellpadding="5" cellspacing="0" style="margin: 20px auto; border-collapse: collapse;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($members as $m): ?>
            <tr>
                <td><?= $m->memberID ?></td>
                <td><?= htmlspecialchars($m->name) ?></td>
                <td><?= htmlspecialchars($m->email) ?></td>
                <td><a href="member_detail.php?id=<?= $m->memberID ?>">View</a></td>
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
