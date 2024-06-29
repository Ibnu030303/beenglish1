<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session.
session_destroy();
session_unset();

// Redirect to login page
header("Location: index.php");
exit();
?>
