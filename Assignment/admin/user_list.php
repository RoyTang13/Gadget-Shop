<?php
require '../_base.php';
$_title = 'user List';
include 'admin_head.php';
// make sure only logged-in admins can access this page
if (!isset($_SESSION['adminID'])) {
    header('Location: index.php');
    exit;
}

// Functionable Sorting
$order = "userID ASC";
// Default sorting by userID ascending
if (!empty($_GET['sort_name'])) {
    $order = "userName " . ($_GET['sort_name'] === 'desc' ? "DESC" : "ASC");
}

// Build the final query
$sql = "SELECT userID, fname, lname, email, userPhoto, phoneNo FROM user";  
$sql .= " ORDER BY $order";
$stm = $_admin_db->query("
    SELECT userID, fname, lname, email, userPhoto, phoneNo FROM user 
    ORDER BY userID ASC
");
?>
<section>
<main>
    <h1 class="text-center">User List</h1>
    <p class="text-center"><?= $stm->rowCount() ?> user(s)</p>
    <table class ="table">
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
            <?php
            $stm = $_admin_db->query("SELECT userID, fname, lname, email, phoneNo FROM user ORDER BY userID ASC");
            while ($user = $stm->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($user['userID']) . "</td>";
                echo "<td>" . htmlspecialchars($user['fname']) . "</td>";
                echo "<td>" . htmlspecialchars($user['lname']) . "</td>";
                echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                echo "<td>" . htmlspecialchars($user['phoneNo']) . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</main>
</section>
</body>
</html>
