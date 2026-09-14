<?php
// ============================================
// DASHBOARD_ADMIN.PHP — ADMIN DASHBOARD
// ============================================

session_start();

if (!isset($_SESSION["username"]) || !isset($_SESSION["is_admin"])) {
    header("Location: login.php");
    exit;
}

$nama_admin = htmlspecialchars($_SESSION["username"]);
$jam_login  = date("H:i", time());
$tanggal    = date("d F Y");
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard — Farles</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="style.css" />
  <style>
    body { padding: 24px; }
    .admin-theme-toggle { position: fixed; top: 22px; right: 22px; z-index: 1200; }

    .topbar {
      max-width: 1100px; margin: 90px auto 32px; display: flex; justify-content: space-between;
      align-items: center; flex-wrap: wrap; gap: 16px; position: relative; z-index: 1;
    }
    .logo { font-family: var(--font-display); font-size: 1.4rem; font-weight: 800; letter-spacing: 2px; color: var(--text-primary); }
    .logo .dot { color: var(--gold); }
    .admin-chip {
      display: flex; align-items: center; gap: 12px; background: var(--card-bg);
      border: 1px solid var(--border-color); padding: 8px 16px 8px 8px; border-radius: 30px;
    }
    .admin-chip .avatar {
      width: 34px; height: 34px; border-radius: 50%; background: var(--accent);
      display: flex; align-items: center; justify-content: center; font-weight: 700;
      font-size: 0.9rem; color: #ffffff;
    }
    .admin-chip .info span { display: block; font-size: 0.72rem; color: var(--text-secondary); text-transform: uppercase; }
    .admin-chip .info strong { font-size: 0.9rem; color: var(--text-primary); }

    .container { max-width: 1100px; margin: 0 auto; position: relative; z-index: 1; }

    .welcome-card {
      background: var(--card-bg); border: 1px solid var(--border-color); border-radius: var(--radius-lg);
      padding: 28px 30px; margin-bottom: 28px; backdrop-filter: blur(var(--blur-strong));
      box-shadow: 0 15px 40px rgba(0,0,0,0.4);
    }
    .welcome-card h1 { font-family: var(--font-display); font-size: 1.6rem; margin-bottom: 6px; color: var(--text-primary); }
    .welcome-card p { color: var(--text-secondary); font-family: var(--font-mono); font-size: 0.88rem; }

    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; margin-bottom: 28px; }
    .stat-card {
      background: var(--card-bg); border: 1px solid var(--border-color); border-radius: var(--radius-md);
      padding: 22px; transition: transform 0.25s ease, border-color 0.25s ease; backdrop-filter: blur(var(--blur-strong));
    }
    .stat-card:hover { transform: translateY(-4px); border-color: var(--text-primary); }
    .stat-card i { font-size: 1.5rem; color: var(--gold); margin-bottom: 12px; display: block; }
    .stat-card .stat-value { font-family: var(--font-display); font-size: 1.6rem; font-weight: 700; color: var(--text-primary); }
    .stat-card .stat-label { color: var(--text-secondary); font-size: 0.82rem; margin-top: 4px; }

    .section-title { font-family: var(--font-display); font-size: 1.1rem; margin-bottom: 16px; color: var(--text-primary); }
    .actions-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 28px; }
    .action-card {
      background: var(--card-bg); border: 1px solid var(--border-color); border-radius: var(--radius-md);
      padding: 22px; text-decoration: none; color: var(--text-primary); display: flex; align-items: center;
      gap: 16px; transition: all 0.25s ease; backdrop-filter: blur(var(--blur-strong));
    }
    .action-card:hover { border-color: var(--text-primary); transform: translateY(-3px); }
    .action-card i {
      width: 42px; height: 42px; border-radius: 12px; background: var(--accent-glow);
      display: flex; align-items: center; justify-content: center; font-size: 1.1rem;
      color: var(--gold); flex-shrink: 0;
    }
    .action-card .action-title { font-weight: 600; font-size: 0.95rem; }
    .action-card .action-desc { color: var(--text-secondary); font-size: 0.8rem; margin-top: 2px; }

    .logout-wrap { text-align: center; }
    a.logout-btn {
      display: inline-flex; align-items: center; gap: 10px;
      background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.35);
      color: #fca5a5; padding: 12px 26px; border-radius: 10px; font-weight: 600;
      text-decoration: none; transition: all 0.25s ease;
    }
    a.logout-btn:hover { background-color: rgba(239, 68, 68, 0.2); }

    @media (max-width: 600px) {
      .topbar { flex-direction: column; align-items: flex-start; margin-top: 70px; }
    }

    /* PASTIKAN CURSOR NORMAL SELALU TERLIHAT DI HALAMAN INI */
    .cursor-dot, .cursor-ring { display: none !important; }
    @media (hover: hover) and (pointer: fine) {
      body, a, button, input, textarea,
      .action-card, .stat-card, .filter-btn, .service-card {
        cursor: auto !important;
      }
      a, button, .action-card, .auth-btn, .logout-btn {
        cursor: pointer !important;
      }
    }
  </style>
</head>
<body>

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

  <button id="themeToggle" class="theme-toggle admin-theme-toggle" aria-label="Toggle Theme">
    <span id="themeIcon">☀️</span>
  </button>

  <div class="topbar">
    <div class="logo">FARLES<span class="dot">.</span> ADMIN</div>
    <div class="admin-chip">
      <div class="avatar"><?= strtoupper(substr($nama_admin, 0, 1)) ?></div>
      <div class="info">
        <span>Logged in as</span>
        <strong><?= $nama_admin ?></strong>
      </div>
    </div>
  </div>

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
      <a href="index.html#works" class="action-card">
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

    <div class="logout-wrap">
      <a href="logout.php" class="logout-btn">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
      </a>
    </div>

  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const themeToggleBtn = document.getElementById('themeToggle');
      const themeIcon = document.getElementById('themeIcon');
      const updateThemeUI = (isLight) => {
        document.body.classList.toggle('light-mode', isLight);
        if (themeIcon) themeIcon.textContent = isLight ? '🌙' : '☀️';
      };
      updateThemeUI(localStorage.getItem('theme') === 'light');
      if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
          const goingLight = !document.body.classList.contains('light-mode');
          updateThemeUI(goingLight);
          localStorage.setItem('theme', goingLight ? 'light' : 'dark');
        });
      }

      // CUSTOM ANIME CURSOR
      const cursorDot = document.getElementById('cursorDot');
      const cursorRing = document.getElementById('cursorRing');
      const canHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

      if (cursorDot && cursorRing && canHover) {
        document.addEventListener('mousemove', (e) => {
          cursorDot.style.left = e.clientX + 'px';
          cursorDot.style.top = e.clientY + 'px';
          cursorRing.style.left = e.clientX + 'px';
          cursorRing.style.top = e.clientY + 'px';
        });
        document.addEventListener('mousedown', () => {
          cursorRing.style.transform = 'translate(-50%, -50%) scale(0.85)';
        });
        document.addEventListener('mouseup', () => {
          cursorRing.style.transform = 'translate(-50%, -50%) scale(1)';
        });
        const hoverSelector = 'a, button, input, textarea, .action-card, .stat-card';
        document.querySelectorAll(hoverSelector).forEach(el => {
          el.addEventListener('mouseenter', () => document.body.classList.add('cursor-hover'));
          el.addEventListener('mouseleave', () => document.body.classList.remove('cursor-hover'));
        });
      }
    });
  </script>
</body>
</html>