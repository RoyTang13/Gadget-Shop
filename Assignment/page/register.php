<style>
    body{
        background: E0E0E0;
    }
</style>

<?php
require '../_base.php';
$fname = $lname = $email = $phoneNo = $password = $cpassword = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $phoneNo = $_POST['phoneNo'];
        $password = $_POST['password'];
        $cpassword = $_POST['cpassword'];
    }


    if (is_post()) {
        // Input
        $userid      = req('userID');
        $fname       = req('fname');
        $lname       = req('lname');
        $email       = req('email');
        $phoneNo     = req('phoneNo');
        $f = get_file('userPhoto');
        $password    = req('password');
        $cpassword = req('cpassword');



    // Validate first name
    if ($fname == '') {
        $_err['fname'] = 'Required';
    }
    else if (strlen($fname) > 100) {
        $_err['fname'] = 'Maximum length 100';
    }

    // Validate last name
    if ($lname == '') {
        $_err['lname'] = 'Required';
    }
    else if (strlen($lname) > 100) {
        $_err['lname'] = 'Maximum length 100';
    }

    //verify the unique email
    if ($email == '') {
        $_err['email'] = 'Required';
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_err['email']  = "Invalid email format";
    }
    else if (!is_unique($email, 'user', 'email')) {
        $_err['email'] = 'Duplicated';
    }

    //verify the unique phone number
    if ($phoneNo == '') {
        $_err['phoneNo'] = 'Required';
    }
    else if (!preg_match('/^01[0-9]-[0-9]{7}$/', $phoneNo)) {
        $_err['phoneNo'] = 'Invalid phone number format';
    }

    else if (!is_unique($phoneNo, 'user', 'phoneNo')) {
        $_err['phoneNo'] = 'Duplicated phone number';
    }

    // Validate: photo (file)
    if (!$f) {
        $_err['photo'] = 'Required';
    }
    else if (!str_starts_with($f->type, 'image/')) {
        $_err['photo'] = 'Must be image';
    }
    else if ($f->size > 1 * 1024 * 1024) {
        $_err['photo'] = 'Maximum 1MB';
    }


    //valid password
    if ($password == '') {
        $_err['password'] = 'Required';
    }   
    else if (strlen($password) < 8) {
        $_err['password'] = 'Minimum 8 characters';
    }
    else if (
        !preg_match('/[A-Z]/', $password) ||        
        !preg_match('/[a-z]/', $password) ||        
        !preg_match('/[0-9]/', $password) ||        
        !preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)           // special character
    ) {
        $_err['password'] = 'Must include upper, lower, number, and special character';
    }

    //Check comfirm password
    if ($cpassword == '') {
        $_err['cpassword'] = 'Required';
    }
    else if ($password !== $cpassword) {
        $_err['cpassword'] = 'Password does not match';
    }

     // Output
     if (!$_err) {
        $userPhoto = save_photo($f, "../Assignment/userPhoto");
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stm = $_db->prepare('INSERT INTO user
            (fname, lname, email, phoneNo,userPhoto, password)
            VALUES (?, ?, ?, ?, ?, ?)');
        $stm->execute([$fname, $lname, $email, $phoneNo, $userPhoto,$hashed_password]);
        
        temp('info', 'Record inserted');
        redirect('/page/login.php');
    }
    }



include '../_head.php';
?>


<div class="container">
    <div class="box form-box">
    <h2 class="login-title">Sign Up</h2>
    <form method="post" class="form" enctype="multipart/form-data">
                <label for="fname">First Name</label>
                <?= html_text('fname','maxlength="100" data-upper placeholder="Roy"') ?>
                <?= err('fname') ?>

                <label for="lname">Last Name</label>
                <?= html_text('lname','maxlength="100" data-upper placeholder="Tang"') ?>
                <?= err('lname') ?>

                <label for="email">Email</label>
                <?= html_text('email','maxlength="100" placeholder="example@gmail.com"') ?>
                <?= err('email') ?>

                <label for="phoneNo">Phone Number</label>
                <?= html_text('phoneNo','maxlength="100" placeholder="012-3456789"') ?>
                <?= err('phoneNo') ?>

                <label for="photo">Photo</label>
                <label class="upload" tabindex="0">
                    <?= html_file('userPhoto', 'image/*','hidden') ?>
                    <img src="/images/photo.jpg">
                </label>
                <?= err('photo') ?>

                <label for="password">Password</label>
                <?= html_password('password','maxlength="100" placeholder="Must include upper, lower, number, and special character" ') ?>
                <?= err('password') ?>

                <label for="cpassword">Confirm Password</label>
                <?= html_password('cpassword','maxlength="100" placeholder="Must include upper, lower, number, and special character" ') ?>
                <?= err('cpassword') ?>

                <section>
                    <button>Submit</button>
                    <button type="reset">Reset</button>
                </section>

                <div class="links">
                    Already a member? <a href="/page/login.php">Sign In Here</a>
                </div>
        </form>
    </div>
</div>
