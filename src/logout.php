<?php
session_start();

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

// Menghancurkan semua data session
session_unset();
session_destroy();

// Mengalihkan ke halaman login setelah logout
header("Location: login.php");
exit();
?>

