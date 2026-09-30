<?php
// ============================================
// CONTACT.PHP — menerima pesan dari form kontak (JSON) dan menyimpannya ke DB
// ============================================
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/helpers.php';

header('Content-Type: application/json; charset=utf-8');

function balas($kode, $ok, $pesan) {
    http_response_code($kode);
    echo json_encode(['ok' => $ok, 'message' => $pesan]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') balas(405, false, 'Metode tidak diizinkan.');

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data)) balas(400, false, 'Data tidak valid.');

if (empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], (string) ($data['csrf'] ?? ''))) {
    balas(403, false, 'Sesi kedaluwarsa. Muat ulang halaman lalu coba lagi.');
}

// Batasi: 1 pesan per 30 detik per pengunjung
if (!empty($_SESSION['last_contact']) && time() - $_SESSION['last_contact'] < 30) {
    balas(429, false, 'Tunggu sebentar sebelum mengirim pesan lagi.');
}

$nama  = trim((string) ($data['nama'] ?? ''));
$email = trim((string) ($data['email'] ?? ''));
$pesan = trim((string) ($data['pesan'] ?? ''));

if ($nama === '' || mb_strlen($nama) > 100)              balas(422, false, 'Nama wajib diisi (maks. 100 karakter).');
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) balas(422, false, 'Masukkan email yang valid.');
if ($pesan === '' || mb_strlen($pesan) > 3000)           balas(422, false, 'Pesan wajib diisi (maks. 3000 karakter).');

$pdo->prepare('INSERT INTO messages (nama, email, pesan) VALUES (?, ?, ?)')->execute([$nama, $email, $pesan]);
$_SESSION['last_contact'] = time();

balas(200, true, 'Terima kasih! Pesan kamu sudah terkirim.');