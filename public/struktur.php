<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="page-content">

<section class="py-5 text-center bg-light">
  <div class="container">
    <h2 class="fw-bold">Struktur Organisasi Lengkap</h2>
    <p class="text-muted">Daftar lengkap anggota Lab-AI</p>
  </div>
</section>

<section class="container my-5">
  <div class="row g-4">

    <?php
    // daftar lengkap semua anggota
    $all = [
      ["Nama", "Ketua Laboratorium", "../assets/img/banners/dosen1.jpg"],
      ["Nama", "Anggota", "../assets/img/banners/laboran1.jpg"],
      ["Nama", "Anggota", "../assets/img/banners/anggota1.jpg"],
      ["Nama", "Anggota", "../assets/img/banners/anggota2.jpg"],
      ["Nama", "Anggota", "../assets/img/banners/anggota3.jpg"],
      ["Nama", "Anggota", "../assets/img/banners/anggota4.jpg"],
      ["Nama", "Anggota", "../assets/img/banners/anggota5.jpg"]
    ];

    foreach ($all as $x): ?>
      <div class="col-md-3 col-6">
        <div class="card shadow-sm h-100">
          <img src="<?= $x[2] ?>" class="card-img-top rounded-top" alt="<?= $x[0] ?>">
          <div class="card-body text-center">
            <h6 class="fw-semibold mb-0"><?= $x[0] ?></h6>
            <small class="text-muted"><?= $x[1] ?></small>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
    
  </div>
</section>

</div>

<?php include '../includes/footer.php'; ?>
