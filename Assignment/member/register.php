<?php
require '_base.php';


$errors = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = $_POST['password'];


if ($name === '') $errors[] = 'Name required';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email';
if (strlen($password) < 6) $errors[] = 'Password min 6 chars';


// Upload photo
$photo = null;
if (!empty($_FILES['photo']['name'])) {
$ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
$photo = uniqid() . '.' . $ext;
move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/$photo");
}


if (!$errors) {
$stm = $_db->prepare(
'INSERT INTO member (name,email,password,photo) VALUES (?,?,?,?)'
);
$stm->execute([
$name,
$email,
password_hash($password, PASSWORD_DEFAULT),
$photo
]);


redirect('login.php');
exit;
}
}
?>


<h1>Member Registration</h1>


<?php foreach ($errors as $e): ?>
<p style="color:red">• <?= $e ?></p>
<?php endforeach ?>


<form method="post" enctype="multipart/form-data">
<input name="name" placeholder="Name"><br><br>
<input name="email" placeholder="Email"><br><br>
<input type="password" name="password" placeholder="Password"><br><br>
<input type="file" name="photo" accept="image/*"><br><br>
<button>Register</button>
</form>

<?php include '../_foot.php'; ?>