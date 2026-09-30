<?php
// ============================================
// DASHBOARD_ADMIN.PHP — angka diambil dari database
// ============================================
$active_page = 'dashboard';
$page_title  = 'Dashboard';

include 'layouts/auth.php';

$total_proyek = (int) $pdo->query('SELECT COUNT(*) FROM projects')->fetchColumn();
$total_pesan  = (int) $pdo->query('SELECT COUNT(*) FROM messages')->fetchColumn();
$pesan_baru   = (int) $pdo->query('SELECT COUNT(*) FROM messages WHERE dibaca = 0')->fetchColumn();
$total_user   = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$rata_progres = $pdo->query('SELECT AVG(progress) FROM projects WHERE progress IS NOT NULL')->fetchColumn();
$rata_progres = $rata_progres === null ? null : (int) round($rata_progres);

$pesan_terbaru = $pdo->query('SELECT id, nama, email, pesan, dibaca, created_at FROM messages ORDER BY id DESC LIMIT 5')->fetchAll();

include 'layouts/header.php';
include 'layouts/sidebar.php';
?>

    <div class="container">

      <div class="welcome-card">
        <h1>Halo, <?= $nama_admin ?> 👋</h1>
        <p>&gt; sesi admin aktif — <?= e($tanggal) ?>, pukul <?= e($jam_login) ?></p>
      </div>

      <?php flash_show(); ?>

      <h2 class="section-title">Ringkasan</h2>
      <div class="stats-grid">
        <div class="stat-card">
          <i class="fa-solid fa-diagram-project"></i>
          <div class="stat-value"><?= $total_proyek ?></div>
          <div class="stat-label">Total Proyek Ditampilkan</div>
        </div>
        <div class="stat-card">
          <i class="fa-solid fa-envelope"></i>
          <div class="stat-value"><?= $total_pesan ?></div>
          <div class="stat-label">Pesan Masuk (<?= $pesan_baru ?> belum dibaca)</div>
        </div>
        <div class="stat-card">
          <i class="fa-solid fa-users"></i>
          <div class="stat-value"><?= $total_user ?></div>
          <div class="stat-label">Pengguna Terdaftar</div>
        </div>
        <div class="stat-card">
          <i class="fa-solid fa-gamepad"></i>
          <div class="stat-value"><?= $rata_progres === null ? '-' : $rata_progres . '%' ?></div>
          <div class="stat-label">Rata-rata Progress Proyek</div>
        </div>
      </div>

      <h2 class="section-title">Aksi Cepat</h2>
      <div class="actions-grid">
        <a href="project_form.php" class="action-card">
          <i class="fa-solid fa-plus"></i>
          <div>
            <div class="action-title">Tambah Proyek</div>
            <div class="action-desc">Proyek baru langsung tampil di situs</div>
          </div>
        </a>
        <a href="projects.php" class="action-card">
          <i class="fa-solid fa-briefcase"></i>
          <div>
            <div class="action-title">Kelola Proyek</div>
            <div class="action-desc">Ubah atau hapus karya di Hall of Creations</div>
          </div>
        </a>
        <a href="messages.php" class="action-card">
          <i class="fa-solid fa-inbox"></i>
          <div>
            <div class="action-title">Cek Pesan</div>
            <div class="action-desc">Baca pesan dari form kontak</div>
          </div>
        </a>
      </div>

      <h2 class="section-title">Pesan Terbaru</h2>
      <div class="panel">
        <?php if (!$pesan_terbaru) { ?>
          <div class="empty">Belum ada pesan masuk.</div>
        <?php } else { ?>
          <div class="table-wrap">
            <table class="data-table">
              <thead><tr><th>Pengirim</th><th>Pesan</th><th>Waktu</th></tr></thead>
              <tbody>
              <?php foreach ($pesan_terbaru as $m) { ?>
                <tr>
                  <td>
                    <?= e($m['nama']) ?>
                    <?php if (!$m['dibaca']) { ?><span class="pill pill-new">baru</span><?php } ?>
                    <div class="muted" style="font-size:.78rem"><?= e($m['email']) ?></div>
                  </td>
                  <td class="muted"><?= e(mb_strimwidth($m['pesan'], 0, 90, '…')) ?></td>
                  <td class="muted"><?= e(date('d/m/Y H:i', strtotime($m['created_at']))) ?></td>
                </tr>
              <?php } ?>
              </tbody>
            </table>
          </div>
        <?php } ?>
      </div>

    </div>

<?php include 'layouts/footer.php'; ?>
