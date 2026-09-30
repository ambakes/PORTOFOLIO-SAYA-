<?php
// ============================================
// PROJECTS.PHP — KELOLA PROYEK (CRUD dari database)
// Ada pencarian + filter kategori (FR-03).
// ============================================
$active_page = 'projects';
$page_title  = 'Kelola Proyek';

include 'layouts/auth.php';

$q   = trim($_GET['q'] ?? '');
$kat = $_GET['kategori'] ?? 'all';

$sql    = 'SELECT * FROM projects WHERE 1=1';
$params = [];
if ($q !== '') {
    $sql .= ' AND (title LIKE ? OR tag LIKE ? OR description LIKE ?)';
    $like = '%' . $q . '%';
    array_push($params, $like, $like, $like);
}
if (in_array($kat, ['web', 'game'], true)) {
    $sql .= ' AND category = ?';
    $params[] = $kat;
} else {
    $kat = 'all';
}
$sql .= ' ORDER BY id DESC';
$st = $pdo->prepare($sql);
$st->execute($params);
$projects = $st->fetchAll();

include 'layouts/header.php';
include 'layouts/sidebar.php';
?>

    <div class="container">

      <div class="page-head">
        <div>
          <h1>Kelola Proyek</h1>
          <p><?= count($projects) ?> proyek<?= ($q !== '' || $kat !== 'all') ? ' (hasil filter)' : ' ditampilkan di Hall of Creations' ?></p>
        </div>
        <a href="project_form.php" class="abtn"><i class="fa-solid fa-plus"></i> Tambah Proyek</a>
      </div>

      <?php flash_show(); ?>

      <form method="GET" class="toolbar">
        <input type="search" name="q" value="<?= e($q) ?>" placeholder="Cari judul, tag, atau deskripsi...">
        <select name="kategori">
          <option value="all"  <?= $kat === 'all'  ? 'selected' : '' ?>>Semua kategori</option>
          <option value="web"  <?= $kat === 'web'  ? 'selected' : '' ?>>Web</option>
          <option value="game" <?= $kat === 'game' ? 'selected' : '' ?>>Game</option>
        </select>
        <button type="submit" class="abtn abtn-ghost"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
        <?php if ($q !== '' || $kat !== 'all') { ?><a href="projects.php" class="abtn abtn-ghost">Reset</a><?php } ?>
      </form>

      <div class="panel">
        <?php if (!$projects) { ?>
          <div class="empty">Tidak ada proyek ditemukan.</div>
        <?php } else { ?>
          <div class="table-wrap">
            <table class="data-table">
              <thead>
                <tr><th>Gambar</th><th>Proyek</th><th>Kategori</th><th>Progress</th><th>Aksi</th></tr>
              </thead>
              <tbody>
              <?php foreach ($projects as $p) { ?>
                <tr>
                  <td><img class="thumb" src="<?= e($p['img']) ?>" alt=""></td>
                  <td>
                    <strong><?= e($p['title']) ?></strong>
                    <div class="muted" style="font-size:.78rem"><?= e($p['tag']) ?></div>
                  </td>
                  <td><span class="pill"><?= e($p['category']) ?></span></td>
                  <td>
                    <?php if ($p['progress'] !== null) { ?>
                      <div class="bar"><span style="width: <?= (int) $p['progress'] ?>%"></span></div>
                      <span class="muted" style="font-size:.78rem"><?= (int) $p['progress'] ?>%</span>
                    <?php } else { ?><span class="muted">—</span><?php } ?>
                  </td>
                  <td>
                    <div class="row-actions">
                      <a href="project_form.php?id=<?= (int) $p['id'] ?>" class="abtn abtn-ghost abtn-sm"><i class="fa-solid fa-pen"></i> Ubah</a>
                      <form method="POST" action="project_delete.php" data-confirm="Hapus proyek '<?= e($p['title']) ?>'? Tindakan ini tidak bisa dibatalkan.">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                        <button type="submit" class="abtn abtn-danger abtn-sm"><i class="fa-solid fa-trash"></i> Hapus</button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php } ?>
              </tbody>
            </table>
          </div>
        <?php } ?>
      </div>

    </div>

<?php include 'layouts/footer.php'; ?>
