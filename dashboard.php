<?php
// ============================================
// DASHBOARD.PHP — USER BIASA
// ============================================

session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

$nama_user = htmlspecialchars($_SESSION["username"]);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard — Farles</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <style>
    .auth-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
    .auth-theme-toggle { position: fixed; top: 22px; right: 22px; z-index: 1200; }
    .auth-wrapper { width: 100%; max-width: 420px; position: relative; z-index: 1; }
    .auth-logo {
      text-align: center; font-family: var(--font-display); font-size: 1.6rem;
      font-weight: 800; letter-spacing: 2px; margin-bottom: 24px; color: var(--text-primary);
    }
    .auth-logo .dot { color: var(--gold); }
    .auth-card {
      background: var(--card-bg); border: 1px solid var(--border-color); border-radius: var(--radius-lg);
      padding: 32px 28px; backdrop-filter: blur(var(--blur-strong)); box-shadow: 0 15px 40px rgba(0,0,0,0.4);
      text-align: center;
    }
    .auth-card h1 { font-family: var(--font-display); font-size: 1.5rem; margin-bottom: 8px; color: var(--text-primary); }
    .auth-card p.sub { color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 22px; font-family: var(--font-mono); }
    .auth-card p.desc { color: var(--text-secondary); font-size: 0.92rem; margin-bottom: 22px; }
    a.logout-btn {
      display: inline-flex; align-items: center; gap: 10px; justify-content: center;
      background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.35);
      color: #fca5a5; padding: 12px 26px; border-radius: 10px; font-weight: 600;
      text-decoration: none; transition: all 0.25s ease; width: 100%; box-sizing: border-box;
    }
    a.logout-btn:hover { background-color: rgba(239, 68, 68, 0.2); }

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

  <button id="themeToggle" class="theme-toggle auth-theme-toggle" aria-label="Toggle Theme">
    <span id="themeIcon">☀️</span>
  </button>

  <div class="auth-page">
    <div class="auth-wrapper">
      <div class="auth-logo">FARLES<span class="dot">.</span></div>
      <div class="auth-card">
        <h1>Halo, <?= $nama_user ?> 👋</h1>
        <p class="sub">&gt; kamu berhasil login ke sistem</p>
        <p class="desc">Ini halaman contoh setelah login. Ganti isinya sesuai kebutuhan kamu.</p>
        <a href="logout.php" class="logout-btn">Logout</a>
      </div>
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