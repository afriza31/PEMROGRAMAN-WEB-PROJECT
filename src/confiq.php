<?php
// Konfigurasi database
$sqluser = "root";
$sqlpassword = "";
$sqldatabase = "login";

// Mencoba untuk menghubungkan ke database MySQL
try {
    $pdo = new PDO("mysql:host=localhost;dbname=" . $sqldatabase, $sqluser, $sqlpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit("ERROR: Tidak bisa terhubung. " . $e->getMessage());
}
?>
