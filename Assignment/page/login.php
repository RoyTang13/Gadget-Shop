<?php
require '../_base.php';

$_title = 'Page | Demo 2';
include '../_head.php';

$_err = [];
show_popup();

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

    // ------------------------------
    // reCAPTCHA VALIDATION
    // ------------------------------
    // $recaptcha = $_POST['g-recaptcha-response'] ?? '';
    // $secretKey = "6Lfymx4sAAAAAAhjdZaclLmEl69dKnxzS8PRqwM7"; 

    // $response = file_get_contents(
    //     "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptcha"
    // );
    // $responseKeys = json_decode($response, true);

    // if (empty($recaptcha) || !$responseKeys["success"]) {
    //     $_err['recaptcha'] = "Please verify you're not a robot";
    // }

    // If no errors, check database
    if (empty($_err)) {
        $stm = $_db->prepare("SELECT * FROM user WHERE email = ?");
        $stm->execute([$email]);
        $user = $stm->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $_err['email'] = 'Email not found';
        } else if ($user['status'] === 'banned') {
            set_popup('Your account has been banned.');
            redirect('login.php');
        } else if (!password_verify($password, $user['password'])) { // hashed check
            $_err['password'] = 'Incorrect password';
        } else {
            // Login successful, save session
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['fname'] = $user['fname'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['userPhoto'] = $user['userPhoto'];
            $_SESSION['password'] = $user['password'];
            // UPDATE LAST LOGIN
            $_db->prepare("UPDATE user SET lastLogin = NOW() WHERE userID = ?")
            ->execute([$user['userID']]);

            //set cookie for successsful login
            if (!empty($_POST['remember_me'])) {
                $token = bin2hex(random_bytes(32));
                $expiry = time() + (30 * 24 * 60 * 60); // 30 days
                setcookie('remember_me', $token, $expiry, '/', '', true, true);
            
                // Save token in database
                $_db->prepare("UPDATE user SET remember_token = ? WHERE userID = ?")
                    ->execute([$token, $user['userID']]);
            }

            // Redirect to homepage or dashboard
            redirect('/'); 
        }
    }
    
    // check the session for new login  
    if (!isset($_SESSION['userID']) && isset($_COOKIE['remember_me'])) {
        $token = $_COOKIE['remember_me'];
        $user = $_db->prepare("SELECT * FROM user WHERE remember_token = ?");
        $user->execute([$token]);
        $user = $user->fetch(PDO::FETCH_ASSOC);
    
        if ($user) {
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['fname'] = $user['fname'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['userPhoto'] = $user['userPhoto'];
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

                <div class="remember-me">
                    <label>
                        <input type="checkbox" name="remember_me" value="1">Remember Me
                    </label>
                </div>

<!-- 
                <div class="g-recaptcha" data-sitekey="6Lfymx4sAAAAABMqtubtNWizFORYHqcABGmCZeOl"></div>
                <?= err('recaptcha') ?> -->

                
                <section>
                     <button type="reset">Reset</button>
                    <button>Submit</button> 
                    
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
