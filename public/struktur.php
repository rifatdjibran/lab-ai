<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<link rel="stylesheet" href="struktural.css">

<div class="page-content">

<!-- Hero -->
<section class="hero-section">
    <div class="text-center text-white">
        <h1 class="fw-bold">Struktur Organisasi Lengkap</h1>
        <p class="lead mt-2">Daftar lengkap anggota Lab-AI</p>
    </div>
</section>

<section class="container my-5">

<?php  
$leader = [
    "Ir. Yan Watequlis Syaifudin, S.T., M.MT., Ph.D",
    "Ketua Laboratorium",
    "../assets/img/tim/ketua.png"
];

$members = [
    ["Pramana Yoga Saputra, S.Kom., M.MT.", "Anggota", "../assets/img/tim/yoga.png"],
    ["Yuri Ariyanto, S.Kom., M.Kom.", "Anggota", "../assets/img/tim/yuri.png"],
    ["Triana Fatmawati, S.T., M.T.", "Anggota", "../assets/img/tim/triana.jpg"],
    ["M. Hasyim Ratsanjani, S.Kom., M.Kom.", "Anggota", "../assets/img/tim/hasyim.png"],
    ["Noprianto, S.Kom., M.Eng.", "Anggota", "../assets/img/tim/noprianto.png"],
    ["Mustika Mentari, S.Kom., M.Kom.", "Anggota", "../assets/img/tim/mustika.png"],
    ["Kadek Suarjuna Batubulan, S.Kom., MT", "Anggota", "../assets/img/tim/kadek.png"],
    ["Muhammad Afif Hendrawan, S.Kom., M.T.", "Anggota", "../assets/img/tim/afif.jpg"],
    ["Chandrasena Setiadi, S.T., M.Tr.T", "Anggota", "../assets/img/tim/chandrasena.jpg"],
    ["Retno Damayanti, S.Pd., M.T.", "Anggota", "../assets/img/tim/retno.jpg"]
];
?>

<!-- Ketua di atas -->
<div class="row justify-content-center mb-5">
    <div class="col-md-4 col-8">
        <div class="org-card shadow-sm">
            <img src="<?= $leader[2] ?>" alt="<?= $leader[0] ?>">
            <h6 class="org-title"><?= $leader[0] ?></h6>
            <small class="org-role"><?= $leader[1] ?></small>
            <a href="detail_struktur.php" class="org-more">Selengkapnya →</a>
        </div>
    </div>
</div>

<!-- Anggota di bawah -->
<div class="row g-4">
    <?php foreach ($members as $m): ?>
    <div class="col-md-3 col-6">
        <div class="org-card shadow-sm h-100">
            <img src="<?= $m[2] ?>" alt="<?= $m[0] ?>">
            <h6 class="org-title"><?= $m[0] ?></h6>
            <small class="org-role"><?= $m[1] ?></small>

            <a href="detail_struktur.php" class="org-more">Selengkapnya →</a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

</section>

</div>

<?php include '../includes/footer.php'; ?>
