  <!-- CUSTOM ANIME CURSOR -->
  <div class="cursor-dot" id="cursorDot" aria-hidden="true"></div>
  <div class="cursor-ring" id="cursorRing" aria-hidden="true"></div>

  <div class="sakura-container" aria-hidden="true">
    <div class="sakura"></div>
    <div class="sakura"></div>
    <div class="sakura"></div>
    <div class="sakura"></div>
    <div class="sakura"></div>
  </div>

  <div class="sparkle-field" aria-hidden="true">
    <span class="sparkle"></span>
    <span class="sparkle"></span>
    <span class="sparkle"></span>
    <span class="sparkle"></span>
    <span class="sparkle"></span>
    <span class="sparkle"></span>
  </div>

  <!-- TOMBOL SIDEBAR (MOBILE) -->
  <button id="sidebarToggle" class="sidebar-toggle" aria-label="Buka menu">
    <i class="fa-solid fa-bars"></i>
  </button>
  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <button id="themeToggle" class="theme-toggle admin-theme-toggle" aria-label="Toggle Theme">
    <span id="themeIcon">☀️</span>
  </button>

  <!-- ================= SIDEBAR ================= -->
  <aside class="sidebar" id="sidebar" aria-label="Menu admin">
    <div class="logo">
      FARLES<span class="dot">.</span>
      <small>ADMIN PANEL</small>
    </div>

    <div class="nav-label">Menu</div>
    <nav class="side-nav">
      <a href="dashboard_admin.php" class="side-link active">
        <i class="fa-solid fa-gauge-high"></i> Dashboard
      </a>
      <a href="index.html" class="side-link">
        <i class="fa-solid fa-house"></i> Lihat Portofolio
      </a>
      <a href="projects.php" class="side-link">
        <i class="fa-solid fa-briefcase"></i> Kelola Proyek
      </a>
      <a href="mailto:jhonatanfarles@gmail.com" class="side-link">
        <i class="fa-solid fa-inbox"></i> Cek Pesan
      </a>
    </nav>

    <div class="sidebar-footer">
      <div class="admin-chip">
        <div class="avatar"><?= strtoupper(substr($nama_admin, 0, 1)) ?></div>
        <div class="info">
          <span>Logged in as</span>
          <strong><?= $nama_admin ?></strong>
        </div>
      </div>
      <a href="logout.php" class="logout-btn">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
      </a>
    </div>
  </aside>

  <!-- ================= KONTEN UTAMA ================= -->
  <main class="main">