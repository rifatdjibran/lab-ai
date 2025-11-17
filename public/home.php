<!-- Hero Section -->
<section class="hero">
  <div class="container position-relative text-start text-white">
    <h1 class="display-4 fw-bold">Selamat Datang</h1>
    <h5 class="display-5 fw-bold">Laboratorium for Applied Informatics</h5>
    <p class="lead">Tempat riset dan inovasi di bidang Informatika Terapan</p>
    <a href="#profil" class="btn btn-outline-light mt-3">Lihat Profil</a>
  </div>
</section>


<!-- Berita Terbaru Section -->
<section class="container my-5">
  <h3 class="text-primary mb-4">Berita Terbaru</h3>
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <img src="assets/img/berita1.jpg" class="card-img-top grayscale" alt="Berita 1">
        <div class="card-body">
          <h5 class="card-title">Kegiatan Riset AI 2025</h5>
          <p class="card-text">Laboratorium AI mengadakan pelatihan pemodelan machine learning untuk mahasiswa.</p>
          <a href="#" class="btn btn-outline-primary btn-sm">Selengkapnya</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <img src="assets/img/berita2.jpg" class="card-img-top grayscale" alt="Berita 2">
        <div class="card-body">
          <h5 class="card-title">Workshop Data Science</h5>
          <p class="card-text">Pelatihan intensif analisis data dan visualisasi menggunakan Python dan Tableau.</p>
          <a href="#" class="btn btn-outline-primary btn-sm">Selengkapnya</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <img src="assets/img/berita3.jpg" class="card-img-top grayscale" alt="Berita 3">
        <div class="card-body">
          <h5 class="card-title">Kolaborasi Industri</h5>
          <p class="card-text">Lab-AI menjalin kerja sama dengan startup lokal untuk proyek AI vision.</p>
          <a href="#" class="btn btn-outline-primary btn-sm">Selengkapnya</a>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- Hero Section -->
<section id="profil" class="hero-section d-flex align-items-center justify-content-center text-center" 
         style="background-image: url('../assets/img/banner.png'); height: 50vh;">
  <div class="container">
    <h1 class="fw-bold display-4">Profil Laboratorium</h1>
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
    <img src="../assets/img/banners/struktur.png" alt="Struktur Organisasi" 
         class="img-fluid rounded shadow-sm my-4" style="max-width: 80%;">
    <p class="text-muted">
      Struktur organisasi Lab-AI terdiri dari dosen pembina, koordinator laboratorium, laboran,
      dan asisten mahasiswa yang bekerja sama untuk mendukung kegiatan akademik dan penelitian.
    </p>
  </div>
</section>

<!-- Tim Kami -->
<section>
  <div class="container text-center">
    <h3>Tim Kami</h3>
    <p class="text-muted mb-4">
      Beberapa staf dan anggota aktif di Laboratorium for Applied Informatics:
    </p>

    <div class="row justify-content-center g-4">
      <?php
      $anggota = [
        ["Dr. Ahmad Setiawan", "Ketua Laboratorium", "../assets/img/banners/dosen1.jpg"],
        ["Siti Nurhaliza", "Laboran", "../assets/img/banners/laboran1.jpg"]
      ];
      foreach ($anggota as $a): ?>
        <div class="col-md-3 col-6">
          <div class="card shadow-sm">
            <img src="<?= $a[2] ?>" class="card-img-top rounded-top" alt="<?= $a[0] ?>">
            <div class="card-body">
              <h6 class="fw-semibold mb-0"><?= $a[0] ?></h6>
              <small class="text-muted"><?= $a[1] ?></small>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Tombol Selengkapnya -->
    <a href="/lab-ai/public/struktur.php" class="btn btn-outline-primary mt-4">
    Selengkapnya
    </a>


  </div>
</section>


