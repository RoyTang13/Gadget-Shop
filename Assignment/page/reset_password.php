<?php
require '../_base.php';
$_title = 'Reset Password | TechNest';
include '../_head.php';

$email = $_GET['email'] ?? '';
$_err = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $password = $_POST['password'] ?? '';
        $cpassword = $_POST['cpassword'] ?? '';

        if ($password == '') $_err['password'] = 'Required';
        else if (strlen($password) < 8) $_err['password'] = 'Minimum 8 characters';
        
        if ($cpassword != $password) $_err['cpassword'] = 'Passwords do not match';

        if (empty($_err)) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stm = $_db->prepare("UPDATE user SET password=?, reset_code=NULL, reset_expiry=NULL WHERE email=?");
            $stm->execute([$hashed, $email]);

            temp('info', 'Password reset successfully. You can now login.');
            redirect('/page/login.php');
        }
    }
?>

<div class="container">
    <div class="box form-box">
        <h2 class="login-title">Reset Password</h2>
        <form method="post">
            <label for="password">New Password</label>
            <input type="password" name="password">
            <?= err('password') ?>

            <label for="cpassword">Confirm New Password</label>
            <input type="password" name="cpassword">
            <?= err('cpassword') ?>

            <section>
                <button type="submit">Reset Password</button>
            </section>
        </form>
    </div>
</div>

<?php include '../_foot.php'; ?>
