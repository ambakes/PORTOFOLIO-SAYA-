<?php
// ============================================
// DASHBOARD.PHP — USER BIASA (versi database)
// Isi: profil, ubah password, daftar proyek, kirim pesan ke admin
// ============================================
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/helpers.php';

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$st = $pdo->prepare('SELECT id, username, email, password_hash, role, created_at FROM users WHERE id = ?');
$st->execute([$_SESSION['user_id']]);
$me = $st->fetch();

if (!$me) {                        // akun sudah dihapus admin
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}
if ($me['role'] === 'admin') {     // admin punya dashboard sendiri
    header('Location: dashboard_admin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $aksi = $_POST['aksi'] ?? '';

    if ($aksi === 'password') {
        $lama  = $_POST['password_lama'] ?? '';
        $baru  = $_POST['password_baru'] ?? '';
        $konf  = $_POST['password_konfirmasi'] ?? '';
        $ip    = client_ip();

        $c = $pdo->prepare('SELECT COUNT(*) FROM login_attempts WHERE ip = ? AND attempted_at > (NOW() - INTERVAL 10 MINUTE)');
        $c->execute([$ip]);

        if ((int) $c->fetchColumn() >= 5) {
            flash('Terlalu banyak percobaan gagal. Coba lagi dalam 10 menit.', 'err');
        } elseif (!password_verify($lama, $me['password_hash'])) {
            $pdo->prepare('INSERT INTO login_attempts (ip) VALUES (?)')->execute([$ip]);
            flash('Password lama salah.', 'err');
        } elseif (strlen($baru) < 8) {
            flash('Password baru minimal 8 karakter.', 'err');
        } elseif ($baru !== $konf) {
            flash('Konfirmasi password baru tidak sama.', 'err');
        } elseif ($baru === $lama) {
            flash('Password baru harus berbeda dari yang lama.', 'err');
        } else {
            $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?')
                ->execute([password_hash($baru, PASSWORD_DEFAULT), $me['id']]);
            session_regenerate_id(true);
            flash('Password berhasil diubah.');
        }

    } elseif ($aksi === 'pesan') {
        $pesan = trim($_POST['pesan'] ?? '');

        if (!empty($_SESSION['last_contact']) && time() - $_SESSION['last_contact'] < 30) {
            flash('Tunggu sebentar sebelum mengirim pesan lagi.', 'err');
        } elseif ($pesan === '' || mb_strlen($pesan) > 3000) {
            flash('Pesan wajib diisi (maks. 3000 karakter).', 'err');
        } else {
            $pdo->prepare('INSERT INTO messages (nama, email, pesan) VALUES (?, ?, ?)')
                ->execute([$me['username'], $me['email'], $pesan]);
            $_SESSION['last_contact'] = time();
            flash('Pesan terkirim ke admin. Terima kasih!');
        }
    }

    header('Location: dashboard.php');
    exit;
}

$projects = $pdo->query('SELECT title, tag, description, img, progress FROM projects ORDER BY id ASC')->fetchAll();
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="auth.css" />
  <style>
    .u-wrap { max-width: 1050px; margin: 0 auto; padding: 24px 20px 60px; position: relative; z-index: 1; }
    .u-nav { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 26px; }
    .u-nav .auth-logo { margin: 0; }
    .u-nav-links { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .u-btn { display: inline-flex; align-items: center; gap: 8px; padding: 9px 16px; border-radius: 10px; border: 1px solid var(--border-color); background: transparent; color: var(--text-primary); font-family: inherit; font-weight: 600; font-size: 0.88rem; text-decoration: none; }
    .u-btn:hover { background: rgba(168,85,247,0.12); }
    .u-btn-primary { background: var(--accent); color: #fff; border-color: transparent; }
    .u-btn-primary:hover { background: var(--accent-hover); box-shadow: 0 0 16px var(--accent-glow); }
    .u-btn-danger { color: #fca5a5; border-color: rgba(239,68,68,0.4); background: rgba(239,68,68,0.1); }
    body.light-mode .u-btn-danger { color: #991b1b; }
    .u-theme { background: transparent; border: 1px solid var(--border-color); border-radius: 10px; padding: 8px 12px; font-size: 1rem; }

    .u-hello { margin-bottom: 22px; }
    .u-hello h1 { font-family: var(--font-display); font-size: 1.7rem; color: var(--text-primary); margin-bottom: 4px; }
    .u-hello p { color: var(--text-secondary); font-family: var(--font-mono); font-size: 0.88rem; }

    .u-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px; }
    .u-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 24px; backdrop-filter: blur(var(--blur-strong)); }
    .u-card.full { grid-column: 1 / -1; }
    .u-card h2 { font-family: var(--font-display); font-size: 1.05rem; color: var(--text-primary); margin-bottom: 16px; display: flex; align-items: center; gap: 10px; }
    .u-card h2 i { color: var(--gold); }

    .u-row { display: flex; justify-content: space-between; gap: 12px; padding: 10px 0; border-bottom: 1px solid var(--border-color); font-size: 0.92rem; }
    .u-row:last-child { border-bottom: none; }
    .u-row span:first-child { color: var(--text-secondary); }
    .u-row span:last-child { color: var(--text-primary); font-weight: 600; word-break: break-all; text-align: right; }

    .u-field { margin-bottom: 14px; }
    .u-field label { display: block; font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-secondary); margin-bottom: 6px; }
    .u-field input, .u-field textarea { width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); border-radius: 10px; padding: 11px 13px; color: var(--text-primary); font-family: inherit; font-size: 0.95rem; }
    body.light-mode .u-field input, body.light-mode .u-field textarea { background: rgba(0,0,0,0.03); }
    .u-field input:focus, .u-field textarea:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }

    .alert { padding: 11px 14px; border-radius: 10px; font-size: 0.88rem; margin-bottom: 18px; }
    .alert-ok  { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.4); color: #86efac; }
    .alert-err { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.4); color: #fca5a5; }
    body.light-mode .alert-ok { color: #166534; } body.light-mode .alert-err { color: #991b1b; }

    .u-projects { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 16px; }
    .u-proj { border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden; background: rgba(255,255,255,0.03); }
    .u-proj img { width: 100%; height: 130px; object-fit: cover; display: block; }
    .u-proj-body { padding: 14px; }
    .u-proj-tag { font-size: 0.72rem; color: var(--gold); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    .u-proj h3 { font-size: 0.98rem; color: var(--text-primary); margin: 4px 0 6px; }
    .u-proj p { font-size: 0.82rem; color: var(--text-secondary); }
    .u-bar { height: 6px; background: rgba(255,255,255,0.1); border-radius: 99px; overflow: hidden; margin-top: 10px; }
    body.light-mode .u-bar { background: rgba(0,0,0,0.1); }
    .u-bar span { display: block; height: 100%; background: var(--accent); }
    .u-muted { color: var(--text-secondary); font-size: 0.85rem; }

    @media (max-width: 760px) { .u-grid { grid-template-columns: 1fr; } }
  </style>
</head>
<body>
  <div class="sakura-container" aria-hidden="true">
    <div class="sakura"></div><div class="sakura"></div><div class="sakura"></div><div class="sakura"></div><div class="sakura"></div>
  </div>

  <div class="u-wrap">
    <div class="u-nav">
      <div class="auth-logo">FARLES<span class="dot">.</span></div>
      <div class="u-nav-links">
        <a href="index.php" class="u-btn"><i class="fa-solid fa-house"></i> Lihat Situs</a>
        <button id="themeToggle" class="u-theme" aria-label="Toggle Theme" type="button"><span id="themeIcon">☀️</span></button>
        <a href="logout.php" class="u-btn u-btn-danger"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
      </div>
    </div>

    <div class="u-hello">
      <h1>Halo, <?= e($me['username']) ?> 👋</h1>
      <p>&gt; kamu berhasil login ke sistem</p>
    </div>

    <?php flash_show(); ?>

    <div class="u-grid">
      <!-- PROFIL -->
      <div class="u-card">
        <h2><i class="fa-solid fa-user"></i> Profil Akun</h2>
        <div class="u-row"><span>Username</span><span><?= e($me['username']) ?></span></div>
        <div class="u-row"><span>Email</span><span><?= e($me['email']) ?></span></div>
        <div class="u-row"><span>Role</span><span><?= e($me['role']) ?></span></div>
        <div class="u-row"><span>Terdaftar sejak</span><span><?= e(tgl_id(strtotime($me['created_at']))) ?></span></div>
      </div>

      <!-- UBAH PASSWORD -->
      <div class="u-card">
        <h2><i class="fa-solid fa-key"></i> Ubah Password</h2>
        <form method="POST" action="dashboard.php">
          <?= csrf_field() ?>
          <input type="hidden" name="aksi" value="password">
          <div class="u-field">
            <label for="password_lama">Password lama</label>
            <input type="password" id="password_lama" name="password_lama" autocomplete="current-password" required>
          </div>
          <div class="u-field">
            <label for="password_baru">Password baru</label>
            <input type="password" id="password_baru" name="password_baru" minlength="8" autocomplete="new-password" placeholder="Minimal 8 karakter" required>
          </div>
          <div class="u-field">
            <label for="password_konfirmasi">Ulangi password baru</label>
            <input type="password" id="password_konfirmasi" name="password_konfirmasi" minlength="8" autocomplete="new-password" required>
          </div>
          <button type="submit" class="u-btn u-btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Password</button>
        </form>
      </div>

      <!-- KIRIM PESAN -->
      <div class="u-card full">
        <h2><i class="fa-solid fa-envelope"></i> Kirim Pesan ke Admin</h2>
        <p class="u-muted" style="margin-bottom:14px">Pesan dikirim atas nama <?= e($me['username']) ?> (<?= e($me['email']) ?>).</p>
        <form method="POST" action="dashboard.php">
          <?= csrf_field() ?>
          <input type="hidden" name="aksi" value="pesan">
          <div class="u-field">
            <label for="pesan">Pesan</label>
            <textarea id="pesan" name="pesan" rows="4" maxlength="3000" placeholder="Tulis pesan kamu di sini..." required></textarea>
          </div>
          <button type="submit" class="u-btn u-btn-primary"><i class="fa-solid fa-paper-plane"></i> Kirim Pesan</button>
        </form>
      </div>

      <!-- DAFTAR PROYEK -->
      <div class="u-card full">
        <h2><i class="fa-solid fa-briefcase"></i> Daftar Proyek</h2>
        <?php if (!$projects) { ?>
          <p class="u-muted">Belum ada proyek yang ditampilkan.</p>
        <?php } else { ?>
          <div class="u-projects">
            <?php foreach ($projects as $p) { ?>
              <div class="u-proj">
                <img src="<?= e($p['img']) ?>" alt="<?= e($p['title']) ?>">
                <div class="u-proj-body">
                  <span class="u-proj-tag"><?= e($p['tag']) ?></span>
                  <h3><?= e($p['title']) ?></h3>
                  <p><?= e(mb_strimwidth($p['description'], 0, 110, '…')) ?></p>
                  <?php if ($p['progress'] !== null) { ?>
                    <div class="u-bar"><span style="width: <?= (int) $p['progress'] ?>%"></span></div>
                    <span class="u-muted" style="font-size:.75rem">Progress: <?= (int) $p['progress'] ?>%</span>
                  <?php } ?>
                </div>
              </div>
            <?php } ?>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>

  <script src="auth.js"></script>
</body>
</html>