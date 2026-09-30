<?php
// ============================================
// LAYOUTS/AUTH.PHP — penjaga halaman admin
// Role dicek ke DATABASE di setiap request (bukan cuma dari session).
// ============================================
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php';

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$st = $pdo->prepare('SELECT id, username, role FROM users WHERE id = ?');
$st->execute([$_SESSION['user_id']]);
$user_aktif = $st->fetch();

if (!$user_aktif) {               // akun sudah dihapus
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}
if ($user_aktif['role'] !== 'admin') {   // user biasa tidak boleh masuk
    header('Location: dashboard.php');
    exit;
}

$nama_admin = e($user_aktif['username']);
$tanggal    = tgl_id();
$jam_login  = date('H:i', $_SESSION['login_time'] ?? time());