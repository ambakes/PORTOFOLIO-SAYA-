<?php
// ============================================
// LAYOUTS/AUTH.PHP — CEK SESSION ADMIN
// Dipakai di setiap halaman admin (dashboard_admin.php, projects.php, dll)
// ============================================

session_start();

if (!isset($_SESSION["username"]) || !isset($_SESSION["is_admin"])) {
    header("Location: login.php");
    exit;
}

$nama_admin = htmlspecialchars($_SESSION["username"]);
$jam_login  = date("H:i", time());
$tanggal    = date("d F Y");
?>