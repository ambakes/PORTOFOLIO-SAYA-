<?php
// ============================================
// USERS.PHP — daftar pengguna, ubah role, hapus
// ============================================
$active_page = 'users';
$page_title  = 'Pengguna';

include 'layouts/auth.php';

// Email akun yang sedang login (untuk tahu apakah dia pemilik utama)
$st = $pdo->prepare('SELECT email FROM users WHERE id = ?');
$st->execute([$user_aktif['id']]);
$aku_owner = is_owner_email($st->fetchColumn());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id   = (int) ($_POST['id'] ?? 0);
    $aksi = $_POST['aksi'] ?? '';

    $st = $pdo->prepare('SELECT id, username, email, role FROM users WHERE id = ?');
    $st->execute([$id]);
    $target = $st->fetch();

    if (!$target) {
        flash('Pengguna tidak ditemukan.', 'err');
    } elseif ($id === (int) $user_aktif['id']) {
        flash('Kamu tidak bisa mengubah atau menghapus akunmu sendiri di sini.', 'err');
    } elseif (is_owner_email($target['email'])) {
        flash('Akun pemilik utama dilindungi dan tidak bisa diubah atau dihapus.', 'err');
    } elseif (in_array($aksi, ['jadi_admin', 'jadi_user'], true) && !$aku_owner) {
        flash('Hanya pemilik utama yang boleh mengubah role.', 'err');
    } elseif ($aksi === 'hapus' && $target['role'] === 'admin' && !$aku_owner) {
        flash('Hanya pemilik utama yang boleh menghapus akun admin.', 'err');
    } elseif ($aksi === 'jadi_admin') {
        $pdo->prepare('UPDATE users SET role = "admin" WHERE id = ?')->execute([$id]);
        flash('Role diubah menjadi admin.');
    } elseif ($aksi === 'jadi_user') {
        $pdo->prepare('UPDATE users SET role = "user" WHERE id = ?')->execute([$id]);
        flash('Role diubah menjadi user.');
    } elseif ($aksi === 'hapus') {
        $pdo->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
        flash('Pengguna dihapus.');
    }
    header('Location: users.php');
    exit;
}

$q   = trim($_GET['q'] ?? '');
$rol = $_GET['role'] ?? 'all';
$sql = 'SELECT id, username, email, role, created_at FROM users WHERE 1=1';
$params = [];
if ($q !== '') {
    $sql .= ' AND (username LIKE ? OR email LIKE ?)';
    $like = '%' . $q . '%';
    array_push($params, $like, $like);
}
if (in_array($rol, ['admin', 'user'], true)) {
    $sql .= ' AND role = ?';
    $params[] = $rol;
} else {
    $rol = 'all';
}
$sql .= ' ORDER BY id DESC';
$st = $pdo->prepare($sql);
$st->execute($params);
$users = $st->fetchAll();

include 'layouts/header.php';
include 'layouts/sidebar.php';
?>

    <div class="container">
      <div class="page-head">
        <div>
          <h1>Pengguna</h1>
          <p><?= count($users) ?> akun terdaftar</p>
        </div>
      </div>

      <?php flash_show(); ?>

      <form method="GET" class="toolbar">
        <input type="search" name="q" value="<?= e($q) ?>" placeholder="Cari username atau email...">
        <select name="role">
          <option value="all"   <?= $rol === 'all'   ? 'selected' : '' ?>>Semua role</option>
          <option value="admin" <?= $rol === 'admin' ? 'selected' : '' ?>>Admin</option>
          <option value="user"  <?= $rol === 'user'  ? 'selected' : '' ?>>User</option>
        </select>
        <button type="submit" class="abtn abtn-ghost"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
      </form>

      <div class="panel">
        <?php if (!$users) { ?>
          <div class="empty">Tidak ada pengguna.</div>
        <?php } else { ?>
          <div class="table-wrap">
            <table class="data-table">
              <thead><tr><th>Username</th><th>Email</th><th>Role</th><th>Terdaftar</th><th>Aksi</th></tr></thead>
              <tbody>
              <?php foreach ($users as $u) {
                $diri        = (int) $u['id'] === (int) $user_aktif['id'];
                $target_own  = is_owner_email($u['email']);
                $bisa_role   = $aku_owner && !$diri && !$target_own;
                $bisa_hapus  = !$diri && !$target_own && ($aku_owner || $u['role'] !== 'admin');
              ?>
                <tr>
                  <td>
                    <strong><?= e($u['username']) ?></strong><?= $diri ? ' <span class="muted">(kamu)</span>' : '' ?>
                    <?php if ($target_own) { ?><div class="muted" style="font-size:.74rem"><i class="fa-solid fa-crown"></i> pemilik utama</div><?php } ?>
                  </td>
                  <td class="muted"><?= e($u['email']) ?></td>
                  <td><span class="pill <?= $u['role'] === 'admin' ? 'pill-admin' : '' ?>"><?= e($u['role']) ?></span></td>
                  <td class="muted"><?= e(date('d/m/Y', strtotime($u['created_at']))) ?></td>
                  <td>
                    <?php if ($bisa_role || $bisa_hapus) { ?>
                    <div class="row-actions">
                      <?php if ($bisa_role) { ?>
                      <form method="POST" data-confirm="Ubah role akun '<?= e($u['username']) ?>'?">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $u['id'] ?>">
                        <input type="hidden" name="aksi" value="<?= $u['role'] === 'admin' ? 'jadi_user' : 'jadi_admin' ?>">
                        <button type="submit" class="abtn abtn-ghost abtn-sm"><?= $u['role'] === 'admin' ? 'Jadikan user' : 'Jadikan admin' ?></button>
                      </form>
                      <?php } ?>
                      <?php if ($bisa_hapus) { ?>
                      <form method="POST" data-confirm="Hapus akun '<?= e($u['username']) ?>'?">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $u['id'] ?>">
                        <input type="hidden" name="aksi" value="hapus">
                        <button type="submit" class="abtn abtn-danger abtn-sm"><i class="fa-solid fa-trash"></i></button>
                      </form>
                      <?php } ?>
                    </div>
                    <?php } else { ?><span class="muted">—</span><?php } ?>
                  </td>
                </tr>
              <?php } ?>
              </tbody>
            </table>
          </div>
        <?php } ?>
      </div>
    </div>

<?php include 'layouts/footer.php'; ?>