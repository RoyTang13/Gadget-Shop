<?php
require '../_base.php';


$_title = 'Admin Dashboard';
include 'admin_head.php';
?>

<body>

<section class="dashboard">
    <div class="dashboard-wrapper">
        <div class="dashboard-container">
        <div class="card">
                <h3>Total Users</h3>
                <p>
                <?php
                    global $_db;
                    $stm = $_db->query("SELECT COUNT(*) FROM user");
                    $count = $stm->fetchColumn();
                    echo $count ?: 0;
                ?></p>
            </div>
            <div class="card">
                <h3>Total Products</h3>
                <p>
                <?php
                    global $_db;
                    $stm = $_db->query("SELECT COUNT(*) FROM product");
                    $count = $stm->fetchColumn();
                    echo $count ?: 0;
                ?>
                </p>
            </div>
            <div class="card">
                <h3>Revenue</h3>
                <p>$3,200</p>
            </div>
            <div class="card">
                <h3>New Orders</h3>
                <p>18</p>
            </div>
        </div>

    </div>
</section>


</body>
</html>
