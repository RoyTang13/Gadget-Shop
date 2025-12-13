<?php
require '../_base.php';
$_title = 'Change Password';
include 'admin_head.php';
// ----------------------------------------------------------------------------
$_err = []; // Validation errors

if (is_post()) {
    $password     = req('password');
    $new_password = req('new_password');
    $confirm      = req('confirm');

    // Validate: old password
    if ($password == '') {
        $_err['password'] = 'Required';
    }
    else if (strlen($password) < 5 || strlen($password) > 100) {
        $_err['password'] = 'Between 5-100 characters';
    }
    else {
        $stm = $_db->prepare('
            SELECT COUNT(*) FROM user
            WHERE password = SHA1(?) AND id = ?
        ');
        $stm->execute([$password, $_user->id]);
        
        if ($stm->fetchColumn() == 0) {
            $_err['password'] = 'Not matched';
        }
    }

    // Validate: new_password
    if ($new_password == '') {
        $_err['new_password'] = 'Required';
    }
    else if (strlen($new_password) < 5 || strlen($new_password) > 100) {
        $_err['new_password'] = 'Between 5-100 characters';
    }

    // Validate: confirm password
    if (!$confirm) {
        $_err['confirm'] = 'Required';
    }
    else if (strlen($confirm) < 5 || strlen($confirm) > 100) {
        $_err['confirm'] = 'Between 5-100 characters';
    }
    else if ($confirm != $new_password) {
        $_err['confirm'] = 'Not matched';
    }

    // DB operation
    if (!$_err) {
        // Update user (password)
        $stm = $_db->prepare('
            UPDATE user
            SET password = SHA1(?)
            WHERE id = ?
        ');
        $stm->execute([$new_password, $_user->id]);

        temp('info', 'Record updated');
        redirect('/');
    }
}

// ----------------------------------------------------------------------------
// HTML output
?>
<div class="login-wrapper">
    <div class="login-left">
        <div class="login-form-box">
            <h2 class="login-title">Change Password</h2>

            <form method="post" class="form">
                 <label for="password">Password</label>
                 <?= html_password('password', 'maxlength="100"') ?>
                 <?= err('password') ?>

                   <label for="new_password">New Password</label>
                   <?= html_password('new_password', 'maxlength="100"') ?>
                  <?= err('new_password') ?>

                  <label for="confirm">Confirm password</label>
                  <?= html_password('confirm', 'maxlength="100"') ?>
                  <?= err('confirm') ?>

                  <section>
                        <button>Submit</button>
                     <button type="reset">Reset</button>
                  </section>
            </form>
        </div>
    </div>

    <div class="chpass-right">
        <div class="image-text-overlay"></div>
    </div>
</div> 



<?php
include '../_foot.php';
?>