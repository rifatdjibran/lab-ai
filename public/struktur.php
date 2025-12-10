<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<!-- <link rel="stylesheet" href="struktural.css"> -->

<style>
.org-card img {
    width: 100%;
    height: 280px;
    object-fit: cover;
    object-position: 0 10%;
    border-radius: 0.5rem 0.5rem 0 0;
}

</style>

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
$members = [
    1 => ["nama"=>"Ir. Yan Watequlis Syaifudin, S.T., M.MT., Ph.D", "role"=>"Ketua Laboratorium", "img"=>"../assets/img/tim/ketua.png"],
    2 => ["nama"=>"Pramana Yoga Saputra, S.Kom., M.MT.", "role"=>"Anggota", "img"=>"../assets/img/tim/yoga.png"],
    3 => ["nama"=>"Yuri Ariyanto, S.Kom., M.Kom.", "role"=>"Anggota", "img"=>"../assets/img/tim/yuri.png"],
    4 => ["nama"=>"Triana Fatmawati, S.T., M.T.", "role"=>"Anggota", "img"=>"../assets/img/tim/triana.jpg"],  
    5 => ["nama"=>"Noprianto, S.Kom., M.Eng.", "role"=>"Anggota", "img"=>"../assets/img/tim/noprianto.png"],
    6 => ["nama"=>"Mustika Mentari, S.Kom., M.Kom.", "role"=>"Anggota", "img"=>"../assets/img/tim/mustika.png"],
    7 => ["nama"=>"Kadek Suarjuna Batubulan, S.Kom., MT", "role"=>"Anggota", "img"=>"../assets/img/tim/kadek.png"],
    8 => ["nama"=>"Muhammad Afif Hendrawan, S.Kom., M.T.", "role"=>"Anggota", "img"=>"../assets/img/tim/afif.jpg"],
    9 => ["nama"=>"Chandrasena Setiadi, S.T., M.Tr.T", "role"=>"Anggota", "img"=>"../assets/img/tim/chandrasena.jpg"],
];
?>

<!-- Ketua di atas -->
<div class="row justify-content-center mb-5">
    <div class="col-md-4 col-8">
        <div class="org-card shadow-sm">
            <img src="<?= $members[1]['img'] ?>" alt="<?= $members[1]['nama'] ?>">
            <h6 class="org-title"><?= $members[1]['nama'] ?></h6>
            <small class="org-role"><?= $members[1]['role'] ?></small>
            <a href="detail_struktur.php?id=1" class="org-more">Selengkapnya →</a>
        </div>
    </div>
</div>

<!-- Anggota lainnya -->
<div class="row g-4">
    <?php foreach ($members as $id=>$m): 
        if($id==1) continue; // Ketua sudah ditampilkan
    ?>
    <div class="col-md-3 col-6">
        <div class="org-card shadow-sm h-100">
            <img src="<?= $m['img'] ?>" alt="<?= $m['nama'] ?>">
            <h6 class="org-title"><?= $m['nama'] ?></h6>
            <small class="org-role"><?= $m['role'] ?></small>
            <a href="detail_struktur.php?id=<?= $id ?>" class="org-more">Selengkapnya →</a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

</section>
</div>

<?php include '../includes/footer.php'; ?>
