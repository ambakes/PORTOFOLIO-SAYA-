<?php
// ============================================
// REGISTER.PHP — VERSI SEDERHANA
// ============================================

$pesan_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $email    = $_POST["email"];
    $password = $_POST["password"];

    if ($username == "" || $email == "" || $password == "") {
        $pesan_error = "Semua field wajib diisi!";
    } else {

        $sudah_ada = false;
        $baris_baris = file("users.txt", FILE_IGNORE_NEW_LINES);

        if ($baris_baris) {
            foreach ($baris_baris as $baris) {
                $data = explode("|", $baris);
                if ($data[0] == $username) {
                    $sudah_ada = true;
                }
            }
        }

        if ($sudah_ada) {
            $pesan_error = "Username sudah dipakai, coba yang lain!";
        } else {
            $data_baru = $username . "|" . $email . "|" . $password . "\n";
            $file = fopen("users.txt", "a");
            fwrite($file, $data_baru);
            fclose($file);

            header("Location: login.php?pesan=daftar_sukses");
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Akun — Farles</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <style>
    .auth-page {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }
    .auth-theme-toggle { position: fixed; top: 22px; right: 22px; z-index: 1200; }
    .auth-wrapper { width: 100%; max-width: 420px; position: relative; z-index: 1; }
    .auth-logo {
      text-align: center;
      font-family: var(--font-display);
      font-size: 1.6rem;
      font-weight: 800;
      letter-spacing: 2px;
      margin-bottom: 24px;
      color: var(--text-primary);
    }
    .auth-logo .dot { color: var(--gold); }
    .auth-card {
      background: var(--card-bg);
      border: 1px solid var(--border-color);
      border-radius: var(--radius-lg);
      padding: 32px 28px;
      backdrop-filter: blur(var(--blur-strong));
      box-shadow: 0 15px 40px rgba(0,0,0,0.4);
    }
    .auth-card h1 { font-family: var(--font-display); font-size: 1.5rem; margin-bottom: 8px; color: var(--text-primary); }
    .auth-card p.sub { color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 22px; font-family: var(--font-mono); }
    .auth-form-group { margin-bottom: 16px; }
    .auth-form-group label {
      display: block; font-size: 0.78rem; color: var(--text-secondary);
      margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .auth-form-group input {
      width: 100%; background: rgba(255,255,255,0.04); border: 1px solid var(--border-color);
      border-radius: 10px; padding: 12px 14px; color: var(--text-primary); font-size: 1rem;
      font-family: inherit; transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    body.light-mode .auth-form-group input { background: rgba(0,0,0,0.03); }
    .auth-form-group input:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }
    .auth-btn {
      width: 100%; background-color: var(--accent); color: #ffffff; padding: 12px; border: none;
      border-radius: 10px; font-weight: 600; font-size: 1rem; cursor: pointer; margin-top: 6px;
      font-family: inherit; transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }
    .auth-btn:hover { background-color: var(--accent-hover); box-shadow: 0 0 20px var(--accent-glow); }
    .auth-switch { text-align: center; margin-top: 18px; font-size: 0.9rem; color: var(--text-secondary); }
    .auth-switch a { color: var(--gold); text-decoration: none; font-weight: 600; }
    .auth-alert {
      background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.35);
      color: #fca5a5; padding: 10px 14px; border-radius: 10px; font-size: 0.85rem; margin-bottom: 18px;
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

  <button id="themeToggle" class="theme-toggle auth-theme-toggle" aria-label="Toggle Theme">
    <span id="themeIcon">☀️</span>
  </button>

  <div class="auth-page">
    <div class="auth-wrapper">
      <div class="auth-logo">FARLES<span class="dot">.</span></div>
      <div class="auth-card">
        <h1>Buat Akun Baru</h1>
        <p class="sub">&gt; daftar untuk mulai mengakses sistem</p>

        <?php if ($pesan_error != "") { ?>
          <div class="auth-alert"><?= htmlspecialchars($pesan_error) ?></div>
        <?php } ?>

        <form method="POST" action="register.php">
          <div class="auth-form-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="username_kamu" required>
          </div>
          <div class="auth-form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="kamu@email.com" required>
          </div>
          <div class="auth-form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Password kamu" required>
          </div>
          <button type="submit" class="auth-btn">Daftar</button>
        </form>

        <p class="auth-switch">Sudah punya akun? <a href="login.php">Login di sini</a></p>
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