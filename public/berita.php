<?php
include '../config/database.php';

// Koneksi database PostgreSQL
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$conn) {
    die("Koneksi ke database gagal: " . pg_last_error());
}

// Ambil data berita
$query = "SELECT * FROM berita ORDER BY tanggal DESC";
$result = pg_query($conn, $query); // ← gunakan $conn

if (!$result) {
    die("Query error: " . pg_last_error());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Terbaru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .news-card {
            border-radius: 12px;
            overflow: hidden;
            transition: 0.3s;
            background: white;
        }
        .news-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .news-img {
            height: 180px;
            object-fit: cover;
        }
        .category {
            font-size: 14px;
            font-weight: bold;
            color: #c00;
        }
        .hero-section {
            background: linear-gradient(rgba(0,51,102,0.6), rgba(0,51,102,0.6)),
                        url('../assets/img/home-lab.jpg.jpg') center/cover no-repeat;
            height: 30vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            border-bottom-left-radius: 50px; /* lengkung kiri bawah */
            border-bottom-right-radius: 50px;
        }
    </style>
</head>

<body class="bg-light">

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
        <li class="nav-item"><a class="nav-link" href="public/kontak.php">Kontak</a></li>

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
            <h1 class="display-4 fw-bold">Berita Laboratorium</h1>
            <p class="lead">Ikuti kegiatan terbaru dan jadwal workshop kami</p>
        </div>
    </section>
<div class="container py-5">

<!-- Hanya Contoh -->
<div class="col-md-4">
    <div class="news-card shadow-sm">
        <img src="../assets/img/berita1.jpg" class="w-100 news-img">

        <div class="p-3">
            <small class="text-muted">November 17, 2025 • Admin Lab AI</small>

            <h5 class="mt-2">Tim Robotika Lab AI Raih Juara 1 Nasional</h5>

            <p class="text-muted" style="font-size: 14px;">
                Tim robotika dari Lab Applied Informatics berhasil meraih Juara 1 pada kompetisi Robot Nasional 2025...
            </p>

            <a href="#" class="text-primary">Read more</a>



    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Latest News</h3>
        <a href="#" class="text-danger">See all</a>
    </div>

    <div class="row g-4">

        <?php while ($b = pg_fetch_assoc($result)) : ?>
        <div class="col-md-4">
            <div class="news-card shadow-sm">

                <img src="<?= $b['gambar']; ?>" class="w-100 news-img" alt="gambar">

                <div class="p-3">
                    <small class="text-muted">
                        <?= date("F d, Y", strtotime($b['tanggal'])); ?> • <?= $b['penulis']; ?>
                    </small>

                    <h5 class="mt-2"><?= $b['judul']; ?></h5>

                    <p class="text-muted" style="font-size: 14px;">
                        <?= mb_strimwidth(strip_tags($b['isi']), 0, 120, "..."); ?>
                    </p>

                    <a href="detail_berita.php?id=<?= $b['id']; ?>" class="text-primary">Read more</a>
                </div>

            </div>
        </div>
        <?php endwhile; ?>

    </div>

</div>
</div>
    </div>
</div>
</body>
</html>
