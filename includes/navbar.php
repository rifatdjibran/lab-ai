<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="assets/img/logo.png" alt="Logo" height="40" class="me-2">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="pages/profil.php">Struktur Organisasi</a></li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="penelitianPublikasiDropdown" role="button" data-bs-toggle="dropdown">
            Penelitian & Publikasi
          </a>
          <ul class="dropdown-menu" aria-labelledby="penelitianPublikasiDropdown">
            <li><a class="dropdown-item" href="pages/fasilitas.php#penelitian">Hasil Penelitian</a></li>
            <li><a class="dropdown-item" href="pages/kegiatan.php#publikasi">Publikasi Ilmiah</a></li>
          </ul>
        </li>

        <li class="nav-item"><a class="nav-link" href="pages/fasilitas.php">Fasilitas</a></li>
        <li class="nav-item"><a class="nav-link" href="pages/kegiatan.php">Agenda</a></li>
        <li class="nav-item"><a class="nav-link" href="pages/berita.php">Gallery</a></li>
        <li class="nav-item"><a class="nav-link" href="pages/kontak.php">Kontak</a></li>

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
