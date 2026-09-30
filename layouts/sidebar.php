<?php
$active_page = $active_page ?? '';
$belum_dibaca = (int) $pdo->query('SELECT COUNT(*) FROM messages WHERE dibaca = 0')->fetchColumn();
function side_link($href, $icon, $label, $key, $active, $badge = 0) {
    $cls = 'side-link' . ($active === $key ? ' active' : '');
    echo '<a href="' . e($href) . '" class="' . $cls . '"><i class="fa-solid ' . e($icon) . '"></i> ' . e($label);
    if ($badge > 0) echo '<span class="side-badge">' . (int) $badge . '</span>';
    echo '</a>';
}
?>
  <aside class="admin-sidebar">
    <div class="side-logo">FARLES<span class="dot">.</span></div>
    <?php
    side_link('dashboard_admin.php', 'fa-gauge', 'Dashboard', 'dashboard', $active_page);
    side_link('projects.php', 'fa-briefcase', 'Kelola Proyek', 'projects', $active_page);
    side_link('messages.php', 'fa-inbox', 'Pesan Masuk', 'messages', $active_page, $belum_dibaca);
    side_link('users.php', 'fa-users', 'Pengguna', 'users', $active_page);
    side_link('report.php', 'fa-print', 'Laporan', 'report', $active_page);
    ?>
    <div class="side-sep"></div>
    <a href="index.php" class="side-link" target="_blank" rel="noopener"><i class="fa-solid fa-house"></i> Lihat Situs</a>
    <button type="button" class="side-link" id="themeToggleAdmin"><i class="fa-solid fa-circle-half-stroke"></i> <span id="themeLabel">Ganti Tema</span></button>
    <div class="side-spacer"></div>
    <a href="logout.php" class="side-link"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
  </aside>

  <main class="admin-main">