<?php
// ============================================
// REPORT.PHP — rekap laporan siap cetak / simpan PDF (FR-04)
// Klik "Cetak / Simpan PDF", lalu pilih "Save as PDF" di dialog cetak browser.
// ============================================
$active_page = 'report';
$page_title  = 'Laporan';

include 'layouts/auth.php';

$projects = $pdo->query('SELECT title, tag, category, progress, created_at FROM projects ORDER BY id')->fetchAll();
$users    = $pdo->query('SELECT username, email, role, created_at FROM users ORDER BY id')->fetchAll();
$total_pesan = (int) $pdo->query('SELECT COUNT(*) FROM messages')->fetchColumn();
$pesan_baru  = (int) $pdo->query('SELECT COUNT(*) FROM messages WHERE dibaca = 0')->fetchColumn();

include 'layouts/header.php';
include 'layouts/sidebar.php';
?>

    <div class="container">
      <div class="page-head">
        <div>
          <h1>Laporan Rekapitulasi</h1>
          <p>Dicetak <?= e(tgl_id()) ?>, pukul <?= e(date('H:i')) ?> oleh <?= $nama_admin ?></p>
        </div>
        <button type="button" class="abtn no-print" onclick="window.print()"><i class="fa-solid fa-print"></i> Cetak / Simpan PDF</button>
      </div>

      <div class="stats-grid">
        <div class="stat-card"><div class="stat-value"><?= count($projects) ?></div><div class="stat-label">Total Proyek</div></div>
        <div class="stat-card"><div class="stat-value"><?= count($users) ?></div><div class="stat-label">Total Pengguna</div></div>
        <div class="stat-card"><div class="stat-value"><?= $total_pesan ?></div><div class="stat-label">Total Pesan (<?= $pesan_baru ?> belum dibaca)</div></div>
      </div>

      <h2 class="section-title">Daftar Proyek</h2>
      <div class="panel">
        <div class="table-wrap">
          <table class="data-table">
            <thead><tr><th>No</th><th>Judul</th><th>Tag</th><th>Kategori</th><th>Progress</th><th>Dibuat</th></tr></thead>
            <tbody>
            <?php foreach ($projects as $i => $p) { ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= e($p['title']) ?></td>
                <td><?= e($p['tag']) ?></td>
                <td><?= e($p['category']) ?></td>
                <td><?= $p['progress'] === null ? '-' : (int) $p['progress'] . '%' ?></td>
                <td><?= e(date('d/m/Y', strtotime($p['created_at']))) ?></td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      </div>

      <h2 class="section-title">Daftar Pengguna</h2>
      <div class="panel">
        <div class="table-wrap">
          <table class="data-table">
            <thead><tr><th>No</th><th>Username</th><th>Email</th><th>Role</th><th>Terdaftar</th></tr></thead>
            <tbody>
            <?php foreach ($users as $i => $u) { ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= e($u['username']) ?></td>
                <td><?= e($u['email']) ?></td>
                <td><?= e($u['role']) ?></td>
                <td><?= e(date('d/m/Y', strtotime($u['created_at']))) ?></td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

<?php include 'layouts/footer.php'; ?>