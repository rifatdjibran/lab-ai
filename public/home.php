<?php
require "config/database.php";

// Koneksi PostgreSQL
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Koneksi database gagal: " . pg_last_error());
}
?>


<!-- Hero Section (Tetap Satu-Satunya) -->
<section class="hero">
  <div class="container position-relative text-start text-white">
    <h1 class="display-4 fw-bold">Selamat Datang</h1>
    <h5 class="display-5 fw-bold">Laboratorium for Applied Informatics</h5>
    <p class="lead">Tempat riset dan inovasi di bidang Informatika Terapan</p>
    <a href="#profil" class="btn btn-outline-light mt-3">Lihat Profil</a>
  </div>
</section>


<!-- Berita Terbaru Section -->
<?php
include 'config/database.php';

// Ambil 3 berita terbaru
$query = "SELECT * FROM berita ORDER BY tanggal DESC LIMIT 3";
$beritaHome = pg_query($conn, $query);
?>

<section class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="text-primary">Berita Terbaru</h3>
      <a href="public/berita.php" class="text-primary fw-bold">Selengkapnya →</a>
  </div>

  <div class="row g-4">

    <?php
    if ($beritaHome && pg_num_rows($beritaHome) > 0):
        while ($b = pg_fetch_assoc($beritaHome)):

            // Path gambar (fallback kalau kosong)
            $imgPath = !empty($b['gambar']) 
                ? "assets/uploads/berita/" . htmlspecialchars($b['gambar'])
                : "assets/img/default-news.png"; 
    ?>

    <div class="col-md-4">
      <div class="card shadow-sm h-100">

        <img src="<?= $imgPath ?>" 
             class="card-img-top" 
             style="height: 200px; object-fit: cover;"
             alt="<?= htmlspecialchars($b['judul']) ?>">

        <div class="card-body">
          <small class="text-muted">
            <?= date('d M Y', strtotime($b['tanggal'])) ?>
          </small>

          <h5 class="card-title mt-2">
            <?= htmlspecialchars($b['judul']) ?>
          </h5>

          <p class="card-text">
            <?= htmlspecialchars(substr($b['isi'], 0, 120)) ?>...
          </p>

          <a href="public/detail_berita.php?id=<?= $b['id'] ?>" 
             class="btn btn-outline-primary btn-sm">
             Selengkapnya
          </a>
        </div>
      </div>
    </div>

    <?php 
        endwhile;
    else:
    ?>

      <div class="col-12 text-center">
          <p class="text-muted">Belum ada berita terbaru.</p>
      </div>

    <?php endif; ?>

  </div>
</section>


<!-- Agenda Terbaru -->
<section class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="text-primary">Agenda Terbaru</h3>
      <a href="public/agenda.php" class="text-primary fw-bold">Selengkapnya →</a>
  </div>

    <div class="row g-4">

        <?php
        // Query Agenda
        $agendaResult = pg_query($conn, "SELECT * FROM kegiatan ORDER BY tanggal_mulai DESC LIMIT 3");

        if ($agendaResult && pg_num_rows($agendaResult) > 0):
            while ($ag = pg_fetch_assoc($agendaResult)):
        ?>

        <div class="col-md-4">
            <div class="card agenda-card shadow-sm h-100">

                <img src="/lab-ai/assets/uploads/kegiatan/<?= htmlspecialchars($ag['gambar']) ?>"
                     alt="<?= htmlspecialchars($ag['nama_kegiatan']) ?>"
                     class="card-img-top"
                     style="height: 180px; object-fit: cover;">

                <div class="card-body">
                    <h5 class="fw-bold"><?= htmlspecialchars($ag['nama_kegiatan']) ?></h5>

                    <p class="text-muted" style="font-size: 14px;">
                        <?= htmlspecialchars(substr($ag['deskripsi'], 0, 100)) ?>...
                    </p>

                    <p class="mb-1">
                        <strong>Tanggal:</strong><br>
                        <?= date('d M Y', strtotime($ag['tanggal_mulai'])) ?> —
                        <?= date('d M Y', strtotime($ag['tanggal_selesai'])) ?>
                    </p>

                    <p class="mb-2">
                        <strong>Lokasi:</strong><br>
                        <?= htmlspecialchars($ag['lokasi']) ?>
                    </p>

                    <?php
                        $today = date('Y-m-d');
                        if ($today < $ag['tanggal_mulai']) {
                            echo '<span class="badge bg-warning text-dark">Segera</span>';
                        } elseif ($today <= $ag['tanggal_selesai']) {
                            echo '<span class="badge bg-success">Berlangsung</span>';
                        } else {
                            echo '<span class="badge bg-secondary">Selesai</span>';
                        }
                    ?>
                </div>

            </div>
        </div>

        <?php endwhile; else: ?>

        <div class="col-12 text-center">
            <p class="text-muted">Belum ada agenda terbaru.</p>
        </div>

        <?php endif; ?>

    </div>
</section>

<!-- Profil Laboratorium (BUKAN HERO LAGI) -->
<section id="profil" class="py-5 bg-light">
  <div class="container text-center">
    <h2 class="fw-bold">Profil Laboratorium</h2>
    <p class="lead">Mengenal lebih dekat Laboratorium for Applied Informatics</p>
  </div>
</section>


