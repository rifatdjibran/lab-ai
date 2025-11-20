<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin/login.php");
    exit();
}
?>

<!-- HEADER ADMIN -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold" href="../admin/index.php">
      <i class="bi bi-cpu me-2"></i>Lab AI Admin
    </a>

    <div class="d-flex align-items-center">
      <span class="text-white me-3">
        <i class="bi bi-person-circle me-1"></i>
        <?= $_SESSION['nama_admin'] ?>
      </span>

      <form action="../admin/login.php" method="POST" style="margin:0;">
        <button class="logout-btn">
          <div class="sign">
            <svg viewBox="0 0 512 512">
              <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1
              c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64
              c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96
              c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0
              c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path>
            </svg>
          </div>
          <div class="text">Logout</div>
        </button>
      </form>

    </div>
  </div>
</nav>
