<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="page-content">

<!-- Hero Section -->
<section class="hero-berita">
    <div class="text-center text-white">
        <h1 class="fw-bold">Struktur Organisasi Lengkap</h1>
        <p class="lead mt-2">Daftar lengkap anggota Lab-AI</p>
    </div>
</section>

<section class="container my-5">
  <div class="row g-4">

    <?php
    $all = [
    ["Ir. Yan Watequlis Syaifudin, S.T., M.MT., Ph.D", "Ketua Laboratorium", "../assets/img/tim/ketua.png"],
    ["Pramana Yoga Saputra, S.Kom., M.MT.", "Anggota", "../assets/img/tim/yoga.png"],
    ["Yuri Ariyanto, S.Kom., M.Kom.", "Anggota", "../assets/img/tim/yuri.png"],
    ["Triana Fatmawati, S.T., M.T.", "Anggota", "../assets/img/tim/triana.jpg"],
    ["M. Hasyim Ratsanjani, S.Kom., M.Kom.", "Anggota", "../assets/img/tim/hasyim.png"],
    ["Noprianto, S.Kom., M.Eng.", "Anggota", "../assets/img/tim/noprianto.png"],
    ["Mustika Mentari, S.Kom., M.Kom.", "Anggota", "../assets/img/tim/mustika.png"],
    ["Kadek Suarjuna Batubulan, S.Kom.,MT", "Anggota", "../assets/img/tim/kadek.png"],
    ["Muhammad Afif Hendrawan, S.Kom., M.T.", "Anggota", "../assets/img/tim/afif.jpg"],
    ["Chandrasena Setiadi, S.T., M.Tr.T", "Anggota", "../assets/img/tim/chandrasena.jpg"],
    ["Retno Damayanti, S.Pd. M.T.", "Anggota", "../assets/img/tim/retno.jpg"]
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
