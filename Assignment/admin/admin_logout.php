<?php
require '../_base.php'; 

// Destroy session
session_start();
session_unset();
session_destroy();

// Redirect to homepage
header('Location: /admin');
exit;
?>