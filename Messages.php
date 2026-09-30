<?php
// ============================================
// MESSAGES.PHP — pesan dari form kontak
// ============================================
$active_page = 'messages';
$page_title  = 'Pesan Masuk';

include 'layouts/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id  = (int) ($_POST['id'] ?? 0);
    $aksi = $_POST['aksi'] ?? '';
    if ($aksi === 'baca') {
        $pdo->prepare('UPDATE messages SET dibaca = 1 WHERE id = ?')->execute([$id]);
    } elseif ($aksi === 'belum') {
        $pdo->prepare('UPDATE messages SET dibaca = 0 WHERE id = ?')->execute([$id]);
    } elseif ($aksi === 'hapus') {
        $pdo->prepare('DELETE FROM messages WHERE id = ?')->execute([$id]);
        flash('Pesan dihapus.');
    } elseif ($aksi === 'baca_semua') {
        $pdo->exec('UPDATE messages SET dibaca = 1');
        flash('Semua pesan ditandai sudah dibaca.');
    }
    header('Location: messages.php' . (!empty($_POST['kembali']) ? '?' . http_build_query(['status' => $_POST['kembali']]) : ''));
    exit;
}

$status = $_GET['status'] ?? 'all';
$q      = trim($_GET['q'] ?? '');
$sql = 'SELECT * FROM messages WHERE 1=1';
$params = [];
if ($status === 'baru') $sql .= ' AND dibaca = 0'; else $status = 'all';
if ($q !== '') {
    $sql .= ' AND (nama LIKE ? OR email LIKE ? OR pesan LIKE ?)';
    $like = '%' . $q . '%';
    array_push($params, $like, $like, $like);
}
$sql .= ' ORDER BY id DESC';
$st = $pdo->prepare($sql);
$st->execute($params);
$messages = $st->fetchAll();

include 'layouts/header.php';
include 'layouts/sidebar.php';
?>

    <div class="container">
      <div class="page-head">
        <div>
          <h1>Pesan Masuk</h1>
          <p><?= count($messages) ?> pesan</p>
        </div>
        <form method="POST">
          <?= csrf_field() ?>
          <input type="hidden" name="aksi" value="baca_semua">
          <button type="submit" class="abtn abtn-ghost"><i class="fa-solid fa-check-double"></i> Tandai semua dibaca</button>
        </form>
      </div>

      <?php flash_show(); ?>

      <form method="GET" class="toolbar">
        <input type="search" name="q" value="<?= e($q) ?>" placeholder="Cari nama, email, atau isi pesan...">
        <select name="status">
          <option value="all"  <?= $status === 'all'  ? 'selected' : '' ?>>Semua pesan</option>
          <option value="baru" <?= $status === 'baru' ? 'selected' : '' ?>>Belum dibaca</option>
        </select>
        <button type="submit" class="abtn abtn-ghost"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
      </form>

      <div class="panel">
        <?php if (!$messages) { ?>
          <div class="empty">Tidak ada pesan.</div>
        <?php } foreach ($messages as $m) { ?>
          <div class="msg-card">
            <div class="msg-head">
              <div>
                <strong><?= e($m['nama']) ?></strong>
                <?php if (!$m['dibaca']) { ?><span class="pill pill-new">baru</span><?php } ?>
                <div class="muted" style="font-size:.82rem"><a href="mailto:<?= e($m['email']) ?>" style="color:inherit"><?= e($m['email']) ?></a></div>
              </div>
              <div class="muted" style="font-size:.82rem"><?= e(date('d/m/Y H:i', strtotime($m['created_at']))) ?></div>
            </div>
            <div class="msg-body"><?= nl2br(e($m['pesan'])) ?></div>
            <div class="row-actions">
              <form method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int) $m['id'] ?>">
                <input type="hidden" name="kembali" value="<?= e($status) ?>">
                <input type="hidden" name="aksi" value="<?= $m['dibaca'] ? 'belum' : 'baca' ?>">
                <button type="submit" class="abtn abtn-ghost abtn-sm"><?= $m['dibaca'] ? 'Tandai belum dibaca' : 'Tandai dibaca' ?></button>
              </form>
              <a href="mailto:<?= e($m['email']) ?>?subject=<?= rawurlencode('Re: pesan dari portofolio') ?>" class="abtn abtn-ghost abtn-sm"><i class="fa-solid fa-reply"></i> Balas</a>
              <form method="POST" data-confirm="Hapus pesan ini?">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int) $m['id'] ?>">
                <input type="hidden" name="kembali" value="<?= e($status) ?>">
                <input type="hidden" name="aksi" value="hapus">
                <button type="submit" class="abtn abtn-danger abtn-sm"><i class="fa-solid fa-trash"></i> Hapus</button>
              </form>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>

<?php include 'layouts/footer.php'; ?>