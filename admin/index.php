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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | Lab AI Admin</title>

  <link rel="icon" type="image/png" href="../assets/img/logoclear.png">
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
      font-family: "Poppins", sans-serif;
    }

    /* SIDEBAR COLLAPSE */
    .sidebar {
      width: 250px;
      transition: 0.3s ease;
      overflow: hidden;
    }

    .sidebar.collapsed {
      width: 80px;
    }

    .sidebar-text {
      transition: 0.2s ease;
    }

    .sidebar.collapsed .sidebar-text {
      opacity: 0;
      width: 0;
      pointer-events: none;
    }

    .sidebar.collapsed .sidebar-logo {
      margin-right: 0 !important;
    }

    #toggleSidebar i {
      transition: 0.3s;
    }
    .sidebar.collapsed #toggleSidebar i {
      transform: rotate(180deg);
    }

    #main-content {
      transition: margin-left 0.3s ease;
      margin-left: 5px;
    }

    #main-content.collapsed {
      margin-left: 50px;
    }

    /* NAVBAR */
    .navbar {
      background-color: #0d6efd;
    }
    .navbar .navbar-brand,
    .navbar .nav-link,
    .navbar .text-white {
      color: #fff !important;
    }

    /* CARD STYLE */
    .card {
      border: none;
      border-radius: 16px;
      transition: 0.25s ease;
    }

    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    .stat-card {
      min-height: 170px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .stat-card i {
      font-size: 2.2rem;
      color: #0d6efd;
    }

    .sidebar-link {
      padding: 10px 12px;
      border-radius: 8px;
      transition: 0.2s;
    }

    .sidebar-link:hover {
      background-color: #e7efff;
      color: #0d6efd !important;
    }
  </style>
</head>

<body>

<!-- NAVBAR -->
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
      <a href="login.php" class="btn btn-outline-light btn-sm">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </div>
  </div>
</nav>

<div class="d-flex">

  <!-- SIDEBAR -->
  <?php include "../includes/sidebar.php"; ?>

  <!-- MAIN CONTENT -->
  <div id="main-content" class="p-4 flex-grow-1">
    <h3 class="fw-bold mb-3">Dashboard</h3>
    <p class="text-muted mb-4">Selamat Datang Kembali Admin!</p>
    <p>Berikut ringkasan data pada sistem portal Lab AI.</p>

    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">

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
          <div class='col'>
            <div class='card shadow-sm stat-card'>
              <div class='card-body text-center'>
                <i class='bi $icon'></i>
                <h5 class='fw-bold'>$label</h5>
                <p class='display-6 fw-bold text-primary mb-0'>$count</p>
              </div>
            </div>
          </div>
          ";
      }
      ?>

    </div>

    <div class="mt-5">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h5 class="card-title mb-3">
            <i class="bi bi-info-circle me-2"></i>Informasi Umum
          </h5>
          <p>
            Portal Laboratorium AI ini dikembangkan untuk mempermudah pengelolaan data seperti berita, kegiatan, galeri, publikasi, dan penelitian.
            <br>Gunakan menu di sidebar untuk mengelola konten sesuai kebutuhan.
          </p>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
document.getElementById("toggleSidebar").addEventListener("click", () => {
  const sidebar = document.getElementById("sidebar");
  const content = document.getElementById("main-content");

  sidebar.classList.toggle("collapsed");
  content.classList.toggle("collapsed");
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
