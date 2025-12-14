<?php
require '../_base.php'; // Include your database and session setup

// Check if admin is logged in
if (!isset($_SESSION['adminID'])) {
    header('Location: ../admin/index.php');
    exit;
}

// Get memberID from URL
$memberID = $_GET['id'] ?? null;
if (!$memberID) {
    die('Invalid member ID.');
}

// Fetch member to check if exists and get photo filename
$stmt = $_db->prepare("SELECT photo FROM member WHERE memberID = ?");
$stmt->execute([$memberID]);
$member = $stmt->fetch(PDO::FETCH_OBJ);

if (!$member) {
    die('Member not found.');
}

// Delete the member from database
$delStmt = $_db->prepare("DELETE FROM member WHERE memberID = ?");
$delStmt->execute([$memberID]);

// Delete photo file if exists
if ($member->photo) {
    $photoPath = "../uploads/" . $member->photo;
    if (file_exists($photoPath)) {
        unlink($photoPath);
    }
}

// Redirect back to member list
header('Location: member_list.php');
exit;
?>