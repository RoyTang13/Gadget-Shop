<?php
    // Start the session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Make sure only logged-in admins can access this page
    if (!isset($_SESSION['adminID'])) {
        header('Location: index.php');
        exit;
    }

    try {
        // Prepare and execute query safely with placeholder
        $stm = $_db->prepare('SELECT * FROM admin WHERE adminID = :id');
        $stm->execute(['id' => $_SESSION['adminID']]);
        $a = $stm->fetch(PDO::FETCH_OBJ);

        // Optional: Handle case where adminID is not found
        if (!$a) {
            // Admin not found, log out or redirect
            session_destroy();
            header('Location: index.php');
            exit;
        }
    } catch (PDOException $e) {
        // Handle database errors
        die('Database error: ' . htmlspecialchars($e->getMessage()));
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Admin Panel</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/admin.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    </head>
    <body>
        <input type="checkbox" id="check">
        <label for="check">
            <i class="fas fa-bars" id="btn"></i>
            <i class="fas fa-times" id="cancel"></i>
        </label>

        <div class="sidebar">
            <header>TechNest</header>
            <div class="admin-card">
                <?php if (!empty($a->adminPhoto)): ?>
                    <img src="/adminPhoto/<?= htmlspecialchars($a->adminPhoto) ?>" alt="Admin Photo" class="avatar">
                <?php else: ?>
                    <div class="photo-placeholder">No Photo</div>
                <?php endif; ?>
                <h3>Hello! <?= htmlspecialchars($a->fname) . ' ' . htmlspecialchars($a->lname) ?></h3>
            </div>
            <ul>
                <li><a href="../admin/admin_dashboard.php"><i class='fas fa-qrcode'></i> Dashboards</a></li>
                <li><a href="../product/list.php"><i class='fa fa-headphones'></i> Products</a></li>
                <li><a href="../admin/user_list.php"><i class='fa fa-address-book'></i> User List</a></li>
                <li><a href="../admin/order_list.php"><i class='fas fa-clipboard-list'></i> Order List</a></li>
                <li><a href="../admin/report.php"><i class='fa fa-line-chart'></i> Report</a></li>
                <li><a href="../admin/admin_profile.php"><i class='fa fa-address-card'></i> Edit Profile</a></li>
                <li><a href="../admin/admin_logout.php"><i class='fas fa-right-from-bracket'></i> Logout</a></li>
            </ul>
        </div>
        
    <style>
        .admin-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 2px;
            padding: 25px;
            color: white;
            box-shadow: 0 6px 10px rgba(0,0,0,.08);
        }
        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-top: -20px;
            margin-bottom: 7px;
            border: 2px solid #fff;
        }
        .photo-placeholder {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #555;
        }
        .admin-card h3 {
            margin: 0;
            font-size: 1.2rem;
            text-align: center;
            color: white;
        }
    </style>
<main>
</main>
</body>
</html>
