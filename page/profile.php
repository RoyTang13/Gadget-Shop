<?php
require '../_base.php';

$_title = 'Profile | TechNest';
include '../_head.php';

// Ensure user is logged in
if (!isset($_SESSION['userID'])) {
    redirect('/page/login.php');
}

$_err = [];
$_success = [];

// Fetch user data
$userID = $_SESSION['userID'];
$stm = $_db->prepare("SELECT * FROM user WHERE userID = ?");
$stm->execute([$userID]);
$user = $stm->fetch(PDO::FETCH_ASSOC);

$fname = $user['fname'];
$lname = $user['lname'];
$email = $user['email'];
$phoneNo = $user['phoneNo'];

// Handle profile update
if (isset($_POST['update_profile'])) {
    $fname = $_POST['fname'] ?? '';
    $lname = $_POST['lname'] ?? '';
    $email = $_POST['email'] ?? '';
    $phoneNo = $_POST['phoneNo'] ?? '';

    if ($fname == '') $_err['fname'] = 'Required';
    else if (strlen($fname) > 100) $_err['fname'] = 'Maximum length 100';

    if ($lname == '') $_err['lname'] = 'Required';
    else if (strlen($lname) > 100) $_err['lname'] = 'Maximum length 100';

    if ($email == '') $_err['email'] = 'Required';
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $_err['email'] = 'Invalid email format';
    else if (!is_unique($email, 'user', 'email', $userID)) $_err['email'] = 'Email already used';

    if ($phoneNo == '') $_err['phoneNo'] = 'Required';
    else if (!preg_match('/^01[0-9]-[0-9]{7}$/', $phoneNo)) $_err['phoneNo'] = 'Invalid phone number';
    else if (!is_unique($phoneNo, 'user', 'phoneNo', $userID)) $_err['phoneNo'] = 'Phone already used';

    if (empty($_err)) {
        $update = $_db->prepare("UPDATE user SET fname=?, lname=?, email=?, phoneNo=? WHERE userID=?");
        $update->execute([$fname, $lname, $email, $phoneNo, $userID]);
        temp('info', 'Profile updated successfully');

        $_SESSION['fname'] = $fname;
        $_SESSION['email'] = $email;

        redirect('/page/profile.php');
    }
}

// Handle password update
if (isset($_POST['update_password'])) {
    $current_pass = $_POST['current_pass'] ?? '';
    $new_pass = $_POST['new_pass'] ?? '';
    $confirm_pass = $_POST['confirm_pass'] ?? '';

    if ($current_pass == '') $_err['current_pass'] = 'Required';
    else if (!password_verify($current_pass, $user['password'])) $_err['current_pass'] = 'Current password incorrect';

    if ($new_pass == '') $_err['new_pass'] = 'Required';
    else if (strlen($new_pass) < 6) $_err['new_pass'] = 'Minimum 6 characters';

    if ($confirm_pass != $new_pass) $_err['confirm_pass'] = 'Passwords do not match';

    if (empty($_err)) {
        $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
        $update_pass = $_db->prepare("UPDATE user SET password=? WHERE userID=?");
        $update_pass->execute([$hashed, $userID]);
        $_success['password'] = 'Password updated!';
    }
}
?>
<!-- Profile Box -->
<div class="profile-box">
    <h2 class="login-title">Your Profile</h2>
    <form method="post">
        <label>First Name</label>
        <input type="text" name="fname" value="<?= htmlspecialchars($fname) ?>">
        <?= err('fname') ?>

        <label>Last Name</label>
        <input type="text" name="lname" value="<?= htmlspecialchars($lname) ?>">
        <?= err('lname') ?>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
        <?= err('email') ?>

        <label>Phone Number</label>
        <input type="text" name="phoneNo" value="<?= htmlspecialchars($phoneNo) ?>">
        <?= err('phoneNo') ?>

        <section>
            <button type="submit" name="update_profile">Update Profile</button>
        </section>
    </form>
    <div class="links">
        <a href="/page/logout.php">Logout</a>
    </div>
</div>

<!-- Password Box -->
<div class="password-profile">
    <h2 class="login-title">Update Password</h2>
    <form method="post">
        <label>Current Password</label>
        <input type="password" name="current_pass">
        <?= err('current_pass') ?>

        <label>New Password</label>
        <input type="password" name="new_pass">
        <?= err('new_pass') ?>

        <label>Confirm New Password</label>
        <input type="password" name="confirm_pass">
        <?= err('confirm_pass') ?>

        <section>
            <button type="submit" name="update_password">Update Password</button>
        </section>

        <?php if (!empty($_success['password'])): ?>
            <p style="color:green; margin-top:10px;"><?= $_success['password'] ?></p>
        <?php endif; ?>
    </form>
</div>
