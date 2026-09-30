<?php
// ============================================
// PROJECT_DELETE.PHP — hapus proyek (hanya POST + CSRF)
// ============================================
include 'layouts/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: projects.php');
    exit;
}
csrf_check();

$id = (int) ($_POST['id'] ?? 0);
$st = $pdo->prepare('SELECT img FROM projects WHERE id = ?');
$st->execute([$id]);
$row = $st->fetch();

if ($row) {
    $pdo->prepare('DELETE FROM projects WHERE id = ?')->execute([$id]);
    // Hapus file hanya kalau memang hasil upload lewat form (folder uploads/)
    if (strpos($row['img'], 'uploads/') === 0 && path_aman($row['img'])) {
        $file = __DIR__ . '/' . $row['img'];
        if (is_file($file)) @unlink($file);
    }
    flash('Proyek berhasil dihapus.');
} else {
    flash('Proyek tidak ditemukan.', 'err');
}

header('Location: projects.php');
exit;