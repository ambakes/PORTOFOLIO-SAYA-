<?php
$page_title = $page_title ?? 'Admin';
?><!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($page_title) ?> — Farles Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="style.css" />
  <style>
    .cursor-dot, .cursor-ring { display: none !important; }
    body, a, button, input, select, textarea { cursor: auto !important; }
    a, button, select, label.file-label { cursor: pointer !important; }

    .admin-shell { display: flex; min-height: 100vh; }
    .admin-sidebar {
      width: 250px; flex-shrink: 0; padding: 24px 14px; position: sticky; top: 0; height: 100vh;
      display: flex; flex-direction: column; gap: 4px; overflow-y: auto;
      background: var(--card-bg); border-right: 1px solid var(--border-color);
      backdrop-filter: blur(var(--blur-strong));
    }
    .side-logo { font-family: var(--font-display); font-weight: 800; font-size: 1.4rem; letter-spacing: 2px; padding: 0 12px 18px; color: var(--text-primary); }
    .side-logo .dot { color: var(--gold); }
    .side-link {
      display: flex; align-items: center; gap: 12px; padding: 10px 12px; border-radius: 10px;
      color: var(--text-secondary); text-decoration: none; font-size: 0.92rem; font-weight: 500;
      background: none; border: none; width: 100%; text-align: left; font-family: inherit;
      transition: background 0.2s, color 0.2s;
    }
    .side-link i { width: 18px; text-align: center; }
    .side-link:hover { background: rgba(168, 85, 247, 0.12); color: var(--text-primary); }
    body.light-mode .side-link:hover { background: rgba(0,0,0,0.06); }
    .side-link.active { background: var(--accent); color: #fff; }
    .side-badge { margin-left: auto; background: #ef4444; color: #fff; font-size: 0.7rem; padding: 1px 7px; border-radius: 999px; font-weight: 700; }
    .side-spacer { flex: 1; }
    .side-sep { height: 1px; background: var(--border-color); margin: 8px 0; }

    .admin-main { flex: 1; min-width: 0; padding: 28px clamp(16px, 4vw, 40px) 60px; }
    .container { max-width: 1100px; margin: 0 auto; }
    .page-head { display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; flex-wrap: wrap; margin-bottom: 20px; }
    .page-head h1 { font-family: var(--font-display); font-size: 1.5rem; color: var(--text-primary); margin-bottom: 4px; }
    .page-head p { color: var(--text-secondary); font-size: 0.88rem; }

    .welcome-card, .panel {
      background: var(--card-bg); border: 1px solid var(--border-color); border-radius: var(--radius-lg);
      padding: 24px; backdrop-filter: blur(var(--blur-strong)); margin-bottom: 24px;
    }
    .welcome-card h1 { font-family: var(--font-display); font-size: 1.6rem; margin-bottom: 6px; color: var(--text-primary); }
    .welcome-card p { color: var(--text-secondary); font-family: var(--font-mono); font-size: 0.88rem; }
    .section-title { font-family: var(--font-display); font-size: 1.1rem; margin: 8px 0 14px; color: var(--text-primary); }

    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 16px; margin-bottom: 28px; }
    .stat-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 20px; backdrop-filter: blur(var(--blur-strong)); }
    .stat-card i { color: var(--gold); font-size: 1.2rem; margin-bottom: 10px; }
    .stat-value { font-family: var(--font-display); font-size: 1.9rem; font-weight: 800; color: var(--text-primary); }
    .stat-label { color: var(--text-secondary); font-size: 0.82rem; }

    .actions-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 28px; }
    .action-card {
      display: flex; align-items: center; gap: 14px; padding: 18px; text-decoration: none;
      background: var(--card-bg); border: 1px solid var(--border-color); border-radius: var(--radius-md);
      backdrop-filter: blur(var(--blur-strong)); transition: transform 0.2s, border-color 0.2s;
    }
    .action-card:hover { transform: translateY(-3px); border-color: var(--accent); }
    .action-card > i { font-size: 1.3rem; color: var(--gold); }
    .action-title { font-weight: 600; color: var(--text-primary); }
    .action-desc { font-size: 0.82rem; color: var(--text-secondary); }

    .alert { padding: 11px 14px; border-radius: 10px; font-size: 0.88rem; margin-bottom: 18px; }
    .alert-ok  { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.4); color: #86efac; }
    .alert-err { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.4); color: #fca5a5; }
    body.light-mode .alert-ok { color: #166534; } body.light-mode .alert-err { color: #991b1b; }

    .toolbar { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 18px; }
    .toolbar input, .toolbar select, .field input, .field select, .field textarea {
      background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); border-radius: 10px;
      padding: 10px 12px; color: var(--text-primary); font-family: inherit; font-size: 0.92rem;
    }
    body.light-mode .toolbar input, body.light-mode .toolbar select,
    body.light-mode .field input, body.light-mode .field select, body.light-mode .field textarea { background: rgba(0,0,0,0.03); }
    .toolbar input { flex: 1; min-width: 180px; }
    .toolbar select option, .field select option { color: #000; }
    .toolbar input:focus, .field input:focus, .field select:focus, .field textarea:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }

    .abtn {
      display: inline-flex; align-items: center; gap: 8px; padding: 9px 16px; border-radius: 10px; border: 1px solid transparent;
      font-family: inherit; font-weight: 600; font-size: 0.88rem; text-decoration: none; background: var(--accent); color: #fff;
      transition: background 0.2s, box-shadow 0.2s;
    }
    .abtn:hover { background: var(--accent-hover); box-shadow: 0 0 16px var(--accent-glow); }
    .abtn-ghost { background: transparent; color: var(--text-primary); border-color: var(--border-color); }
    .abtn-ghost:hover { background: rgba(168,85,247,0.12); box-shadow: none; }
    .abtn-danger { background: rgba(239,68,68,0.12); color: #fca5a5; border-color: rgba(239,68,68,0.4); }
    .abtn-danger:hover { background: rgba(239,68,68,0.25); box-shadow: none; }
    body.light-mode .abtn-danger { color: #991b1b; }
    .abtn-sm { padding: 6px 11px; font-size: 0.8rem; }

    .table-wrap { overflow-x: auto; }
    .data-table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
    .data-table th { text-align: left; font-size: 0.74rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-secondary); padding: 10px 12px; border-bottom: 1px solid var(--border-color); white-space: nowrap; }
    .data-table td { padding: 12px; border-bottom: 1px solid var(--border-color); color: var(--text-primary); vertical-align: middle; }
    .data-table td.muted, .muted { color: var(--text-secondary); }
    .data-table .thumb { width: 64px; height: 44px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color); display: block; }
    .row-actions { display: flex; gap: 6px; flex-wrap: wrap; }
    .row-actions form { display: inline; }
    .pill { display: inline-block; padding: 2px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 600; border: 1px solid var(--border-color); color: var(--gold); }
    .pill-admin { background: var(--accent); color: #fff; border-color: transparent; }
    .pill-new { background: #ef4444; color: #fff; border-color: transparent; }
    .empty { text-align: center; padding: 30px; color: var(--text-secondary); }
    .bar { width: 90px; height: 6px; background: rgba(255,255,255,0.1); border-radius: 99px; overflow: hidden; }
    body.light-mode .bar { background: rgba(0,0,0,0.1); }
    .bar > span { display: block; height: 100%; background: var(--accent); }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .field { display: flex; flex-direction: column; gap: 6px; }
    .field.full { grid-column: 1 / -1; }
    .field label { font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-secondary); }
    .field small { color: var(--text-secondary); font-size: 0.78rem; }
    .field-error { color: #fca5a5; font-size: 0.82rem; }
    .form-actions { display: flex; gap: 10px; margin-top: 22px; }
    .preview-img { max-width: 220px; border-radius: 10px; border: 1px solid var(--border-color); }

    .msg-card { border-bottom: 1px solid var(--border-color); padding: 16px 4px; }
    .msg-card:last-child { border-bottom: none; }
    .msg-head { display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 6px; }
    .msg-body { color: var(--text-secondary); font-size: 0.92rem; margin: 8px 0 12px; }

    @media (max-width: 820px) {
      .admin-shell { flex-direction: column; }
      .admin-sidebar { width: 100%; height: auto; position: static; flex-direction: row; flex-wrap: wrap; padding: 12px; border-right: none; border-bottom: 1px solid var(--border-color); }
      .side-logo, .side-sep, .side-spacer { display: none; }
      .side-link { width: auto; padding: 8px 10px; font-size: 0.82rem; }
      .form-grid { grid-template-columns: 1fr; }
    }

    @media print {
      .admin-sidebar, .no-print { display: none !important; }
      body { background: #fff !important; color: #000 !important; }
      .panel, .welcome-card, .stat-card { background: #fff !important; border: 1px solid #999 !important; backdrop-filter: none !important; color: #000 !important; }
      .stat-value, .stat-label, .data-table td, .data-table th, .page-head h1, .page-head p, .section-title { color: #000 !important; }
      .admin-main { padding: 0; }
    }
  </style>
</head>
<body>
<div class="admin-shell">