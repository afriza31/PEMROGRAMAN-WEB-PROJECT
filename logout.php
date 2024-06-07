<?php
session_start();
if (!isset($_SESSION['uname'])) {
    header("Location: index.php");
    exit();
}

session_destroy();
setcookie("uname", "", time() - 3600);
setcookie("pass", "", time() - 3600);
header("Location: login.php");
exit();
?>
