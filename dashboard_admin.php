<?php
// ============================================
// DASHBOARD_ADMIN.PHP
// ============================================
$active_page = 'dashboard';

include 'layouts/auth.php';
include 'layouts/header.php';
include 'layouts/sidebar.php';
?>

    <div class="container">

      <div class="welcome-card">
        <h1>Halo, <?= $nama_admin ?> 👋</h1>
        <p>&gt; sesi admin aktif — <?= $tanggal ?>, pukul <?= $jam_login ?></p>
      </div>

      <h2 class="section-title">Ringkasan</h2>
      <div class="stats-grid">
        <div class="stat-card">
          <i class="fa-solid fa-diagram-project"></i>
          <div class="stat-value">3</div>
          <div class="stat-label">Total Proyek Ditampilkan</div>
        </div>
        <div class="stat-card">
          <i class="fa-solid fa-envelope"></i>
          <div class="stat-value">0</div>
          <div class="stat-label">Pesan Masuk (via form)</div>
        </div>
        <div class="stat-card">
          <i class="fa-solid fa-gamepad"></i>
          <div class="stat-value">10%</div>
          <div class="stat-label">Progress Misteri Ambaruwoo</div>
        </div>
        <div class="stat-card">
          <i class="fa-solid fa-shield-halved"></i>
          <div class="stat-value">Admin</div>
          <div class="stat-label">Role Akun Aktif</div>
        </div>
      </div>

      <h2 class="section-title">Aksi Cepat</h2>
      <div class="actions-grid">
        <a href="index.html" class="action-card">
          <i class="fa-solid fa-house"></i>
          <div>
            <div class="action-title">Lihat Portofolio</div>
            <div class="action-desc">Buka halaman utama situs</div>
          </div>
        </a>
        <a href="projects.php" class="action-card">
          <i class="fa-solid fa-briefcase"></i>
          <div>
            <div class="action-title">Kelola Proyek</div>
            <div class="action-desc">Lihat daftar karya di Hall of Creations</div>
          </div>
        </a>
        <a href="mailto:jhonatanfarles@gmail.com" class="action-card">
          <i class="fa-solid fa-inbox"></i>
          <div>
            <div class="action-title">Cek Pesan</div>
            <div class="action-desc">Buka email untuk pesan dari form kontak</div>
          </div>
        </a>
      </div>

    </div>

<?php
include 'layouts/footer.php';
?>