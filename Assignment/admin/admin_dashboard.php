<?php
require '../_base.php';


$_title = 'Admin Dashboard';
include 'admin_head.php';

// make sure only logged-in admins can access this page
if (!isset($_SESSION['adminID'])) {
    header('Location: index.php');
    exit;
}
/* ===== Order Summary ===== */

// Overall summary
$stm = $_db->query("
    SELECT 
        COUNT(*) AS totalOrders,
        COALESCE(SUM(totalAmount), 0) AS totalRevenue
    FROM orders
");
$orderSummary = $stm->fetch(PDO::FETCH_OBJ);

// Get total sales per month (for last 12 months)
$stm = $_db->query("
    SELECT DATE(orderDate) AS orderDay,
           SUM(totalAmount) AS dailySales
    FROM orders
    WHERE status = 'Complete'
      AND orderDate >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    GROUP BY orderDay
    ORDER BY orderDay ASC
");

$salesData = $stm->fetchAll(PDO::FETCH_ASSOC);

$dates = [];
$cumulativeSales = [];

$runningTotal = 0;

foreach ($salesData as $row) {
    $runningTotal += (float)$row['dailySales'];
    $dates[] = $row['orderDay'];
    $cumulativeSales[] = $runningTotal;
}

$datesJson = json_encode($dates);
$salesJson = json_encode($cumulativeSales);



?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.0"></script>
<body>

<section class="dashboard">
    <div class="dashboard-wrapper">
        <div class="dashboard-container">
        <div class="card">
            <a href="../admin/user_list.php">
                <h3>Total Users</h3>
                <p>
                <?php
                    global $_db;
                    $stm = $_db->query("SELECT COUNT(*) FROM user");
                    $count = $stm->fetchColumn();
                    echo $count ?: 0;
                ?></p>
            </a>
            </div>
            <div class="card">
                <a href="../product/list.php">
                <h3>Total Products</h3>
                <p>
                <?php
                    global $_db;
                    $stm = $_db->query("SELECT COUNT(*) FROM product");
                    $count = $stm->fetchColumn();
                    echo $count ?: 0;
                ?>
                </p>
                </a>
            </div>
            <div class="card">
                <a href="../admin/order_list.php">
                <h3>Total order</h3>
                <p>
                    <?php
                    global $_db;
                    $stm = $_db->query("SELECT COUNT(*) FROM orders");
                    $count = $stm->fetchColumn();
                    echo $count ?: 0;
                ?>
                </p>
            </div>
            <!-- ===== Order Summary ===== -->
            <div class="card">
                <a href="../admin/report.php">
                    <h3>Total Revenue</h3>
                    <p>RM <?= number_format($orderSummary->totalRevenue, 2) ?></p>
                </a>
            </div>

        </div>
        <div class="chart-wrapper">
                <canvas id="myChart"></canvas>
            </div>
    </div>


        <script>
                const xValues = <?= $datesJson ?>;
                const yValues = <?= $salesJson ?>;

                const ctx = document.getElementById('myChart').getContext('2d');

                new Chart(ctx, {
                type: "line",
                data: {
                    labels: xValues,
                    datasets: [{
                    label: 'Cumulative Sales',
                    data: yValues,
                    borderColor: "rgba(99,102,241,1)",
                    backgroundColor: "rgba(99,102,241,0.15)",
                    fill: true,
                    tension: 0.3
                    }]
                },
                options: {
                    plugins: {
                    title: {
                        display: true,
                        text: "Cumulative Daily Sales (Last 30 Days)"
                    }
                    },
                    scales: {
                    x: {
                        title: {
                        display: true,
                        text: 'Date'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                        display: true,
                        text: 'Total Revenue (RM)'
                        }
                    }
                    }
                }
                });

</script>

</section>


</body>
</html>
<style>
    .dashboard {
    padding: 20px;
    margin-left: 250px; /* Adjust this to match your sidebar width */
}

.dashboard-wrapper {
    max-width: 1200px;
    margin: 0 auto;
}

/* Cards Container - Flexbox for the row of 4 cards */
.dashboard-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-between;
    margin-bottom: 5px;
}

/* Individual Summary Cards */
.card {
    background: var(--card-bg);
    flex: 1; /* Makes cards equal width */
    min-width: 200px;
    padding: 25px;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

.card a {
    text-decoration: none;
    color: inherit;
    display: block;
}

.card h3 {
    margin: 0;
    font-size: 1.1rem;
    color: var(--text-muted);
    font-weight: 500;
}

.card p {
    margin: 10px 0 0;
    font-size: 1.6rem;
    font-weight: 700;
    color: #0b252e;
    white-space: nowrap; 
}

/* Chart Container */
.chart-wrapper {
    background: #fff;
    padding: 20px;
    border-radius: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    max-width: 100%;
    margin: 0 auto;
    display: block;
}

/* Make canvas fill container */
.chart-wrapper canvas {
    width: 100%;
    height: 400px; /* adjust as needed */
    display: block;
}
</style>

