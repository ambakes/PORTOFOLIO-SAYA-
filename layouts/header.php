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
    :root { --sidebar-w: 260px; }
    body { padding: 0; }
    .admin-theme-toggle { position: fixed; top: 22px; right: 22px; z-index: 1200; }

    /* ================= SIDEBAR ================= */
    .sidebar {
      position: fixed; top: 0; left: 0; bottom: 0; width: var(--sidebar-w);
      background: var(--card-bg); border-right: 1px solid var(--border-color);
      backdrop-filter: blur(var(--blur-strong));
      display: flex; flex-direction: column; padding: 26px 18px 20px;
      z-index: 1100; transition: transform 0.3s ease;
    }
    .sidebar .logo {
      font-family: var(--font-display); font-size: 1.3rem; font-weight: 800;
      letter-spacing: 2px; color: var(--text-primary); padding: 0 10px 22px;
      border-bottom: 1px solid var(--border-color); margin-bottom: 18px;
    }
    .sidebar .logo .dot { color: var(--gold); }
    .sidebar .logo small {
      display: block; font-family: var(--font-mono); font-size: 0.68rem;
      letter-spacing: 1px; color: var(--text-secondary); font-weight: 400; margin-top: 4px;
    }

    .nav-label {
      font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1.2px;
      color: var(--text-secondary); padding: 0 12px; margin: 14px 0 8px;
    }
    .side-nav { display: flex; flex-direction: column; gap: 4px; }
    .side-link {
      display: flex; align-items: center; gap: 14px; padding: 11px 12px;
      border-radius: 10px; color: var(--text-secondary); text-decoration: none;
      font-size: 0.92rem; font-weight: 500; border: 1px solid transparent;
      transition: all 0.2s ease;
    }
    .side-link i { width: 20px; text-align: center; font-size: 1rem; }
    .side-link:hover { color: var(--text-primary); background: var(--accent-glow); }
    .side-link.active {
      color: var(--text-primary); background: var(--accent-glow);
      border-color: var(--border-color);
    }
    .side-link.active i { color: var(--gold); }

    .sidebar-footer { margin-top: auto; padding-top: 16px; border-top: 1px solid var(--border-color); }
    .admin-chip {
      display: flex; align-items: center; gap: 12px; padding: 6px 8px; margin-bottom: 12px;
    }
    .admin-chip .avatar {
      width: 38px; height: 38px; border-radius: 50%; background: var(--accent);
      display: flex; align-items: center; justify-content: center; font-weight: 700;
      font-size: 0.95rem; color: #ffffff; flex-shrink: 0;
    }
    .admin-chip .info { min-width: 0; }
    .admin-chip .info span { display: block; font-size: 0.7rem; color: var(--text-secondary); text-transform: uppercase; }
    .admin-chip .info strong {
      font-size: 0.9rem; color: var(--text-primary); display: block;
      overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    a.logout-btn {
      display: flex; align-items: center; justify-content: center; gap: 10px;
      background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.35);
      color: #fca5a5; padding: 11px 16px; border-radius: 10px; font-weight: 600;
      font-size: 0.9rem; text-decoration: none; transition: all 0.25s ease;
    }
    a.logout-btn:hover { background-color: rgba(239, 68, 68, 0.2); }

    .sidebar-toggle {
      display: none; position: fixed; top: 22px; left: 22px; z-index: 1200;
      width: 44px; height: 44px; border-radius: 12px; border: 1px solid var(--border-color);
      background: var(--card-bg); color: var(--text-primary); font-size: 1.1rem;
      backdrop-filter: blur(var(--blur-strong));
    }
    .sidebar-overlay {
      display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.55); z-index: 1050;
    }

    /* ================= MAIN ================= */
    .main { margin-left: var(--sidebar-w); padding: 24px; position: relative; z-index: 1; }
    .container { max-width: 1100px; margin: 70px auto 0; position: relative; z-index: 1; }

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

    /* ================= RESPONSIVE ================= */
    @media (max-width: 900px) {
      .sidebar { transform: translateX(-100%); }
      .sidebar.open { transform: translateX(0); box-shadow: 10px 0 40px rgba(0,0,0,0.5); }
      .sidebar-overlay.show { display: block; }
      .sidebar-toggle { display: flex; align-items: center; justify-content: center; }
      .main { margin-left: 0; }
    }

    /* PASTIKAN CURSOR NORMAL SELALU TERLIHAT DI HALAMAN INI */
    .cursor-dot, .cursor-ring { display: none !important; }
    @media (hover: hover) and (pointer: fine) {
      body, a, button, input, textarea,
      .action-card, .stat-card, .filter-btn, .service-card {
        cursor: auto !important;
      }
      a, button, .action-card, .auth-btn, .logout-btn, .side-link {
        cursor: pointer !important;
      }
    }
  </style>
</head>
<body>
