<?php
session_start();

if (!isset($_SESSION['email'])) {
    $current_url = urlencode($_SERVER['REQUEST_URI']);
    header("Location: index.php?return_url=$current_url");
    exit();
}
?>
