<?php
require '../_base.php';
$_title = 'Member Edit';
include 'admin_head.php';

// Check if user is logged in as admin
if (!isset($_SESSION['adminID'])) {
    header('Location: ../admin/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);


if ($name === '') $errors[] = 'Name required';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email';


// Photo update (optional)
$photo = $m->photo;
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


redirect('member_detail.php?id=' . $id);
exit;
}
}
?>


<h1>Edit Member</h1>


<?php foreach ($errors as $e): ?>
<p style="color:red">• <?= $e ?></p>
<?php endforeach ?>


<form method="post" enctype="multipart/form-data">
<label>Name</label><br>
<input name="name" value="<?= htmlspecialchars($m->name) ?>"><br><br>


<label>Email</label><br>
<input name="email" value="<?= htmlspecialchars($m->email) ?>"><br><br>


<label>Phone</label><br>
<input name="phone" value="<?= htmlspecialchars($m->phone) ?>"><br><br>


<label>Profile Photo</label><br>
<?php if ($m->photo): ?>
<img src="/uploads/<?= htmlspecialchars($m->photo) ?>" width="80"><br>
<?php endif ?>
<input type="file" name="photo" accept="image/*"><br><br>


<button>Save</button>
    <a href="../admin/member_list.php?id=<?= $id ?>">Cancel</a>
</form>