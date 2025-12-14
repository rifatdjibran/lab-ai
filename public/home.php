<?php
require "config/database.php";

// Koneksi PostgreSQL
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Koneksi database gagal: " . pg_last_error());
}
?>

<style>
    .home-slider-img {
        height: 500px; /* Tinggi slider, bisa disesuaikan */
        object-fit: cover; /* Agar gambar tidak gepeng */
        object-position: center;
    }
    
    /* Responsif untuk HP */
    @media (max-width: 768px) {
        .home-slider-img {
            height: 250px;
        }
    }

    /* Efek hover pada arrow slider agar lebih jelas */
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: rgba(0, 0, 0, 0.3); /* Latar belakang hitam transparan */
        border-radius: 50%;
        padding: 20px;
    }
</style>

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

<section class="container my-5">
    <div id="mainCarousel" class="carousel slide carousel-fade shadow rounded-4 overflow-hidden" data-bs-ride="carousel" data-bs-interval="3000">
        
        <div class="carousel-indicators">
            <?php 
            // Daftar gambar manual
            $sliderImages = ['gambar1.png', 'gambar2.png', 'gambar3.png']; 
            foreach($sliderImages as $index => $img): 
            ?>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="<?= $index ?>" 
                        class="<?= $index === 0 ? 'active' : '' ?>" aria-current="true"></button>
            <?php endforeach; ?>
        </div>

        <div class="carousel-inner">
            <?php foreach($sliderImages as $index => $img): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <img src="assets/img/home/<?= $img ?>" class="d-block w-100 home-slider-img" alt="Slide <?= $index + 1 ?>">
                </div>
            <?php endforeach; ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        
        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
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
            Laboratorium Informatika Terapan merupakan salah satu laboratorium di Jurusan Teknologi Informasi, Politeknik Negeri Malang yang berfokus pada pengembangan dan penerapan ilmu informatika dalam bidang praktis.
            Laboratorium ini mendukung kegiatan akademik mahasiswa melalui praktikum, penelitian, dan pengabdian masyarakat yang berbasis teknologi informasi terkini. 
            Selain itu, laboratorium ini juga menjadi pusat inovasi dan eksperimen untuk menghasilkan solusi kreatif di bidang software development, jaringan komputer,
             serta teknologi terapan lainnya.
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
       "Laboratorium Applied Informatics didirikan bersamaan dengan pembukaan Program Studi S2 Terapan di Jurusan Teknologi Informasi.
        Laboratorium ini merupakan satu-satunya laboratorium di bawah Jurusan Teknologi Informasi yang berlokasi di Gedung Pascasarjana. 
        Laboratorium Applied Informatics mulai beroperasi sejak awal tahun 2023 sebagai sarana pendukung kegiatan pendidikan dan pengembangan teknologi terapan.",
       "bi-clock-history"],

      ["Visi", 
       "Menjadi laboratorium unggulan dalam pengembangan dan penerapan teknologi informasi inovatif yang mendukung transformasi digital 
       berkelanjutan, mendorong kolaborasi lintas sektor, serta menciptakan soluso cerdas berbasis data untuk menghadapi tantangan era industri 4.0.",
       "bi-lightbulb"],

      ["Misi", 
       "1. Menerapkan teknologi informasi terkini, termasuk algoritma, teknologi pengolahan data, dan sistem terdistribusi, untuk mengatasi tantangan 
       praktis dalam berbagai bidang dengan fokus pada pengembangan aplikasi dan solusi teknologi yang dapat meningkatkan produktivitas dan aksesabilitas informasi.",
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
  <div class="container">
    <div class="text-center mb-5">
        <span class="badge bg-primary-subtle text-primary mb-2 px-3 py-2 rounded-pill">FOKUS RISET</span>
        <h2 class="fw-bold">Prioritas Topik Penelitian</h2>
        <p class="text-muted mw-600 mx-auto">
            Tujuh pilar utama penelitian dan pengembangan inovasi teknologi di laboratorium kami.
        </p>
    </div>

    <div class="row g-4 justify-content-center">
      <?php
      // Data Topik Penelitian
      $topics = [
          [
              "title" => "Intelligent Self-learning",
              "desc"  => "Computer Programming: Web, mobile, database, Java, gamifikasi, dan scaffolding.",
              "icon"  => "bi-laptop", // Ikon Laptop/Coding
              "color" => "text-primary"
          ],
          [
              "title" => "Smartfarming (Indoor & Outdoor)",
              "desc"  => "Based on smart technology and Internet of Things (IoT).",
              "icon"  => "bi-flower1", // Ikon Tanaman/Pertanian
              "color" => "text-success"
          ],
          [
              "title" => "Security Information & Event Management",
              "desc"  => "Cybersecurity monitoring and management based on Wazuh.",
              "icon"  => "bi-shield-lock", // Ikon Keamanan
              "color" => "text-danger"
          ],
          [
              "title" => "Decentralized System",
              "desc"  => "Based on blockchain with Ethereum platform (supply chain, crowdfunding, dan tokenization).",
              "icon"  => "bi-diagram-3", // Ikon Terdesentralisasi/Node
              "color" => "text-info"
          ],
          [
              "title" => "Financial Support Technology",
              "desc"  => "Decentralization with blockchain and predictive system using data analysis.",
              "icon"  => "bi-graph-up-arrow", // Ikon Finansial/Analisis
              "color" => "text-warning"
          ],
          [
              "title" => "Asset Protection & Digitization",
              "desc"  => "Using Electroencephalogram (EEG) technology.",
              "icon"  => "bi-activity", // Ikon Gelombang Otak/Aktivitas
              "color" => "text-secondary"
          ],
          [
              "title" => "Digital Map-based Reporting",
              "desc"  => "System for government infrastructure (roads, irrigation, etc.).",
              "icon"  => "bi-map", // Ikon Peta
              "color" => "text-dark"
          ]
      ];

      foreach ($topics as $t):
      ?>
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-0 shadow-sm p-4 hover-effect" style="transition: transform 0.3s;">
            <div class="d-flex align-items-start">
                <div class="icon-box me-3">
                    <i class="bi <?= $t['icon'] ?> fs-1 <?= $t['color'] ?>"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-2"><?= $t['title'] ?></h5>
                    <p class="text-muted small mb-0 lh-sm">
                        <?= $t['desc'] ?>
                    </p>
                </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
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