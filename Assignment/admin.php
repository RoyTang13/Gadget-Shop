<?php
require 'admin_base.php';
$_title = 'Admin Login';
include 'admin_head.php';

// $_err = [];

// $email = '';
// $password = '';

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $email = $_POST['email'] ?? '';
//     $password = $_POST['password'] ?? '';

//     // Validate input
//     if ($email == '') {
//         $_err['email'] = 'Email is required';
//     } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//         $_err['email'] = 'Invalid email format';
//     }

//     if ($password == '') {
//         $_err['password'] = 'Password is required';
//     }

//     // If no errors → check admin table
//     if (empty($_err)) {
//         $stm = $_db->prepare("SELECT * FROM user WHERE email = ? AND role = 'admin' LIMIT 1");
//         $stm->execute([$email]);
//         $admin = $stm->fetch(PDO::FETCH_ASSOC);

//         if (!$admin) {
//             $_err['email'] = 'Admin account not found';
//         } else if (!password_verify($password, $admin['password'])) {
//             $_err['password'] = 'Incorrect password';
//         } else {
//             // Save admin session
//             $_SESSION['adminID'] = $admin['userID'];
//             $_SESSION['admin_email'] = $admin['email'];
//             $_SESSION['role'] = 'admin';

//             // Redirect to admin dashboard
//             redirect('/admin');
//             exit;
//         }
//     }
// }
// ?>

<!-- <div class="login-wrapper">
    <div class="login-left">
        <div class="login-form-box">
            <h2 class="login-title">Admin Login</h2>

            <form action="" method="post">

                <label>Email</label>
                <?= html_text('email','maxlength="100" ') ?>
                <?= err('email') ?>

                <label>Password</label>
                <?= html_password('password','maxlength="100"') ?>
                <?= err('password') ?>

                <section>
                    <button type="reset">Reset</button>
                    <button>Login</button>
                </section>

            </form>
        </div>
    </div>

    <div class="login-right">
        <div class="image-text-overlay"></div>
    </div>
</div> -->

<!-- <?php include '../_foot.php'; ?> -->
