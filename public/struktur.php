<?php 
include '../includes/header.php';
include '../includes/navbar.php';
?>

<style>
    /* 1. Hero Section (Konsisten) */
    .hero-section {
        background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
        padding: 80px 0;
        margin-bottom: 2rem;
        color: white;
    }

    /* 2. Styling Card Struktur */
    .org-card {
        height: 100%;
        border: none;
        border-radius: 15px;
        background-color: #fff;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        /* Tambahkan aksen warna di bawah gambar */
        border-bottom: 5px solid #0d6efd; 
    }

    .org-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    /* Gambar Profil */
    .org-card img {
        width: 100%;
        height: 280px;
        object-fit: cover;
        object-position: center top; /* Fokus ke wajah bagian atas */
        background-color: #f8f9fa;
    }

    /* Body Card */
    .org-card .card-body {
        padding: 1.5rem;
        text-align: center;
    }

    /* Nama Anggota */
    .org-title {
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
        font-size: 1.1rem;
    }

    /* Role/Jabatan */
    .org-role {
        display: block;
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 15px;
        font-weight: 500;
    }

    /* Tombol Selengkapnya */
    .org-more {
        text-decoration: none;
        color: #0d6efd;
        font-weight: 600;
        font-size: 0.9rem;
        transition: color 0.2s;
    }
    .org-more:hover {
        color: #0043a8;
        text-decoration: underline;
    }
</style>

<section class="hero-section text-center">
    <div class="container">
        <h1 class="fw-bold display-5">Struktur Organisasi</h1>
        <p class="lead opacity-75 mt-2">Daftar lengkap dosen dan anggota Laboratorium AI.</p>
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
        10 => ["nama"=>"Retno Damayanti, S.Pd., M.T.", "role"=>"Anggota", "img"=>"../assets/img/tim/retno.jpg"],
    ];
    ?>

    <div class="text-center mb-5">
        <h2 class="fw-bold" style="color: #333;">Tim <span class="text-primary">Laboratorium</span></h2>
        <div style="width: 60px; height: 3px; background: #0d6efd; margin: 10px auto;"></div>
    </div>

    <div class="row justify-content-center mb-5">
        <div class="col-md-4 col-10">
            <div class="org-card shadow text-center">
                <img src="<?= $members[1]['img'] ?>" alt="<?= $members[1]['nama'] ?>">
                <div class="card-body">
                    <span class="badge bg-primary mb-2">KETUA LAB</span>
                    <h5 class="org-title"><?= $members[1]['nama'] ?></h5>
                    <small class="org-role"><?= $members[1]['role'] ?></small>
                    <a href="detail_struktur.php?id=1" class="btn btn-outline-primary btn-sm mt-3 rounded-pill px-4">
                        Lihat Profil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mb-4 fw-bold text-center text-muted">Anggota Laboratorium</h4>
    <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-md-4 justify-content-center">
        <?php foreach ($members as $id=>$m): 
            if($id==1) continue; 
        ?>
        <div class="col">
            <div class="org-card shadow-sm h-100">
                <img src="<?= $m['img'] ?>" alt="<?= $m['nama'] ?>">
                <div class="card-body">
                    <h6 class="org-title"><?= $m['nama'] ?></h6>
                    <small class="org-role"><?= $m['role'] ?></small>
                    <div class="mt-3">
                        <a href="detail_struktur.php?id=<?= $id ?>" class="org-more">Selengkapnya →</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</section>

<?php include '../includes/footer.php'; ?>