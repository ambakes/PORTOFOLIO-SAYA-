<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Panel — Farles</title>
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

    /* ================= KELOLA PROYEK ================= */
    .projects-page-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; margin-bottom: 22px; }
    .projects-note {
      background: var(--card-bg); border: 1px dashed var(--border-color); border-radius: var(--radius-md);
      padding: 14px 18px; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 26px; line-height: 1.6;
    }
    .projects-manage-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 22px; }
    .manage-project-card {
      background: var(--card-bg); border: 1px solid var(--border-color); border-radius: var(--radius-md);
      overflow: hidden; backdrop-filter: blur(var(--blur-strong)); transition: all 0.25s ease;
    }
    .manage-project-card:hover { transform: translateY(-4px); border-color: var(--text-primary); }
    .manage-project-thumb { width: 100%; height: 170px; overflow: hidden; }
    .manage-project-thumb img {
      width: 100%; height: 100%; object-fit: cover; filter: grayscale(20%);
      transition: transform 0.5s ease, filter 0.5s ease;
    }
    .manage-project-card:hover .manage-project-thumb img { transform: scale(1.05); filter: grayscale(0%); }
    .manage-project-body { padding: 18px 20px 20px; }
    .manage-project-tag { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1px; color: var(--gold); font-weight: 700; }
    .manage-project-title { font-family: var(--font-display); font-size: 1.1rem; color: var(--text-primary); margin: 6px 0 8px; }
    .manage-project-desc { font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 14px; line-height: 1.55; }
    .manage-project-progress-label { font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 6px; display: block; }
    .manage-project-progress-bar {
      width: 100%; height: 6px; background: rgba(255,255,255,0.08); border-radius: 20px;
      overflow: hidden; margin-bottom: 16px;
    }
    body.light-mode .manage-project-progress-bar { background: rgba(0,0,0,0.08); }
    .manage-project-progress-fill { height: 100%; background: linear-gradient(90deg, var(--accent), var(--gold)); border-radius: 20px; }
    .manage-project-actions { display: flex; gap: 10px; }
    .manage-project-actions a {
      flex: 1; text-align: center; padding: 9px 12px; border-radius: 8px; font-size: 0.82rem; font-weight: 600;
      text-decoration: none; border: 1px solid var(--border-color); color: var(--text-primary); transition: all 0.2s ease;
    }
    .manage-project-actions a:hover { border-color: var(--accent); color: var(--gold); }
    .manage-project-actions a.primary { background: var(--accent); color: #ffffff; border-color: var(--accent); }
    .manage-project-actions a.primary:hover { background: var(--accent-hover); color: #ffffff; }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 900px) {
      .sidebar { transform: translateX(-100%); }
      .sidebar.open { transform: translateX(0); box-shadow: 10px 0 40px rgba(0,0,0,0.5); }
      .sidebar-overlay.show { display: block; }
      .sidebar-toggle { display: flex; align-items: center; justify-content: center; }
      .main { margin-left: 0; }
    }

    /* PASTIKAN CURSOR NORMAL SELALU TERLIHAT DI HALAMAN ADMIN */
    .cursor-dot, .cursor-ring { display: none !important; }
    @media (hover: hover) and (pointer: fine) {
      body, a, button, input, textarea,
      .action-card, .stat-card, .filter-btn, .service-card, .manage-project-card {
        cursor: auto !important;
      }
      a, button, .action-card, .auth-btn, .logout-btn, .side-link, .manage-project-actions a {
        cursor: pointer !important;
      }
    }
  </style>
</head>
<body>