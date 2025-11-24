<?php
require '../_base.php';
$_title = 'Forgot Password | TechNest';
include '../_head.php';

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../lib/Exception.php';
require '../lib/PHPMailer.php';
require '../lib/SMTP.php';

$_err = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';

    if ($email == '') {
        $_err['email'] = 'Email is required';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_err['email'] = 'Invalid email format';
    } else {
        // Check email exists
        $stm = $_db->prepare("SELECT * FROM user WHERE email = ?");
        $stm->execute([$email]);
        $user = $stm->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $_err['email'] = 'Email not found';
        } else {
            // Generate code
            $code = rand(100000, 999999);
            $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

            // Save code
            $up = $_db->prepare("UPDATE user SET reset_code=?, reset_expiry=? WHERE email=?");
            $up->execute([$code, $expiry, $email]);

            // Send email using PHPMailer
            $mail = new PHPMailer(true);

            try {
                // SMTP settings
                $mail->isSMTP();
                $mail->SMTPOptions = [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ];
                $mail->Host = "smtp.gmail.com";
                $mail->SMTPAuth = true;
                $mail->Username = "leyitang031013@gmail.com";  
                $mail->Password = "hgovnkdmrcxbpfil";  
                $mail->SMTPSecure = "tls";
                $mail->Port = 587;

                // Email content
                $mail->setFrom("leyitang031013@gmail.com", "TechNest");
                $mail->addAddress($email);
                $mail->Subject = "Password Reset Code";
                $mail->Body = "Your verification code is: $code";

                $mail->send();

                temp('info', 'Verification code sent to your email.');
                redirect('/page/verify_code.php?email=' . urlencode($email));

            } catch (Exception $e) {
                $_err['email'] = "Email could not be sent. Error: {$mail->ErrorInfo}";
            }
        }
    }
}
?>

<div class="container">
    <div class="box form-box">
        <h2 class="login-title">Forgot Password</h2>

        <form method="post">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
            <?= err('email') ?>

            <section>
                <button type="submit">Send Verification Code</button>
            </section>
        </form>

        <div class="links">
            <a href="/page/login.php">Back to Login</a>
        </div>
    </div>
</div>

<?php include '../_foot.php'; ?>
