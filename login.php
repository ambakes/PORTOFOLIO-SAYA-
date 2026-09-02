<?php
// ============================================
// LOGIN.PHP — VERSI SEDERHANA
// Cara kerja:
// 1. Kalau ada ?pesan=... di URL (GET) -> tampilkan notifikasi
// 2. Kalau form dikirim (POST) -> cek username, email, & password ke users.txt
// ============================================

session_start(); // dipakai buat nyimpen status "sedang login"

$pesan_error = "";
$pesan_info  = "";

// --- BAGIAN GET: baca pesan dari URL, misal login.php?pesan=daftar_sukses ---
if (isset($_GET["pesan"])) {
    if ($_GET["pesan"] == "daftar_sukses") {
        $pesan_info = "Registrasi berhasil! Silakan login.";
    } elseif ($_GET["pesan"] == "logout_sukses") {
        $pesan_info = "Kamu berhasil logout.";
    }
}

// --- BAGIAN POST: proses login ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $email    = $_POST["email"];
    $password = $_POST["password"];

    $login_berhasil = false;

    // Baca semua baris di users.txt
    $baris_baris = file("users.txt", FILE_IGNORE_NEW_LINES);

    if ($baris_baris) {
        foreach ($baris_baris as $baris) {
            // format tiap baris: username|email|password
            $data = explode("|", $baris);
            $username_di_file = $data[0];
            $email_di_file    = $data[1];
            $password_di_file = $data[2];

            if ($username_di_file == $username && $email_di_file == $email && $password_di_file == $password) {
                $login_berhasil = true;
            }
        }
    }

    if ($login_berhasil) {
        // Simpan status login ke session
        $_SESSION["username"] = $username;
        header("Location: dashboard.php");
        exit;
    } else {
        $pesan_error = "Username, email, atau password salah!";
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login — Farles</title>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      min-height: 100vh;
      background-color: #050508;
      background-image: radial-gradient(circle at 50% 0%, rgba(126, 34, 206, 0.25), transparent 70%);
      color: #f0f0f0;
      font-family: "Inter", sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }
    .wrapper { width: 100%; max-width: 420px; }
    .logo {
      text-align: center;
      font-family: "Sora", sans-serif;
      font-size: 1.6rem;
      font-weight: 800;
      letter-spacing: 2px;
      margin-bottom: 24px;
    }
    .logo .dot { color: #c084fc; }
    .card {
      background: #0d0e15;
      border: 1px solid rgba(168, 85, 247, 0.15);
      border-radius: 16px;
      padding: 32px 28px;
      box-shadow: 0 15px 40px rgba(0,0,0,0.4);
    }
    .card h1 { font-family: "Sora", sans-serif; font-size: 1.5rem; margin-bottom: 8px; }
    .card p.sub {
      color: #a0a5b5;
      font-size: 0.9rem;
      margin-bottom: 22px;
      font-family: "JetBrains Mono", monospace;
    }
    .form-group { margin-bottom: 16px; }
    .form-group label {
      display: block;
      font-size: 0.78rem;
      color: #a0a5b5;
      margin-bottom: 6px;
      text-transform: uppercase;
    }
    .form-group input {
      width: 100%;
      background: rgba(255,255,255,0.04);
      border: 1px solid rgba(168, 85, 247, 0.15);
      border-radius: 10px;
      padding: 12px 14px;
      color: #f0f0f0;
      font-size: 1rem;
    }
    .form-group input:focus { outline: none; border-color: #8b34e0; }
    button {
      width: 100%;
      background-color: #8b34e0;
      color: #fff;
      padding: 12px;
      border: none;
      border-radius: 10px;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      margin-top: 6px;
    }
    button:hover { background-color: #a855f7; }
    .switch { text-align: center; margin-top: 18px; font-size: 0.9rem; color: #a0a5b5; }
    .switch a { color: #c084fc; text-decoration: none; font-weight: 600; }
    .alert {
      background: rgba(239, 68, 68, 0.08);
      border: 1px solid rgba(239, 68, 68, 0.35);
      color: #fca5a5;
      padding: 10px 14px;
      border-radius: 10px;
      font-size: 0.85rem;
      margin-bottom: 18px;
    }
    .alert-sukses {
      background: rgba(34, 197, 94, 0.08);
      border: 1px solid rgba(34, 197, 94, 0.35);
      color: #86efac;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="logo">FARLES<span class="dot">.</span></div>
    <div class="card">
      <h1>Selamat Datang</h1>
      <p class="sub">&gt; masuk untuk melanjutkan sesi kamu</p>

      <?php if ($pesan_error != "") { ?>
        <div class="alert"><?= $pesan_error ?></div>
      <?php } ?>

      <?php if ($pesan_info != "") { ?>
        <div class="alert alert-sukses"><?= $pesan_info ?></div>
      <?php } ?>

      <!-- FORM DIKIRIM PAKAI METHOD POST -->
      <form method="POST" action="login.php">
        <div class="form-group">
          <label>Username</label>
          <input type="text" name="username" placeholder="username kamu" required>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" placeholder="email kamu" required>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" placeholder="password kamu" required>
        </div>
        <button type="submit">Login</button>
      </form>

      <p class="switch">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
  </div>
</body>
</html>