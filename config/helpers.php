<?php
// ============================================
// CONFIG/HELPERS.PHP — session, CSRF, flash, dll.
// ============================================
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => !empty($_SERVER['HTTPS']),
    ]);
    session_start();
}

function e($s) {
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check() {
    $t = $_POST['csrf'] ?? '';
    if (empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $t)) {
        http_response_code(403);
        exit('Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.');
    }
}

function flash($msg, $type = 'ok') {
    $_SESSION['flash'] = ['m' => $msg, 't' => $type];
}

function flash_show() {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        echo '<div class="alert alert-' . e($f['t']) . '">' . e($f['m']) . '</div>';
    }
}

function client_ip() {
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function tgl_id($ts = null) {
    $bulan = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    $ts = $ts ?: time();
    return date('d', $ts) . ' ' . $bulan[(int) date('n', $ts)] . ' ' . date('Y', $ts);
}

// Path file yang aman (tanpa "..", tanpa awalan "/", tanpa http://)
function path_aman($p) {
    return $p !== ''
        && strpos($p, '..') === false
        && $p[0] !== '/'
        && preg_match('/^[\w .()\-\/]+$/u', $p) === 1;
}

// Akun pemilik utama (super admin). Akun ini TIDAK bisa diturunkan atau dihapus
// oleh siapa pun lewat halaman Pengguna, dan hanya dia yang boleh mengatur role.
const OWNER_EMAIL = 'jhonatanfarles@gmail.com';

function is_owner_email($email) {
    return strcasecmp((string) $email, OWNER_EMAIL) === 0;
}