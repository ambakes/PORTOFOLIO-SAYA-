<?php
// ============================================
// PROJECT_FORM.PHP — tambah & ubah proyek
// (?id=X untuk ubah). Mendukung upload gambar.
// ============================================
$active_page = 'projects';

include 'layouts/auth.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$p  = ['title' => '', 'tag' => '', 'category' => 'web', 'description' => '', 'img' => '', 'video' => '', 'progress' => ''];

if ($id > 0) {
    $st = $pdo->prepare('SELECT * FROM projects WHERE id = ?');
    $st->execute([$id]);
    $row = $st->fetch();
    if (!$row) {
        flash('Proyek tidak ditemukan.', 'err');
        header('Location: projects.php');
        exit;
    }
    $p = $row;
    $p['progress'] = $row['progress'] === null ? '' : $row['progress'];
    $p['video']    = $row['video'] ?? '';
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $p['title']       = trim($_POST['title'] ?? '');
    $p['tag']         = trim($_POST['tag'] ?? '');
    $p['category']    = $_POST['category'] ?? 'web';
    $p['description'] = trim($_POST['description'] ?? '');
    $p['video']       = trim($_POST['video'] ?? '');
    $img_teks         = trim($_POST['img'] ?? '');
    $progress_in      = trim($_POST['progress'] ?? '');

    if ($p['title'] === '' || mb_strlen($p['title']) > 150)  $errors[] = 'Judul wajib diisi (maks. 150 karakter).';
    if ($p['tag'] === '' || mb_strlen($p['tag']) > 100)      $errors[] = 'Tag wajib diisi (maks. 100 karakter).';
    if (!in_array($p['category'], ['web', 'game'], true))    $errors[] = 'Kategori tidak valid.';
    if ($p['description'] === '' || mb_strlen($p['description']) > 2000) $errors[] = 'Deskripsi wajib diisi (maks. 2000 karakter).';

    if ($progress_in === '') {
        $progress = null;
    } elseif (ctype_digit($progress_in) && (int) $progress_in <= 100) {
        $progress = (int) $progress_in;
    } else {
        $progress = null;
        $errors[] = 'Progress harus angka 0–100, atau kosongkan.';
    }
    $p['progress'] = $progress_in;

    if ($p['video'] !== '' && (!path_aman($p['video']) || mb_strlen($p['video']) > 255)) {
        $errors[] = 'Nama file video tidak valid.';
    }

    // Gambar: upload baru > nama file manual > gambar lama
    $img_final = $p['img'];
    if (isset($_FILES['img_file']) && $_FILES['img_file']['error'] !== UPLOAD_ERR_NO_FILE) {
        $f = $_FILES['img_file'];
        if ($f['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Upload gambar gagal (kode ' . (int) $f['error'] . ').';
        } elseif ($f['size'] > 3 * 1024 * 1024) {
            $errors[] = 'Ukuran gambar maksimal 3 MB.';
        } else {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime  = $finfo->file($f['tmp_name']);
            $ext_map = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
            if (!isset($ext_map[$mime])) {
                $errors[] = 'File harus berupa gambar (JPG, PNG, WEBP, atau GIF).';
            } else {
                $dir = __DIR__ . '/uploads';
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                $nama = 'uploads/' . bin2hex(random_bytes(8)) . '.' . $ext_map[$mime];
                if (move_uploaded_file($f['tmp_name'], __DIR__ . '/' . $nama)) {
                    $img_final = $nama;
                } else {
                    $errors[] = 'Gagal menyimpan file upload. Pastikan folder uploads/ bisa ditulis.';
                }
            }
        }
    } elseif ($img_teks !== '') {
        if (!path_aman($img_teks) || mb_strlen($img_teks) > 255) {
            $errors[] = 'Nama file gambar tidak valid.';
        } else {
            $img_final = $img_teks;
        }
    }
    if ($img_final === '') $errors[] = 'Gambar wajib diisi (upload file atau isi nama file yang sudah ada).';

    if (!$errors) {
        $vid = $p['video'] === '' ? null : $p['video'];
        if ($id > 0) {
            $pdo->prepare('UPDATE projects SET title=?, tag=?, category=?, description=?, img=?, video=?, progress=? WHERE id=?')
                ->execute([$p['title'], $p['tag'], $p['category'], $p['description'], $img_final, $vid, $progress, $id]);
            flash('Proyek berhasil diperbarui.');
        } else {
            $pdo->prepare('INSERT INTO projects (title, tag, category, description, img, video, progress) VALUES (?,?,?,?,?,?,?)')
                ->execute([$p['title'], $p['tag'], $p['category'], $p['description'], $img_final, $vid, $progress]);
            flash('Proyek baru berhasil ditambahkan.');
        }
        header('Location: projects.php');
        exit;
    }
    $p['img'] = $img_final;
}

$page_title = $id > 0 ? 'Ubah Proyek' : 'Tambah Proyek';
include 'layouts/header.php';
include 'layouts/sidebar.php';
?>

    <div class="container">
      <div class="page-head">
        <div>
          <h1><?= e($page_title) ?></h1>
          <p>Perubahan langsung tampil di Hall of Creations.</p>
        </div>
        <a href="projects.php" class="abtn abtn-ghost"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
      </div>

      <?php foreach ($errors as $er) { ?><div class="alert alert-err"><?= e($er) ?></div><?php } ?>

      <div class="panel">
        <form method="POST" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <div class="form-grid">
            <div class="field full">
              <label for="title">Judul</label>
              <input type="text" id="title" name="title" value="<?= e($p['title']) ?>" maxlength="150" required>
            </div>
            <div class="field">
              <label for="tag">Tag / Teknologi</label>
              <input type="text" id="tag" name="tag" value="<?= e($p['tag']) ?>" placeholder="mis. Python / CLI" maxlength="100" required>
            </div>
            <div class="field">
              <label for="category">Kategori (untuk filter di situs)</label>
              <select id="category" name="category">
                <option value="web"  <?= $p['category'] === 'web'  ? 'selected' : '' ?>>Web</option>
                <option value="game" <?= $p['category'] === 'game' ? 'selected' : '' ?>>Game</option>
              </select>
            </div>
            <div class="field full">
              <label for="description">Deskripsi</label>
              <textarea id="description" name="description" rows="4" maxlength="2000" required><?= e($p['description']) ?></textarea>
            </div>
            <div class="field">
              <label for="progress">Progress (%)</label>
              <input type="number" id="progress" name="progress" min="0" max="100" value="<?= e($p['progress']) ?>" placeholder="kosongkan jika tidak ada">
            </div>
            <div class="field">
              <label for="video">File video (opsional)</label>
              <input type="text" id="video" name="video" value="<?= e($p['video']) ?>" placeholder="mis. video-proyek.mp4">
              <small>Isi nama file yang sudah ada di folder situs.</small>
            </div>
            <div class="field full">
              <label for="img_file">Gambar</label>
              <?php if ($p['img'] !== '') { ?><img class="preview-img" src="<?= e($p['img']) ?>" alt="Gambar saat ini"><?php } ?>
              <input type="file" id="img_file" name="img_file" accept="image/jpeg,image/png,image/webp,image/gif">
              <small>Upload gambar baru (maks. 3 MB), atau isi nama file yang sudah ada di bawah.</small>
              <input type="text" name="img" value="<?= e($p['img']) ?>" placeholder="mis. Screenshot 2026-09-02 214603.png">
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" class="abtn"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            <a href="projects.php" class="abtn abtn-ghost">Batal</a>
          </div>
        </form>
      </div>
    </div>

<?php include 'layouts/footer.php'; ?>