<!-- Tentang Kami -->
<section class="bg-light">
  <div class="container text-center">
    <h2>Tentang Kami</h2>
    <p class="text-muted mt-3">
      Laboratorium for Applied Informatics (Lab-AI) merupakan pusat kegiatan akademik, penelitian,
      dan pengembangan di bidang Informatika Terapan di bawah Jurusan Teknologi Informasi
      Politeknik Negeri Malang.
    </p>
  </div>
</section>


<!-- Sejarah, Visi, Misi -->
<section class="container my-5 visi-misi-section">
  <div class="row g-4">
    <?php
    $sections = [
      ["Sejarah Singkat", 
       "Laboratorium for Applied Informatics (Lab AI) berdiri pada tahun 2020 sebagai wadah penelitian mahasiswa di bidang kecerdasan buatan.",
       "bi-clock-history"],

      ["Visi", 
       "Menjadi laboratorium unggulan dalam riset dan penerapan Artificial Intelligence di lingkungan pendidikan vokasi.",
       "bi-lightbulb"],

      ["Misi", 
       "Mengembangkan riset AI yang berdampak, memperkuat kolaborasi industri, dan mendorong inovasi mahasiswa.",
       "bi-rocket"]
    ];
    foreach ($sections as $s): ?>
      <div class="col-md-4">
        <div class="card card-hover p-4 h-100 text-center">
          <i class="bi <?= $s[2] ?> fs-1 text-primary mb-3 icon-static"></i>
          <h5 class="fw-bold mb-3 text-dark"><?= $s[0] ?></h5>
          <p class="card-text text-secondary"><?= $s[1] ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>


<!-- Struktur Organisasi -->
<section class="bg-light text-center">
  <div class="container">
    <h2>Struktur Organisasi</h2>
    <p class="text-muted">
      Struktur organisasi Lab-AI terdiri dari dosen pembina, koordinator laboratorium, laboran,
      dan asisten mahasiswa.
    </p>
  </div>
</section>


<!-- Tim Kami -->
<section>
  <div class="container text-center">
    <h3>Tim Kami</h3>
    <p class="text-muted mb-4">
      Beberapa staf aktif di Laboratorium for Applied Informatics:
    </p>

    <div class="row justify-content-center g-4">
      <?php
      $anggota_home = [
      ["Ir. Yan Watequlis Syaifudin, S.T., M.MT., Ph.D", "Ketua Laboratorium", "assets/img/tim/ketua.png"],
      ["Pramana Yoga Saputra, S.Kom., M.MT.", "Anggota", "assets/img/tim/yoga.png"]
    ];


      foreach ($anggota_home as $a):
      ?>
        <div class="col-md-3 col-6">
          <div class="card shadow-sm h-100">
            <img src="<?= $a[2] ?>" class="card-img-top rounded-top" alt="<?= $a[0] ?>">
            <div class="card-body">
              <h6 class="fw-semibold mb-0"><?= $a[0] ?></h6>
              <small class="text-muted"><?= $a[1] ?></small>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <a href="/lab-ai/public/struktur.php" class="btn btn-outline-primary mt-4">
      Selengkapnya →
    </a>
  </div>
</section>



<!-- Produk -->
<section class="bg-light py-5">
  <div class="container text-center">

    <h2 class="fw-semibold mb-2">Produk Kami</h2>
    <div class="divider-custom mx-auto mb-4"></div>

    <div class="row g-4">

      <!-- AMATI -->
      <div class="col-md-4 col-sm-6">
        <div class="product-card">
          <div class="product-badge">AMATI</div>
          <img src="assets/img/amati.png" class="product-logo">
          <div class="product-content">
            <div class="product-title">Amati</div>
            <div class="product-desc">
              Automated Cyber Security Maturity Assessment (AMATI)
            </div>
          </div>
        </div>
      </div>

      <!-- Agrilink -->
      <div class="col-md-4 col-sm-6">
        <div class="product-card">
          <div class="product-badge">AGRI</div>
          <img src="assets/img/agrilink.png" class="product-logo">
          <div class="product-content">
            <div class="product-title">Agrilink Vocpro</div>
            <div class="product-desc"></div>
          </div>
        </div>
      </div>

      <!-- SEALS -->
      <div class="col-md-4 col-sm-6">
        <div class="product-card">
          <div class="product-badge">SEALS</div>
          <img src="assets/img/seals.png" class="product-logo">
          <div class="product-content">
            <div class="product-title">SEALS</div>
            <div class="product-desc">Smart Adaptive Learning System (SEALS)</div>
          </div>
        </div>
      </div>

      <!-- CrowdEquiChain -->
      <div class="col-md-4 col-sm-6">
        <div class="product-card">
          <div class="product-badge">CHAIN</div>
          <img src="assets/img/crowd.png" class="product-logo">
          <div class="product-content">
            <div class="product-title">CrowdEquiChain</div>
            <div class="product-desc">Crowdfunding</div>
          </div>
        </div>
      </div>

      <!-- Owncloud -->
      <div class="col-md-4 col-sm-6">
        <div class="product-card">
          <div class="product-badge">SERVER</div>
          <img src="assets/img/owncloud.png" class="product-logo">
          <div class="product-content">
            <div class="product-title">Owncloud Server</div>
            <div class="product-desc"></div>
          </div>
        </div>
      </div>

      <!-- Gitea -->
      <div class="col-md-4 col-sm-6">
        <div class="product-card">
          <div class="product-badge">GIT</div>
          <img src="assets/img/gitea.png" class="product-logo">
          <div class="product-content">
            <div class="product-title">Gitea</div>
            <div class="product-desc"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
