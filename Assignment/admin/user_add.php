<?php
require '../_base.php';
$_title = 'Add User';
include 'admin_head.php';

    // Admin protection
    if (!isset($_SESSION['adminID'])) {
        header('Location: index.php');
        exit;
    }

    $errors = [];
    $success = false;

    // Default values
    $fname = $lname = $email = $phoneNo = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $fname    = trim($_POST['fname'] ?? '');
        $lname    = trim($_POST['lname'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $phoneNo  = trim($_POST['phoneNo'] ?? '');
        $password = $_POST['password'] ?? '';

        // ---- Validation ----
        if ($fname === '') {
            $errors['fname'] = 'First name is required.';
        }

        if ($lname === '') {
            $errors['lname'] = 'Last name is required.';
        }

        if ($email === '') {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format.';
        }
        if ($phoneNo === '') {
            $errors['phoneNo'] = 'Phone number is required.';
        } elseif (!preg_match('/^01[0-9]{1}-?[0-9]{7,8}$/', $phoneNo)) {
            $errors['phoneNo'] = 'Phone format: 01X-XXXXXXX or 01XXXXXXXX';
        }

        if ($password === '') {
            $errors['password'] = 'Password is required.';
        } elseif (strlen($password) < 6) {
            $errors['password'] = 'Minimum 6 characters.';
        }

        // Check duplicate email
        if (empty($errors)) {
            $stm = $_db->prepare("SELECT COUNT(*) FROM user WHERE email = ?");
            $stm->execute([$email]);
            if ($stm->fetchColumn() > 0) {
                $errors['email'] = 'Email already exists.';
            }
        }

        // ---- Insert ----
        if (!$errors) {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stm = $_db->prepare("
                INSERT INTO user (fname, lname, email, phoneNo, password, status)
                VALUES (?, ?, ?, ?, ?, 'active')
            ");
            $stm->execute([$fname, $lname, $email, $phoneNo, $hash]);

            header('Location: user_list.php?added=1');
            exit;
        }
    }
?>

<main class="page-wrapper">
    <div class="form-card">
        <h2 class="title">➕ Add New User</h2>
        <p class="subtitle">Create a new user account</p>

        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" class="user-form">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="fname" value="<?= htmlspecialchars($fname) ?>">
                <?php if (isset($errors['fname'])): ?>
                    <small class="err"><?= $errors['fname'] ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="lname" value="<?= htmlspecialchars($lname) ?>">
                <?php if (isset($errors['lname'])): ?>
                    <small class="err"><?= $errors['lname'] ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
                <?php if (isset($errors['email'])): ?>
                    <small class="err"><?= $errors['email'] ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phoneNo" value="<?= htmlspecialchars($phoneNo) ?>">
                <?php if (isset($errors['phoneNo'])): ?>
                    <small class="err"><?= $errors['phoneNo'] ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password">
                <?php if (isset($errors['password'])): ?>
                    <small class="err"><?= $errors['password'] ?></small>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <i class="fa fa-user-plus"></i> Create User
                </button>
                <a href="user_list.php" class="btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</main>


<style>
    .page-wrapper {
        display: flex;
        justify-content: center;
        padding: 40px 20px;
    }

    .form-card {
        width: 100%;
        max-width: 520px;
        background: #ffffff;
        border-radius: 14px;
        padding: 30px;
        box-shadow: 0 8px 25px rgba(0,0,0,.08);
    }

    .title {
        text-align: center;
        margin-bottom: 5px;
        font-weight: 700;
    }

    .subtitle {
        text-align: center;
        color: #666;
        margin-bottom: 25px;
    }

    .user-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .form-group label {
        font-weight: 600;
        font-size: 14px;
    }

    .form-group input {
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        transition: 0.2s;
    }

    .form-group input:focus {
        border-color: #be06ec;
        outline: none;
        box-shadow: 0 0 0 2px rgba(190,6,236,.15);
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    .btn-primary {
        flex: 1;
        background: #be06ec;
        color: #fff;
        border: none;
        padding: 11px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-primary:hover {
        background: #d17de6;
    }

    .btn-secondary {
        flex: 1;
        background: #e5e5e5;
        color: #333;
        padding: 11px;
        text-align: center;
        border-radius: 8px;
        text-decoration: none;
    }

    .btn-secondary:hover {
        background: #ccc;
    }
</style>
<?php include '../_foot.php'; ?>