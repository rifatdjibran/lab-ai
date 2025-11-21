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
    $all = [
      ["Ir. Yan Watequlis Syaifudin, S.T., M.MT., Ph.D", "Ketua Laboratorium", "../assets/img/banners/dosen1.jpg"],
      ["Pramana Yoga Saputra, S.Kom., M.MT.", "Anggota", "../assets/img/banners/laboran1.jpg"],
      ["Yuri Ariyanto, S.Kom., M.Kom.", "Anggota", "../assets/img/banners/anggota1.jpg"],
      ["Triana Fatmawati, S.T., M.T.", "Anggota", "../assets/img/banners/anggota2.jpg"],
      ["M. Hasyim Ratsanjani, S.Kom., M.Kom.", "Anggota", "../assets/img/banners/anggota3.jpg"],
      ["Noprianto, S.Kom., M.Eng.", "Anggota", "../assets/img/banners/anggota4.jpg"],
      ["Mustika Mentari, S.Kom., M.Kom.", "Anggota", "../assets/img/banners/anggota5.jpg"],
      ["Kadek Suarjuna Batubulan, S.Kom.,MT", "Anggota", "../assets/img/banners/anggota6.jpg"],
      ["Muhammad Afif Hendrawan, S.Kom., M.T.", "Anggota", "../assets/img/banners/anggota7.jpg"],
      ["Chandrasena Setiadi, S.T., M.Tr.T", "Anggota", "../assets/img/banners/anggota8.jpg"],
      ["Retno Damayanti, S.Pd. M.T.", "Anggota", "../assets/img/banners/anggota9.jpg"],
    ];

    foreach ($all as $x):
    ?>
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
