<?php
require "config/database.php";

// Koneksi PostgreSQL (Pastikan variable $host, $port, dll sudah terdefinisi di config/database.php)
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Koneksi database gagal: " . pg_last_error());
}
?>

<section class="hero-section" id="home-hero">
  <div class="container text-center text-white" style="position: relative; z-index: 2;">
    <h1 class="display-4 fw-bold">Selamat Datang</h1>
    <h5 class="display-5 fw-bold">Laboratorium for Applied Informatics</h5>
    <p class="lead">Tempat riset dan inovasi di bidang Informatika Terapan</p>
    <a href="#profil" class="btn btn-outline-light mt-3 rounded-pill px-4">Lihat Profil</a>
  </div>

  <div class="scroll-indicator text-white">
      <small>Scroll ke bawah</small><br>
      <i class="bi bi-chevron-double-down fs-3"></i>
  </div>
</section>

<?php
// Ambil 3 berita terbaru
$query = "SELECT * FROM berita ORDER BY tanggal DESC LIMIT 3";
$beritaHome = pg_query($conn, $query);
?>

<section class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="text-primary fw-bold">Berita Terbaru</h3>
      <a href="public/berita.php" class="text-primary fw-bold text-decoration-none">Selengkapnya →</a>
  </div>

  <div class="row g-4">
    <?php
    if ($beritaHome && pg_num_rows($beritaHome) > 0):
        while ($b = pg_fetch_assoc($beritaHome)):
            $imgPath = !empty($b['gambar']) 
                ? "assets/uploads/berita/" . htmlspecialchars($b['gambar'])
                : "assets/img/default-news.png"; 
    ?>
    <div class="col-md-4">
      <div class="card shadow-sm h-100 news-card">
        <img src="<?= $imgPath ?>" 
             class="card-img-top news-img" 
             alt="<?= htmlspecialchars($b['judul']) ?>">
        <div class="card-body d-flex flex-column">
          <small class="text-muted mb-2">
            <i class="bi bi-calendar3 me-1"></i> <?= date('d M Y', strtotime($b['tanggal'])) ?>
          </small>
          <h5 class="card-title fw-bold text-dark">
            <?= htmlspecialchars($b['judul']) ?>
          </h5>
          <p class="card-text text-secondary flex-grow-1">
            <?= htmlspecialchars(substr(strip_tags($b['isi']), 0, 100)) ?>...
          </p>
          <a href="public/detail_berita.php?id=<?= $b['id'] ?>" 
             class="btn btn-outline-primary btn-sm mt-auto w-100 rounded-pill">
             Baca Selengkapnya
          </a>
        </div>
      </div>
    </div>
    <?php 
        endwhile;
    else:
    ?>
      <div class="col-12 text-center py-5">
          <p class="text-muted">Belum ada berita terbaru.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<section class="container my-5 bg-light rounded-4 p-4 p-md-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="text-primary fw-bold">Agenda Terbaru</h3>
      <a href="public/agenda.php" class="text-primary fw-bold text-decoration-none">Selengkapnya →</a>
  </div>

    <div class="row g-4">
        <?php
        $agendaResult = pg_query($conn, "SELECT * FROM kegiatan ORDER BY tanggal_mulai DESC LIMIT 3");

        if ($agendaResult && pg_num_rows($agendaResult) > 0):
            while ($ag = pg_fetch_assoc($agendaResult)):
        ?>
        <div class="col-md-4">
            <div class="card agenda-card shadow-sm h-100 border-0">
                <img src="/lab-ai/assets/uploads/kegiatan/<?= htmlspecialchars($ag['gambar']) ?>"
                     alt="<?= htmlspecialchars($ag['nama_kegiatan']) ?>"
                     class="card-img-top agenda-img">

                <div class="card-body d-flex flex-column">
                    <h5 class="fw-bold text-dark mb-2"><?= htmlspecialchars($ag['nama_kegiatan']) ?></h5>
                    
                    <p class="text-muted small mb-3 flex-grow-1">
                        <?= htmlspecialchars(substr(strip_tags($ag['deskripsi']), 0, 90)) ?>...
                    </p>

                    <div class="agenda-meta mb-3 p-3 bg-light rounded-3">
                        <div class="d-flex align-items-start mb-2">
                            <i class="bi bi-calendar-event text-primary me-2 mt-1"></i>
                            <span class="small text-muted">
                                <?= date('d M', strtotime($ag['tanggal_mulai'])) ?> - 
                                <?= date('d M Y', strtotime($ag['tanggal_selesai'])) ?>
                            </span>
                        </div>
                        <div class="d-flex align-items-start">
                            <i class="bi bi-geo-alt text-danger me-2 mt-1"></i>
                            <span class="small text-muted"><?= htmlspecialchars($ag['lokasi']) ?></span>
                        </div>
                    </div>

                    <div class="mt-auto">
                    <?php
                        $today = date('Y-m-d');
                        if ($today < $ag['tanggal_mulai']) {
                            echo '<span class="badge bg-warning text-dark w-100 py-2"><i class="bi bi-hourglass-split me-1"></i> Segera</span>';
                        } elseif ($today <= $ag['tanggal_selesai']) {
                            echo '<span class="badge bg-success w-100 py-2"><i class="bi bi-play-circle me-1"></i> Berlangsung</span>';
                        } else {
                            echo '<span class="badge bg-secondary w-100 py-2"><i class="bi bi-check-circle me-1"></i> Selesai</span>';
                        }
                    ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; else: ?>
        <div class="col-12 text-center py-5">
            <p class="text-muted">Belum ada agenda terbaru.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<section id="profil" class="py-5">
  <div class="container text-center">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <span class="badge bg-primary-subtle text-primary mb-2 px-3 py-2 rounded-pill">TENTANG KAMI</span>
            <h2 class="fw-bold mb-4 display-6">Profil Laboratorium</h2>
            <p class="lead text-dark mb-4">
              Mengenal lebih dekat Laboratorium for Applied Informatics (Lab-AI).
            </p>
            <p class="text-muted lh-lg">
              Laboratorium for Applied Informatics (Lab-AI) merupakan pusat kegiatan akademik, penelitian,
              dan pengembangan di bidang Informatika Terapan di bawah Jurusan Teknologi Informasi
              Politeknik Negeri Malang. Kami berkomitmen untuk menjadi wadah inovasi bagi mahasiswa dan dosen
              dalam mengembangkan solusi teknologi tepat guna.
            </p>
        </div>
    </div>
  </div>
