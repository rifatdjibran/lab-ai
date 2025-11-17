<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">

    <a class="navbar-brand d-flex align-items-center" href="/lab-ai/index.php">
      <img src="/lab-ai/assets/img/logo.png" alt="Logo" height="40" class="me-2">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">

        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>" 
             href="/lab-ai/index.php">Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'struktur.php') ? 'active' : '' ?>" 
             href="/lab-ai/public/struktur.php">Struktur Organisasi</a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?= in_array($current_page, ['hasilPenelitian.php','publikasiIlmiah.php']) ? 'active' : '' ?>" 
             href="#" data-bs-toggle="dropdown">
            Penelitian & Publikasi
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/lab-ai/public/hasilPenelitian.php#penelitian">Hasil Penelitian</a></li>
            <li><a class="dropdown-item" href="/lab-ai/public/publikasiIlmiah.php#publikasi">Publikasi Ilmiah</a></li>
          </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'fasilitas.php') ? 'active' : '' ?>" 
             href="/lab-ai/public/fasilitas.php">Fasilitas</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'kegiatan.php') ? 'active' : '' ?>" 
             href="/lab-ai/public/kegiatan.php">Agenda</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'berita.php') ? 'active' : '' ?>" 
             href="/lab-ai/public/berita.php">Gallery</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'kontak.php') ? 'active' : '' ?>" 
             href="/lab-ai/public/kontak.php">Kontak</a>
        </li>

        <li class="nav-item ms-lg-3">
          <a href="/lab-ai/admin/login.php" class="btn btn-outline-primary btn-sm px-3">
            <i class="bi bi-person-lock me-1"></i> Login Admin
          </a>
        </li>

      </ul>
    </div>

  </div>
</nav>
