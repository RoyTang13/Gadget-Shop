<?php
require '../_base.php';
$_title = 'edit Profile';
include 'admin_head.php';
// make sure only logged-in admins can access this page
if (!isset($_SESSION['adminID'])) {
    header('Location: index.php');
    exit;
}
// Fetch admin data
$stm = $_admin_db->prepare("SELECT * FROM admin WHERE adminID = ?");
$stm->execute([$_SESSION['adminID']]);
$admin = $stm->fetch(PDO::FETCH_ASSOC);
if (!$admin) redirect('/');
$_err = [];



// Load admin values
$fname      = $admin['fname'];
$lname      = $admin['lname'];
$email      = $admin['email'];
$phoneNo    = $admin['phoneNo'];
$adminPhoto = $admin['adminPhoto']; // Keep the original filename
$adminID    = $_SESSION['adminID'];  

$f = get_file('adminPhoto'); // get uploaded file

    // -----------------------------------------
    // UPDATE PROFILE
    // -----------------------------------------

    if (isset($_POST['update_profile'])) {

        $fname   = $_POST['fname'] ?? '';
        $lname   = $_POST['lname'] ?? '';
        $email   = $_POST['email'] ?? '';
        $phoneNo = $_POST['phoneNo'] ?? '';

        // --- VALIDATION ---
        if ($fname == '') $_err['fname'] = 'Required';
        if ($lname == '') $_err['lname'] = 'Required';

        if ($email == '') $_err['email'] = 'Required';
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $_err['email'] = 'Invalid';

        if ($phoneNo == '') $_err['phoneNo'] = 'Required';
        else if (!preg_match('/^01[0-9]-[0-9]{7}$/', $phoneNo))
            $_err['phoneNo'] = 'Invalid format';

        // Validate photo only if uploaded
        if ($f) {
            if (!str_starts_with($f->type, 'image/'))
                $_err['adminPhoto'] = 'Must be image';
            else if ($f->size > 1 * 1024 * 1024)
                $_err['adminPhoto'] = 'Max 1MB';
        }

        if (empty($_err)) {

            // ---- PHOTO SAVE ----
            if ($f) {
                if ($adminPhoto) {
                    @unlink("../adminPhoto/$adminPhoto");  // delete old photo
                }
                $adminPhoto = save_photo($f, "../adminPhoto");  // save new photo
            }

            // ---- UPDATE DB ---- 
            $up = $_db->prepare("
                UPDATE admin
                SET fname=?, lname=?, email=?, phoneNo=?, adminPhoto=?
                WHERE adminID=?
         ");
            $up->execute([$fname, $lname, $email, $phoneNo, $adminPhoto, $adminID]);

                // ---- UPDATE SESSION ----
             $_SESSION['fname'] = $fname;
             $_SESSION['email'] = $email;
                $_SESSION['adminPhoto'] = $adminPhoto;

                set_popup("Profile updated successfully");
                redirect('/admin/admin_profile.php');
        }
    }


    // -----------------------------------------
    // UPDATE PASSWORD
    // -----------------------------------------
    if (isset($_POST['update_password'])) {
        $current_pass = $_POST['current_pass'] ?? '';
        $new_pass = $_POST['new_pass'] ?? '';
        $confirm_pass = $_POST['confirm_pass'] ?? '';

        if ($current_pass == '') $_err['current_pass'] = 'Required';
        else if (!password_verify($current_pass, $admin['password'])) $_err['current_pass'] = 'Current password incorrect';

        if ($new_pass == '') $_err['new_pass'] = 'Required';
        else if (strlen($new_pass) < 6) $_err['new_pass'] = 'Minimum 6 chars';

        if ($confirm_pass == '') $_err['confirm_pass'] = 'Required';
        else if ($new_pass !== $confirm_pass)
            $_err['confirm_pass'] = 'Does not match';        

        if (empty($_err)) {
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);

            $up = $_db->prepare("UPDATE admin SET password=? WHERE adminID=?");
            $up->execute([$hashed, $adminID]);

            set_popup("Password updated");
            redirect('/admin/admin_profile.php');
        }
    }

    ?>

<section class="admin-profile-layout">

    <!-- LEFT : PROFILE CARD -->
    <div class="profile-card">
        <img class="avatar"
             src="/adminPhoto/<?= htmlspecialchars($adminPhoto ?: 'default.png') ?>"
             alt="Admin Photo">

        <h3><?= htmlspecialchars($fname . ' ' . $lname) ?></h3>
        <p><?= htmlspecialchars($email) ?></p>

        <a class="logout-btn" href="/admin/admin_logout.php">Logout</a>
    </div>

    <!-- RIGHT : FORMS -->
    <div class="profile-forms">

        <!-- UPDATE PROFILE -->
        <div class="form-box">
            <h2>Edit Profile</h2>

            <form method="post" enctype="multipart/form-data">
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

                <label>Profile Photo</label>
                <?= html_file('adminPhoto', 'image/*') ?>
                <?= err('adminPhoto') ?>

                <button type="submit" name="update_profile">Update Profile</button>
            </form>
        </div>

        <!-- UPDATE PASSWORD -->
        <div class="form-box">
            <h2>Change Password</h2>

            <form method="post">
                <label>Current Password</label>
                <input type="password" name="current_pass">
                <?= err('current_pass') ?>

                <label>New Password</label>
                <input type="password" name="new_pass">
                <?= err('new_pass') ?>

                <label>Confirm Password</label>
                <input type="password" name="confirm_pass">
                <?= err('confirm_pass') ?>

                <button type="submit" name="update_password">Update Password</button>
            </form>
        </div>

    </div>
</section>


