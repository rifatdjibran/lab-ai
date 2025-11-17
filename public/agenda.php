<?php
include '../config/database.php';

// Koneksi database PostgreSQL
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$conn) {
    die("Koneksi ke database gagal: " . pg_last_error());
}

date_default_timezone_set('Asia/Jakarta'); 
$tanggalHariIni = date('l, d F Y');

// Ambil data kegiatan dari database
$query = "SELECT id, nama_kegiatan, deskripsi, tanggal_mulai, tanggal_selesai, 
                 lokasi, gambar, created_at
          FROM public.kegiatan
          ORDER BY tanggal_mulai ASC";
$result = pg_query($conn, $query);

if (!$result) {
    die("Query gagal: " . pg_last_error($conn));
}

$agendaLab = pg_fetch_all($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Laboratorium</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .hero-section {
            background: linear-gradient(rgba(0,51,102,0.6), rgba(0,51,102,0.6)),
                        url('../assets/img/home-lab.jpg.jpg') center/cover no-repeat;
            height: 40vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            border-bottom-left-radius: 50px; /* lengkung kiri bawah */
            border-bottom-right-radius: 50px;
        }
        .agenda-card {
            transition: transform 0.3s;
        }
        .agenda-card:hover {
            transform: translateY(-5px);
        }
        .badge-status {
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
    <img src="../assets/img/logo.png" alt="Logo" height="40" class="me-2">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item"><a class="nav-link active" href="../index.php">Home</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="penelitianPublikasiDropdown" role="button" data-bs-toggle="dropdown">
            Penelitian & Publikasi
          </a>
          <ul class="dropdown-menu" aria-labelledby="penelitianPublikasiDropdown">
            <li><a class="dropdown-item" href="public/fasilitas.php#penelitian">Hasil Penelitian</a></li>
            <li><a class="dropdown-item" href="public/kegiatan.php#publikasi">Publikasi Ilmiah</a></li>
          </ul>
        </li>

        <li class="nav-item"><a class="nav-link" href="../public/fasilitas.php">Fasilitas</a></li>
        <li class="nav-item"><a class="nav-link" href="../public/agenda.php">Agenda</a></li>
        <li class="nav-item"><a class="nav-link" href="../public/galery.php">Gallery</a></li>
        <li class="nav-item"><a class="nav-link" href="../public/berita.php">Berita</a></li>
        <li class="nav-item"><a class="nav-link" href="../public/kontak.php">Kontak</a></li>

        <!-- Tombol Login Admin -->
        <li class="nav-item ms-lg-3">
          <a href="admin/login.php" class="btn btn-outline-primary btn-sm px-3">
            <i class="bi bi-person-lock me-1"></i> Login Admin
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div>
            <h1 class="display-4 fw-bold">Agenda Kegiatan Laboratorium</h1>
            <p class="lead">Ikuti kegiatan terbaru dan jadwal workshop kami</p>
        </div>
    </section>

    <!-- Agenda Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="mb-4 text-center">Agenda Laboratorium</h2>
            <div class="row g-4">
                <?php if ($agendaLab): ?>
                    <?php foreach ($agendaLab as $kegiatan): ?>
                        <div class="col-md-4">
                            <div class="card agenda-card shadow-sm">
                                <img src="../assets/img/banners/<?= $kegiatan['gambar'] ?>" 
                                     class="card-img-top" 
                                     alt="<?= htmlspecialchars($kegiatan['nama_kegiatan']) ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($kegiatan['nama_kegiatan']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($kegiatan['deskripsi']) ?></p>
                                    <p class="mb-1"><strong>Tanggal:</strong> <?= date('d F Y', strtotime($kegiatan['tanggal_mulai'])) ?> 
                                        - <?= date('d F Y', strtotime($kegiatan['tanggal_selesai'])) ?></p>
                                    <p class="mb-1"><strong>Lokasi:</strong> <?= htmlspecialchars($kegiatan['lokasi']) ?></p>
                                    <?php
                                    // Status badge sederhana berdasarkan tanggal
                                    $today = date('Y-m-d');
                                    if ($today < $kegiatan['tanggal_mulai']) {
                                        $status = '<span class="badge bg-warning text-dark badge-status">Segera</span>';
                                    } elseif ($today >= $kegiatan['tanggal_mulai'] && $today <= $kegiatan['tanggal_selesai']) {
                                        $status = '<span class="badge bg-success badge-status">Sedang Berlangsung</span>';
                                    } else {
                                        $status = '<span class="badge bg-secondary badge-status">Selesai</span>';
                                    }
                                    echo $status;
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p class="text-muted">Belum ada agenda saat ini.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary text-white text-center py-3 mt-5">
  <small>&copy; <?= date('Y') ?> Laboratorium for Applied Informatics</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
