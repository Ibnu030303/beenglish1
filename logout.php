<?php
session_start();


// Destroy the session
session_destroy();
session_reset();
session_unset();

// Redirect to login page or any other appropriate page
header("Location: index.php");
exit();
?>
