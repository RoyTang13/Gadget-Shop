    <?php
    require '../_base.php';

    $_title = 'Profile | TechNest';
    include '../_head.php';
    show_popup();

    $userID = $_SESSION['userID'];

    $stm = $_db->prepare("SELECT * FROM user WHERE userID = ?");
    $stm->execute([$userID]);
    $user = $stm->fetch(PDO::FETCH_ASSOC);

    if (!$user) redirect('/');
    $_err = [];


    // Load user values
    $fname   = $user['fname'];
    $lname   = $user['lname'];
    $email   = $user['email'];
    $phoneNo = $user['phoneNo'];
    $userPhoto   = $user['userPhoto'];   

    $f = get_file('userPhoto'); // get uploaded file

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
                $_err['userPhoto'] = 'Must be image';
            else if ($f->size > 1 * 1024 * 1024)
                $_err['userPhoto'] = 'Max 1MB';
        }

        if (empty($_err)) {

            // ---- PHOTO SAVE ----
            if ($f) {
                if ($userPhoto) {
                    @unlink("../userPhoto/$userPhoto");  // delete old
                }
                $userPhoto = save_photo($f, "../Assignment/userPhoto");  // save new
            }

            // ---- UPDATE DB ---- 
            $up = $_db->prepare("
                UPDATE user
                SET fname=?, lname=?, email=?, phoneNo=?, userPhoto=?
                WHERE userID=?
            ");
            $up->execute([$fname, $lname, $email, $phoneNo, $userPhoto, $userID]);

            // ---- UPDATE SESSION ----
            $_SESSION['fname'] = $fname;
            $_SESSION['email'] = $email;
            $_SESSION['userPhoto'] = $userPhoto; 
            set_popup("Profile updated successfully");
            redirect('/page/profile.php');
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
        else if (!password_verify($current_pass, $user['password'])) $_err['current_pass'] = 'Current password incorrect';

        if ($new_pass == '') $_err['new_pass'] = 'Required';
        else if (strlen($new_pass) < 6) $_err['new_pass'] = 'Minimum 6 chars';

        if ($confirm_pass == '') $_err['confirm_pass'] = 'Required';
        else if ($new_pass !== $confirm_pass)
            $_err['confirm_pass'] = 'Does not match';        

        if (empty($_err)) {
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);

            $up = $_db->prepare("UPDATE user SET password=? WHERE userID=?");
            $up->execute([$hashed, $userID]);

            set_popup("Password updated");
            redirect('/page/profile.php');
        }
    }

    ?>
    <!-- Profile Box -->
    <div class="profile-box">
        <h2 class="login-title">Your Profile</h2>
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

            <label for="photo">Photo</label>
            <label class="upload" tabindex="0">
                <?= html_file('userPhoto', 'image/*', 'hidden') ?>
                <img src="/userPhoto/<?= htmlspecialchars($_SESSION['userPhoto'])?>">
            </label>
            <?= err('userPhoto') ?>

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
        <form method="post" enctype="multipart/form-data">
            <label>Current Password</label>
                <input type="password" name="current_pass">
                <?= err('current_pass') ?>
                <br>

            <label>New Password</label>
            <input type="password" name="new_pass">
            <?= err('new_pass') ?>
            <br>

            <label>Confirm New Password</label>
            <input type="password" name="confirm_pass">
            <?= err('confirm_pass') ?>
            <br>

            <section>
                <button type="submit" name="update_password">Update Password</button>
            </section>

        </form>
    </div>
