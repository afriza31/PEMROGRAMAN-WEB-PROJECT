<?php
session_start();

// Jika pengguna belum login, sertakan file login.php
if (!isset($_SESSION['uname'])) {
    include 'login.php';
    exit();
}

// Jika pengguna sudah login, sertakan file utama
include 'main.php';
?>
