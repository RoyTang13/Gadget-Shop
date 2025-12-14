<?php
require '../_base.php';

// Check if user is logged in as admin
if (!isset($_SESSION['adminID'])) {
    header('Location: index.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data and sanitize
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // Basic validation
    if ($name === '' || $email === '') {
        $message = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Invalid email address.';
    } else {
        // Insert into database
        $sql = "INSERT INTO member (name, email) VALUES (:name, :email)";
        $stmt = $_db->prepare($sql);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':email', $email);
        if ($stmt->execute()) {
            header('Location: member_list.php'); // Redirect to the list page
            exit;
        } else {
            $message = 'Failed to add member. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Add Member</title>
<!-- Include your CSS here -->
</head>
<body>
<h1>Add New Member</h1>

<?php if ($message): ?>
    <p style="color: red;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="post" action="">
    <label for="name">Name:</label><br/>
    <input type="text" id="name" name="name" required /><br/><br/>
    
    <label for="email">Email:</label><br/>
    <input type="email" id="email" name="email" required /><br/><br/>
    
    <button type="submit">Add Member</button>
</form>

<p><a href="member_list.php">Back to Member List</a></p>
</body>
</html>