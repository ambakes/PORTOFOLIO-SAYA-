<?php
// ============================================
// DASHBOARD.PHP — VERSI SEDERHANA
// Kalau belum login (belum ada session username) -> tendang ke login.php
// ============================================

session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard — Farles</title>
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
    .card p.desc { color: #a0a5b5; font-size: 0.92rem; margin-bottom: 22px; }
    a.logout-btn {
      display: block;
      text-align: center;
      background-color: #8b34e0;
      color: #fff;
      padding: 12px;
      border-radius: 10px;
      font-weight: 600;
      text-decoration: none;
    }
    a.logout-btn:hover { background-color: #a855f7; }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="logo">FARLES<span class="dot">.</span></div>
    <div class="card">
      <h1>Halo, <?= htmlspecialchars($_SESSION["username"]) ?> 👋</h1>
      <p class="sub">&gt; kamu berhasil login ke sistem</p>
      <p class="desc">Ini halaman contoh setelah login. Ganti isinya sesuai kebutuhan kamu.</p>
      <a href="logout.php" class="logout-btn">Logout</a>
    </div>
  </div>
</body>
</html>