<?php
require '../_base.php';
$_title = 'user List';
include 'admin_head.php';
?>
<section>
<main>
    <h1>User List</h1>
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
