<?php
// ============================================
// REGISTER.PHP — VERSI SEDERHANA
// Cara kerja:
// 1. Kalau method GET -> cuma tampilkan form
// 2. Kalau method POST -> ambil data dari form, simpan ke users.txt
// ============================================

$pesan_error = ""; // tempat nampung pesan error

// Cek apakah form baru saja dikirim (method POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Ambil data dari form pakai $_POST
    $username = $_POST["username"];
    $email    = $_POST["email"];
    $password = $_POST["password"];

    // Validasi sederhana
    if ($username == "" || $email == "" || $password == "") {
        $pesan_error = "Semua field wajib diisi!";
    } else {

        // Cek apakah username sudah ada di file users.txt
        $sudah_ada = false;
        $baris_baris = file("users.txt", FILE_IGNORE_NEW_LINES); // baca semua baris jadi array

        if ($baris_baris) {
            foreach ($baris_baris as $baris) {
                // format tiap baris: username|email|password
                $data = explode("|", $baris);
                if ($data[0] == $username) {
                    $sudah_ada = true;
                }
            }
        }

        if ($sudah_ada) {
            $pesan_error = "Username sudah dipakai, coba yang lain!";
        } else {
            // Simpan data baru ke file users.txt (mode "a" = append/nambah baris baru)
            $data_baru = $username . "|" . $email . "|" . $password . "\n";
            $file = fopen("users.txt", "a");
            fwrite($file, $data_baru);
            fclose($file);

            // Redirect ke login.php sambil kirim pesan lewat GET
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
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="logo">FARLES<span class="dot">.</span></div>
    <div class="card">
      <h1>Buat Akun Baru</h1>
      <p class="sub">&gt; daftar untuk mulai mengakses sistem</p>

      <?php if ($pesan_error != "") { ?>
        <div class="alert"><?= $pesan_error ?></div>
      <?php } ?>

      <!-- FORM DIKIRIM PAKAI METHOD POST -->
      <form method="POST" action="register.php">
        <div class="form-group">
          <label>Username</label>
          <input type="text" name="username" placeholder="username_kamu" required>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" placeholder="kamu@email.com" required>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" placeholder="Password kamu" required>
        </div>
        <button type="submit">Daftar</button>
      </form>

      <p class="switch">Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
  </div>
</body>
</html>