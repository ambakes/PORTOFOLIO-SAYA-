<?php
// ============================================
// REGISTER.PHP — versi database
// - Password disimpan sebagai hash (password_hash)
// - Semua akun baru otomatis role "user"
// ============================================
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/helpers.php';

$pesan_error = '';
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $email === '' || $password === '') {
        $pesan_error = 'Semua field wajib diisi!';
    } elseif (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $username)) {
        $pesan_error = 'Username 3–30 karakter, hanya huruf, angka, dan underscore (_).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
        $pesan_error = 'Format email tidak valid.';
    } elseif (strlen($password) < 8) {
        $pesan_error = 'Password minimal 8 karakter.';
    } else {
        $st = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ? OR email = ?');
        $st->execute([$username, $email]);
        if ((int) $st->fetchColumn() > 0) {
            $pesan_error = 'Username atau email sudah dipakai, coba yang lain!';
        } else {
            try {
                $pdo->prepare('INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, "user")')
                    ->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);
                header('Location: login.php?pesan=daftar_sukses');
                exit;
            } catch (PDOException $ex) {
                $pesan_error = ($ex->getCode() === '23000')
                    ? 'Username atau email sudah dipakai, coba yang lain!'
                    : 'Gagal menyimpan data. Coba lagi.';
            }
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
        <h1>Buat Akun Baru</h1>
        <p class="sub">&gt; daftar untuk mulai mengakses sistem</p>

        <?php if ($pesan_error !== '') { ?><div class="auth-alert"><?= e($pesan_error) ?></div><?php } ?>

        <form method="POST" action="register.php">
          <?= csrf_field() ?>
          <div class="auth-form-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="username_kamu" value="<?= e($username) ?>" required>
            <small>3–30 karakter: huruf, angka, underscore.</small>
          </div>
          <div class="auth-form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="kamu@email.com" value="<?= e($email) ?>" required>
          </div>
          <div class="auth-form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Minimal 8 karakter" minlength="8" required>
          </div>
          <button type="submit" class="auth-btn">Daftar</button>
        </form>

        <p class="auth-switch">Sudah punya akun? <a href="login.php">Login di sini</a></p>
      </div>
    </div>
  </div>
  <script src="auth.js"></script>
</body>
</html>
