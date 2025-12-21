<?php
require '../_base.php';
$_title = 'Verify Code | TechNest';
include '../_head.php';

$email = $_GET['email'] ?? '';
$_err = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = $_POST['code'] ?? '';

    $stm = $_db->prepare("SELECT * FROM user WHERE email=? AND reset_code=? AND reset_expiry > NOW()");
    $stm->execute([$email, $code]);
    $user = $stm->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $_err['code'] = 'User not found.';
    } else if (!$user['reset_code'] || strtotime($user['reset_expiry']) < time()) {
        // Token expired, update date to null
        $_db->prepare("UPDATE user SET reset_code=NULL, reset_expiry=NULL WHERE email=?")->execute([$email]);
        $_err['code'] = 'Verification code has expired.';
    } else if ($user['reset_code'] != $code) {
        $_err['code'] = 'Invalid verification code.';
    } else {
        // Code is valid, redirect to resetpassword.php
        $_db->prepare("UPDATE user SET reset_code=NULL, reset_expiry=NULL WHERE email=?")->execute([$email]);
        redirect('/page/reset_password.php?email=' . urlencode($email));
    }
}
?>

<div class="container">
    <div class="box form-box">
        <h2 class="login-title">Enter Verification Code</h2>
        <form method="post">
        
            <label for="code">6-digit Code</label>
            <input type="text" name="code" maxlength="6">
            <?= err('code') ?>

            <section>
                <button type="submit">Verify Code</button>
            </section>
        </form>
    </div>
</div>

<?php include '../_foot.php'; ?>
