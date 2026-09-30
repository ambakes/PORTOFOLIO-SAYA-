<?php
// ============================================
// LOGIN.PHP — versi database
// - Password dicek dengan password_verify() (hash bcrypt)
// - Admin ditentukan dari kolom users.role, bukan hardcode
// - Dibatasi 5x gagal per 10 menit per IP (anti brute force)
// ============================================
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/helpers.php';

// Sudah login? langsung arahkan
if (!empty($_SESSION['user_id'])) {
    header('Location: ' . (($_SESSION['role'] ?? '') === 'admin' ? 'dashboard_admin.php' : 'dashboard.php'));
    exit;
}

$pesan_error = '';
$pesan_info  = '';

if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] === 'daftar_sukses') $pesan_info = 'Registrasi berhasil! Silakan login.';
    if ($_GET['pesan'] === 'logout_sukses') $pesan_info = 'Kamu berhasil logout.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $ip       = client_ip();

    // Cek batas percobaan
    $st = $pdo->prepare('SELECT COUNT(*) FROM login_attempts WHERE ip = ? AND attempted_at > (NOW() - INTERVAL 10 MINUTE)');
    $st->execute([$ip]);
    if ((int) $st->fetchColumn() >= 5) {
        $pesan_error = 'Terlalu banyak percobaan gagal. Coba lagi dalam 10 menit.';
    } else {
        $st = $pdo->prepare('SELECT id, username, password_hash, role FROM users WHERE username = ? AND email = ?');
        $st->execute([$username, $email]);
        $u = $st->fetch();

        // Biaya waktu dibuat seragam (baik user ada maupun tidak)
        if ($u) {
            $ok = password_verify($password, $u['password_hash']);
        } else {
            password_hash($password, PASSWORD_DEFAULT);
            $ok = false;
        }

        if ($ok) {
            $pdo->prepare('DELETE FROM login_attempts WHERE ip = ?')->execute([$ip]);
            session_regenerate_id(true);
            $_SESSION['user_id']    = (int) $u['id'];
            $_SESSION['username']   = $u['username'];
            $_SESSION['role']       = $u['role'];
            $_SESSION['login_time'] = time();
            if ($u['role'] === 'admin') $_SESSION['is_admin'] = true;

            if (password_needs_rehash($u['password_hash'], PASSWORD_DEFAULT)) {
                $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?')
                    ->execute([password_hash($password, PASSWORD_DEFAULT), $u['id']]);
            }

            header('Location: ' . ($u['role'] === 'admin' ? 'dashboard_admin.php' : 'dashboard.php'));
            exit;
        }

        $pdo->prepare('INSERT INTO login_attempts (ip) VALUES (?)')->execute([$ip]);
        $pesan_error = 'Username, email, atau password salah!';
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login — Farles</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="auth.css" />
</head>
<body>
  <div class="sakura-container" aria-hidden="true">
    <div class="sakura"></div><div class="sakura"></div><div class="sakura"></div><div class="sakura"></div><div class="sakura"></div>
  </div>
  <div class="sparkle-field" aria-hidden="true">
    <span class="sparkle"></span><span class="sparkle"></span><span class="sparkle"></span><span class="sparkle"></span><span class="sparkle"></span><span class="sparkle"></span>
  </div>

  <button id="themeToggle" class="theme-toggle auth-theme-toggle" aria-label="Toggle Theme"><span id="themeIcon">☀️</span></button>

  <div class="auth-page">
    <div class="auth-wrapper">
      <div class="auth-logo">FARLES<span class="dot">.</span></div>
      <div class="auth-card">
        <h1>Selamat Datang</h1>
        <p class="sub">&gt; masuk untuk melanjutkan sesi kamu</p>

        <?php if ($pesan_error !== '') { ?><div class="auth-alert"><?= e($pesan_error) ?></div><?php } ?>
        <?php if ($pesan_info !== '')  { ?><div class="auth-alert auth-alert-sukses"><?= e($pesan_info) ?></div><?php } ?>

        <form method="POST" action="login.php">
          <?= csrf_field() ?>
          <div class="auth-form-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="username kamu" required>
          </div>
          <div class="auth-form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="email kamu" required>
          </div>
          <div class="auth-form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="password kamu" required>
          </div>
          <button type="submit" class="auth-btn">Login</button>
        </form>

        <p class="auth-switch">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
      </div>
    </div>
  </div>
  <script src="auth.js"></script>
</body>
</html>
