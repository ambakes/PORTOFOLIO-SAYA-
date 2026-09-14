<?php
// ============================================
// DASHBOARD_ADMIN.PHP — ADMIN DASHBOARD
// Kalau belum login (belum ada session username/admin) -> tendang ke login.php
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
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      min-height: 100vh;
      background-color: #050508;
      background-image: radial-gradient(circle at 50% 0%, rgba(126, 34, 206, 0.25), transparent 70%);
      color: #f0f0f0;
      font-family: "Inter", sans-serif;
      padding: 24px;
    }

    /* TOPBAR */
    .topbar {
      max-width: 1100px;
      margin: 0 auto 32px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 16px;
    }
    .logo {
      font-family: "Sora", sans-serif;
      font-size: 1.4rem;
      font-weight: 800;
      letter-spacing: 2px;
    }
    .logo .dot { color: #c084fc; }
    .admin-chip {
      display: flex;
      align-items: center;
      gap: 12px;
      background: rgba(22, 20, 35, 0.6);
      border: 1px solid rgba(168, 85, 247, 0.15);
      padding: 8px 16px 8px 8px;
      border-radius: 30px;
    }
    .admin-chip .avatar {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      background: #8b34e0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 0.9rem;
    }
    .admin-chip .info span {
      display: block;
      font-size: 0.72rem;
      color: #a0a5b5;
      text-transform: uppercase;
    }
    .admin-chip .info strong { font-size: 0.9rem; }

    /* CONTAINER */
    .container { max-width: 1100px; margin: 0 auto; }

    .welcome-card {
      background: #0d0e15;
      border: 1px solid rgba(168, 85, 247, 0.15);
      border-radius: 16px;
      padding: 28px 30px;
      margin-bottom: 28px;
      box-shadow: 0 15px 40px rgba(0,0,0,0.4);
    }
    .welcome-card h1 {
      font-family: "Sora", sans-serif;
      font-size: 1.6rem;
      margin-bottom: 6px;
    }
    .welcome-card p {
      color: #a0a5b5;
      font-family: "JetBrains Mono", monospace;
      font-size: 0.88rem;
    }

    /* STATS GRID */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 18px;
      margin-bottom: 28px;
    }
    .stat-card {
      background: rgba(22, 20, 35, 0.6);
      border: 1px solid rgba(168, 85, 247, 0.15);
      border-radius: 14px;
      padding: 22px;
      transition: transform 0.25s ease, border-color 0.25s ease;
    }
    .stat-card:hover { transform: translateY(-4px); border-color: #a855f7; }
    .stat-card i {
      font-size: 1.5rem;
      color: #c084fc;
      margin-bottom: 12px;
      display: block;
    }
    .stat-card .stat-value {
      font-family: "Sora", sans-serif;
      font-size: 1.6rem;
      font-weight: 700;
    }
    .stat-card .stat-label {
      color: #a0a5b5;
      font-size: 0.82rem;
      margin-top: 4px;
    }

    /* QUICK ACTIONS */
    .section-title {
      font-family: "Sora", sans-serif;
      font-size: 1.1rem;
      margin-bottom: 16px;
      color: #f0f0f0;
    }
    .actions-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 18px;
      margin-bottom: 28px;
    }
    .action-card {
      background: rgba(22, 20, 35, 0.6);
      border: 1px solid rgba(168, 85, 247, 0.15);
      border-radius: 14px;
      padding: 22px;
      text-decoration: none;
      color: #f0f0f0;
      display: flex;
      align-items: center;
      gap: 16px;
      transition: all 0.25s ease;
    }
    .action-card:hover {
      border-color: #a855f7;
      background: rgba(139, 52, 224, 0.12);
      transform: translateY(-3px);
    }
    .action-card i {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      background: rgba(139, 52, 224, 0.15);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
      color: #c084fc;
      flex-shrink: 0;
    }
    .action-card .action-title { font-weight: 600; font-size: 0.95rem; }
    .action-card .action-desc { color: #a0a5b5; font-size: 0.8rem; margin-top: 2px; }

    /* LOGOUT */
    .logout-wrap { text-align: center; }
    a.logout-btn {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      background-color: rgba(239, 68, 68, 0.1);
      border: 1px solid rgba(239, 68, 68, 0.35);
      color: #fca5a5;
      padding: 12px 26px;
      border-radius: 10px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.25s ease;
    }
    a.logout-btn:hover { background-color: rgba(239, 68, 68, 0.2); }

    @media (max-width: 600px) {
      .topbar { flex-direction: column; align-items: flex-start; }
    }
  </style>
</head>
<body>

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

</body>
</html>