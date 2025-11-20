<style>
    body{
        background: E0E0E0;
    }
</style>

<?php
require '../_base.php';

$_title = 'Page | Demo 2';
include '../_head.php';
?>

<div class="container">
    <div class="box form-box">
    <h2 class="login-title">Sign Up</h2>
    <form action="" method="post">
                <label for="fname">First Name</label>
                <?= html_text('fname','maxlength="100" data-upper') ?>
                <?= err('id') ?>

                <label for="lname">Last Name</label>
                <?= html_text('lname','maxlength="100" data-upper') ?>
                <?= err('id') ?>

                <label for="email">Email</label>
                <?= html_text('email','maxlength="100" data-upper') ?>
                <?= err('id') ?>

                <label for="phoneNo">Phone Number</label>
                <?= html_text('phoneNo','maxlength="100"') ?>
                <?= err('phoneNo') ?>

                <label for="password">Password</label>
                <?= html_text('password','maxlength="100"') ?>
                <?= err('password') ?>

                <label for="cpassword">Confirm Password</label>
                <?= html_text('cpassword','maxlength="100"') ?>
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
