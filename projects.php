<?php
// ============================================
// PROJECTS.PHP — KELOLA PROYEK
// Menampilkan proyek yang ada di Hall of Creations (index.html).
// Data proyek masih diambil langsung dari sini (belum ada database),
// jadi kalau mau ubah judul/deskripsi/gambar, sesuaikan array $projects
// di bawah DAN juga bagian #works di index.html biar tetap sinkron.
// ============================================
$active_page = 'projects';

include 'layouts/auth.php';
include 'layouts/header.php';
include 'layouts/sidebar.php';

$projects = [
    [
        "tag"      => "WordPress / CMS",
        "title"    => "PROJECT GELAR KARYA SMK TI AIRLANGGA",
        "desc"     => "Menggunakan WordPress dalam project tersebut.",
        "img"      => "WhatsApp Image 2026-09-02 at 22.03.25.jpeg",
        "progress" => null,
    ],
    [
        "tag"      => "C# / Unity Engine",
        "title"    => "Misteri Ambaruwoo",
        "desc"     => "Proyek game misteri yang sedang dikembangkan menggunakan Unity.",
        "img"      => "Screenshot 2026-09-02 214603.png",
        "progress" => 10,
    ],
    [
        "tag"      => "Python / CLI",
        "title"    => "CRUD Management App",
        "desc"     => "Aplikasi pengelolaan data member berbasis Python menggunakan fungsi penambahan, pengubahan, dan penampilan data berstruktur list & dictionary.",
        "img"      => "Screenshot 2026-09-02 222534.png",
        "progress" => null,
    ],
];
?>

    <div class="container">

      <div class="projects-page-header">
        <div>
          <h1 style="font-family: var(--font-display); font-size: 1.5rem; color: var(--text-primary); margin-bottom: 4px;">
            Kelola Proyek
          </h1>
          <p style="color: var(--text-secondary); font-size: 0.88rem;">
            <?= count($projects) ?> proyek sedang ditampilkan di Hall of Creations
          </p>
        </div>
      </div>

      <div class="projects-note">
        <i class="fa-solid fa-circle-info"></i>
        Data proyek di halaman ini masih diambil langsung dari kode (belum pakai database).
        Untuk nambah, ubah, atau hapus proyek, edit array <code>$projects</code> di <code>projects.php</code>
        dan bagian <code>#works</code> di <code>index.html</code> biar tetap sinkron.
      </div>

      <div class="projects-manage-grid">
        <?php foreach ($projects as $p) { ?>
          <div class="manage-project-card">
            <div class="manage-project-thumb">
              <img src="<?= htmlspecialchars($p['img']) ?>" alt="<?= htmlspecialchars($p['title']) ?>">
            </div>
            <div class="manage-project-body">
              <span class="manage-project-tag"><?= htmlspecialchars($p['tag']) ?></span>
              <h3 class="manage-project-title"><?= htmlspecialchars($p['title']) ?></h3>
              <p class="manage-project-desc"><?= htmlspecialchars($p['desc']) ?></p>

              <?php if ($p['progress'] !== null) { ?>
                <span class="manage-project-progress-label">Progress: <?= (int) $p['progress'] ?>%</span>
                <div class="manage-project-progress-bar">
                  <div class="manage-project-progress-fill" style="width: <?= (int) $p['progress'] ?>%;"></div>
                </div>
              <?php } ?>

              <div class="manage-project-actions">
                <a href="index.html#works" target="_blank" rel="noopener">
                  <i class="fa-solid fa-eye"></i> Lihat di Situs
                </a>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>

    </div>

<?php
include 'layouts/footer.php';
?>