</section>

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
        <div class="card card-hover p-4 h-100 text-center border-0 shadow-sm">
          <div class="icon-wrapper mb-3 mx-auto">
              <i class="bi <?= $s[2] ?> fs-2 text-primary icon-static"></i>
          </div>
          <h5 class="fw-bold mb-3 text-dark"><?= $s[0] ?></h5>
          <p class="card-text text-secondary small"><?= $s[1] ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="bg-light py-5">
  <div class="container text-center">
    <span class="badge bg-primary-subtle text-primary mb-2 px-3 py-2 rounded-pill">TIM KAMI</span>
    <h2 class="fw-bold mb-3">Struktur Organisasi</h2>
    <p class="text-muted mb-5 mw-600 mx-auto">
      Struktur organisasi Lab-AI terdiri dari dosen pembina, koordinator laboratorium, laboran, dan asisten mahasiswa.
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
          <div class="card shadow-sm h-100 team-card border-0">
          <div class="team-img-wrapper">
              <img src="<?= $a[2] ?>" class="card-img-top" alt="<?= $a[0] ?>">
          </div>
            <div class="card-body py-3">
              <h6 class="fw-bold mb-1 text-dark small"><?= $a[0] ?></h6>
              <small class="text-primary fw-medium" style="font-size: 0.75rem;"><?= $a[1] ?></small>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="mt-5">
        <a href="/lab-ai/public/struktur.php" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
        Lihat Struktur Lengkap <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container text-center">
    <span class="badge bg-primary-subtle text-primary mb-2 px-3 py-2 rounded-pill">INOVASI</span>
    <h2 class="fw-bold mb-5">Produk Kami</h2>

    <div class="row g-4 justify-content-center">
      <?php 
      $products = [
          ['AMATI', 'amati.png', 'Amati', 'Automated Cyber Security Maturity Assessment (AMATI)', 'bg-danger'],
          ['AGRI', 'agrilink.png', 'Agrilink Vocpro', 'Platform pertanian cerdas berbasis IoT.', 'bg-success'],
          ['SEALS', 'seals.png', 'SEALS', 'Smart Adaptive Learning System (SEALS)', 'bg-info'],
          ['CHAIN', 'crowd.png', 'CrowdEquiChain', 'Platform Crowdfunding berbasis Blockchain.', 'bg-warning'],
          ['SERVER', 'owncloud.png', 'Owncloud Server', 'Layanan penyimpanan cloud privat laboratorium.', 'bg-dark'],
          ['GIT', 'gitea.png', 'Gitea', 'Layanan Git self-hosted untuk manajemen kode.', 'bg-secondary']
      ];
      
      foreach($products as $prod):
      ?>
      <div class="col-lg-4 col-md-6">
        <div class="product-card h-100">
          <div class="product-header">
              <span class="badge <?= $prod[4] ?> position-absolute top-0 end-0 m-3"><?= $prod[0] ?></span>
              <div class="img-container">
                 <img src="assets/img/<?= $prod[1] ?>" class="product-logo" alt="<?= $prod[2] ?>">
              </div>
          </div>
          <div class="product-content text-start">
            <h5 class="product-title fw-bold text-dark"><?= $prod[2] ?></h5>
            <p class="product-desc text-muted small mb-0">
                <?= !empty($prod[3]) ? $prod[3] : 'Deskripsi produk belum tersedia.' ?>
            </p>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>