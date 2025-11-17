<?php
require_once "../config/database.php";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Koneksi ke database gagal: " . pg_last_error());
}

$query = "SELECT * FROM galeri ORDER BY uploaded_at DESC";
$result = pg_query($conn, $query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Preview Galeri</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
.gallery-card {
    border-radius: 20px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    transition: 0.3s;
}
.gallery-card:hover {
    transform: translateY(-5px);
}
.gallery-img {
    height: 180px;
    width: 100%;
    object-fit: cover;
}
.gallery-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: white;
    padding: 5px 10px;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: bold;
    box-shadow: 0 3px 10px rgba(0,0,0,0.15);
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

</head>
    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div>
            <h1 class="display-4 fw-bold">Gallery Laboratorium</h1>
        </div>
    </section>
    <br>
    <br>
<div class="container">
  <div class="row g-4">

    <!-- CARD 1 -->
    <div class="col-md-4 col-sm-6">
      <div class="gallery-card position-relative">
        <div class="gallery-badge">JPG</div>
        <img class="gallery-img" src="../assets/img/berita3.jpg" />
        <div class="p-3">
          <h5>Oak Lake, USA</h5>
          <p class="text-muted mb-1" style="font-size: 0.9rem;">Mar 1, 2025</p>
          <p style="font-size: 0.9rem;">Laboratorium for Applied Informatics</p>
        </div>
      </div>
    </div>

    <!-- CARD 2 -->
    <div class="col-md-4 col-sm-6">
      <div class="gallery-card position-relative">
        <div class="gallery-badge">PNG</div>
        <img class="gallery-img" src="../assets/img/berita2.jpg" />
        <div class="p-3">
          <h5>Logar Valley</h5>
          <p class="text-muted mb-1" style="font-size: 0.9rem;">Mar 11, 2025</p>
          <p style="font-size: 0.9rem;">Laboratorium for Applied Informatics</p>
        </div>
      </div>
    </div>

    <!-- CARD 3 -->
    <div class="col-md-4 col-sm-6">
      <div class="gallery-card position-relative">
        <div class="gallery-badge">JPG</div>
        <img class="gallery-img" src="../assets/img/berita1.jpg" />
        <div class="p-3">
          <h5>Yosemite NP</h5>
          <p class="text-muted mb-1" style="font-size: 0.9rem;">Apr 7, 2025</p>
          <p style="font-size: 0.9rem;">Laboratorium for Applied Informatics</p>
        </div>
      </div>
    </div>

  </div>
</div>

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
