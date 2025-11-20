<?php
require '../_base.php';

$_title = 'Page | Demo 2';
include '../_head.php';
?>

<div class="login-wrapper">
    <div class="login-left">
        <div class="login-form-box">
            <h2 class="login-title">Log In</h2>

            <form action="" method="post">
                <label for="email">Email</label>
                <?= html_text('email','maxlength="100" data-upper') ?>
                <?= err('id') ?>

                <label for="password">Password</label>
                <?= html_text('password','maxlength="100"') ?>
                <?= err('password') ?>

                <section>
                    <button>Submit</button>
                    <button type="reset">Reset</button>
                </section>

                <div class="links">
                    Don't have account? <a href="/page/register.php">Click here</a>
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