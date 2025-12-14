<?php
require '../_base.php';
$_title = 'User List';
include 'admin_head.php';

// Make sure only logged-in admins can access this page
if (!isset($_SESSION['adminID'])) {
    header('Location: index.php');
    exit;
}

// Item per page
$itemsPerPage = 10;

// Get current page
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int) $_GET['page'] : 1;

// Get search term
$search = trim($_GET['search'] ?? '');

// --- Calculate total number of users for pagination ---
$sqlCount = "SELECT COUNT(*) FROM user WHERE 1";
$params = [];

if ($search !== '') {
    $sqlCount .= " AND (fname LIKE :s OR lname LIKE :s OR email LIKE :s)";
    $params[':s'] = "%$search%";
}

$stmCount = $_db->prepare($sqlCount);
if ($search !== '') {
    $stmCount->bindValue(':s', "%$search%", PDO::PARAM_STR);
}
$stmCount->execute();
$totalUsers = (int)$stmCount->fetchColumn();

$totalPages = ceil($totalUsers / $itemsPerPage);
if ($totalPages == 0) {
    $totalPages = 1; // To avoid division by zero, show at least 1 page
}
$offset = ($page - 1) * $itemsPerPage;

// --- Fetch users for current page ---
$sqlData = "SELECT * FROM user WHERE 1";

if ($search !== '') {
    $sqlData .= " AND (fname LIKE :s OR lname LIKE :s OR email LIKE :s)";
}

$sqlData .= " ORDER BY userID ASC LIMIT :limit OFFSET :offset";

$stm = $_db->prepare($sqlData);
if ($search !== '') {
    $stm->bindValue(':s', "%$search%", PDO::PARAM_STR);
}
$stm->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
$stm->bindValue(':offset', $offset, PDO::PARAM_INT);
$stm->execute();

$users = $stm->fetchAll(PDO::FETCH_ASSOC);
?>

<section>
<main>
    <h1 class="text-center">User List</h1>
    <p class="text-center"><?= $totalUsers ?> total users</p>

    <!-- Search Form -->
    <form class="text-center" method="get" role="search">
        <label for="search-input" class="visually-hidden">Search first name / last name / email:</label>
        <input id="search-input" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search first name / last name / email" type="text">
        <button type="submit">Search</button>
    </form>

    <!-- User Table -->
    <table class="table" border="1" cellpadding="5" cellspacing="0" style="margin: 20px auto; border-collapse: collapse;">
        <thead>
            <tr>
                <th>User ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone Number</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($users) > 0): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['userID']) ?></td>
                        <td><?= htmlspecialchars($user['fname']) ?></td>
                        <td><?= htmlspecialchars($user['lname']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['phoneNo']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center;">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

   <!-- Pagination Links -->
<?php if ($totalPages > 1): ?>
    <div style="text-align: center;">
        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <?php
                // Build query string with search and page
                $queryArray = ['search' => $search, 'page' => $p];
                $queryString = http_build_query($queryArray);
            ?>
            <a href="?<?= $queryString ?>" style="margin: 0 5px; <?= ($p == $page) ? 'font-weight: bold;' : '' ?>"><?= $p ?></a>
        <?php endfor; ?>
    </div>
<?php endif; ?>
</main>
</section>

<style>
body {
    margin-bottom: 100px; /* to prevent overlap with footer */
}
</style>

<?php include '../_foot.php'; ?>