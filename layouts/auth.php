<?php
// ============================================
// DASHBOARD.PHP — USER BIASA
// ============================================

session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

$nama_user = htmlspecialchars($_SESSION["username"]);

$nama_admin = htmlspecialchars($_SESSION["username"]);
$jam_login  = date("H:i", time());
$tanggal    = date("d F Y");
?>