<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if not logged in as admin
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>
