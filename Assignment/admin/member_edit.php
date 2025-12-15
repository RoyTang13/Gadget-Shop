<?php
require '../_base.php';
$_title = 'Member Edit';
include 'admin_head.php';

if (!isset($_SESSION['adminID'])) {
    header('Location: ../admin/index.php');
    exit;
}

// Get member ID from URL
$id = $_GET['id'] ?? null;
if (!$id) {
    die('Invalid member ID.');
}

// Fetch member data
$stmt = $_db->prepare("SELECT * FROM member WHERE memberID = ?");
$stmt->execute([$id]);
$m = $stmt->fetch(PDO::FETCH_OBJ);
if (!$m) {
    die('Member not found.');
}

// Initialize errors array
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if ($name === '') $errors[] = 'Name required';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email';

    // Photo update (optional)
    $photo = $m->photo; // default to current photo
    if (!empty($_FILES['photo']['name'])) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['photo']['tmp_name'], "../uploads/$photo");
    }

    if (!$errors) {
        $stm = $_db->prepare(
            'UPDATE member SET name=?, email=?, phone=?, photo=? WHERE memberID=?'
        );
        $stm->execute([$name, $email, $phone, $photo, $id]);

        header('Location: member_detail.php?id=' . $id);
        exit;
    }
}
?>
<section>
<h1>Edit Member</h1>

<?php foreach ($errors as $e): ?>
<p style="color:red">• <?= htmlspecialchars($e) ?></p>
<?php endforeach; ?>

<form method="post" enctype="multipart/form-data">
<label>Name</label><br>
<input name="name" value="<?= htmlspecialchars($m->name) ?>"><br><br>

<label>Email</label><br>
<input name="email" value="<?= htmlspecialchars($m->email) ?>"><br><br>

<label>Phone</label><br>
<input name="phone" value="<?= htmlspecialchars($m->phone) ?>"><br><br>

<label for="photo">Profile Photo</label><br>
<label class="upload" tabindex="0">
    <?= html_file('photo', 'image/*', 'hidden') ?>
     <img src="/userPhoto/<?= htmlspecialchars($photo['photo']) ?>">
</label>
<?= err('photo') ?>

<button type="submit">Save</button>
<a href="../admin/member_list.php?id=<?= $id ?>">Cancel</a>
</form>
</section>