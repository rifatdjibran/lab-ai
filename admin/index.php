<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit();
}
require_once "../config/database.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard | Lab AI Admin</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: "Poppins", sans-serif;
    }
    .navbar {
      background-color: #0d6efd;
    }
    .navbar .navbar-brand, .navbar .nav-link, .navbar .text-white {
      color: #fff !important;
    }
    .card {
      border: none;
      border-radius: 12px;
      transition: transform 0.2s ease-in-out;
    }
    .card:hover {
      transform: translateY(-5px);
    }
    .stat-card i {
      font-size: 2.5rem;
      color: #0d6efd;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold" href="index.php">
      <i class="bi bi-cpu me-2"></i>Lab AI Admin
    </a>
    <div class="d-flex align-items-center">
      <span class="text-white me-3">
        <i class="bi bi-person-circle me-1"></i>
        <?= $_SESSION['nama_admin'] ?>
      </span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </div>
  </div>
</nav>

<div class="d-flex">
  <!-- Sidebar -->
  <?php include "../includes/sidebar.php"; ?>

  <!-- Main Content -->
  <div class="p-4 flex-grow-1">
    <h3 class="fw-bold mb-3">Dashboard</h3>
    <p class="text-muted mb-4">Selamat datang kembali, <strong><?= $_SESSION['nama_admin'] ?></strong>!  
    Berikut ringkasan data pada sistem portal Lab AI.</p>

    <div class="row g-4">
      <?php
      $cards = [
        ['berita', 'Berita', 'bi-newspaper'],
        ['kegiatan', 'Kegiatan', 'bi-calendar-event'],
        ['galeri', 'Galeri', 'bi-images'],
        ['penelitian', 'Penelitian', 'bi-journal-text'],
        ['publikasi', 'Publikasi', 'bi-journal-medical']
      ];

      foreach ($cards as $item) {
          $tbl = $item[0];
          $label = $item[1];
          $icon = $item[2];

          $res = pg_query($conn, "SELECT COUNT(*) FROM $tbl");
          $count = pg_fetch_result($res, 0, 0);

          echo "
          <div class='col-md-4 col-lg-3'>
            <div class='card shadow-sm stat-card'>
              <div class='card-body text-center'>
                <i class='bi $icon mb-3'></i>
                <h5 class='fw-bold'>$label</h5>
                <p class='display-6 fw-bold text-primary mb-0'>$count</p>
              </div>
            </div>
          </div>";
      }
      ?>
    </div>

    <div class="mt-5">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h5 class="card-title mb-3"><i class="bi bi-info-circle me-2"></i>Informasi Umum</h5>
          <p>Portal Laboratorium AI ini dikembangkan untuk mempermudah pengelolaan data seperti berita, kegiatan, galeri, publikasi, dan penelitian.
            <br>Gunakan menu di sidebar untuk mengelola konten sesuai kebutuhan.</p>
        </div>
      </div>
    </div>

  </div>
</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
