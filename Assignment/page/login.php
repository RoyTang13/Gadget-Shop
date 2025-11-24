<?php
require '../_base.php';

$_title = 'Page | Demo 2';
include '../_head.php';

$_err = [];

$email = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get input values
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate input
    if ($email == '') {
        $_err['email'] = 'Email is required';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_err['email'] = 'Invalid email format';
    }

    if ($password == '') {
        $_err['password'] = 'Password is required';
    }

    // If no errors, check database
    if (empty($_err)) {
        $stm = $_db->prepare("SELECT * FROM user WHERE email = ?");
        $stm->execute([$email]);
        $user = $stm->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $_err['email'] = 'Email not found';
        } else if (!password_verify($password, $user['password'])) { // hashed check
            $_err['password'] = 'Incorrect password';
        } else {
            // Login successful, save session
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['fname'] = $user['fname'];
            $_SESSION['email'] = $user['email'];
    
            // Redirect to homepage or dashboard
            redirect('/'); 
        }
    }
}
?>


<div class="login-wrapper">
    <div class="login-left">
        <div class="login-form-box">
            <h2 class="login-title">Log In</h2>

            <form action="" method="post">
                <label for="email">Email</label>
                <?= html_text('email','maxlength="100" ') ?>
                <?= err('email') ?>

                <label for="password">Password</label>
                <?= html_password('password','maxlength="100"') ?>
                <?= err('password') ?>

                <section>
                    <button>Submit</button>
                    <button type="reset">Reset</button>
                </section>

                <div class="links">
                    Don't have account? <a href="/page/register.php">Click here</a><br>
                    Forget account? <a href="/page/forgot_password.php">Click here</a>
                </div>
            </form>
        </div>
    </div>

    <div class="login-right">
    <div class="image-text-overlay">
        </div>
    </div>
</div>

<?php
include '../_foot.php';
?>