<?php 
include '../includes/header.php'; 
include '../includes/navbar.php'; 

// DATA DUMMY LENGKAP
$members = [
    1 => [
        "nama"=>"Ir. Yan Watequlis Syaifudin, S.T., M.MT., Ph.D",
        "role"=>"Ketua Laboratorium",
        "img"=>"../assets/img/tim/ketua.png",
        "email"=>"yan@example.com",
        "pendidikan"=>"S3 Teknik Informatika",
        "linkedin"=>"#",
        "scholar"=>"#",
        "publikasi"=>array_map(fn($i)=>["Publikasi Ketua $i",2020+$i%4],range(1,10)),
        "riset"=>array_map(fn($i)=>["Riset Ketua $i",2020+$i%4],range(1,10)),
        "ki"=>array_map(fn($i)=>["KI Ketua $i",2020+$i%4],range(1,10)),
        "ppm"=>array_map(fn($i)=>["PPM Ketua $i",2020+$i%4],range(1,10)),
        "aktivitas"=>array_map(fn($i)=>["Aktivitas Ketua $i",2020+$i%4],range(1,10)),
    ],
    2 => [
        "nama"=>"Pramana Yoga Saputra, S.Kom., M.MT.",
        "role"=>"Anggota",
        "img"=>"../assets/img/tim/yoga.png",
        "email"=>"yoga@example.com",
        "pendidikan"=>"S2 Teknik Informatika",
        "linkedin"=>"#",
        "scholar"=>"#",
        "publikasi"=>array_map(fn($i)=>["Publikasi Yoga $i",2019+$i%4],range(1,10)),
        "riset"=>array_map(fn($i)=>["Riset Yoga $i",2019+$i%4],range(1,10)),
        "ki"=>array_map(fn($i)=>["KI Yoga $i",2019+$i%4],range(1,10)),
        "ppm"=>array_map(fn($i)=>["PPM Yoga $i",2019+$i%4],range(1,10)),
        "aktivitas"=>array_map(fn($i)=>["Aktivitas Yoga $i",2019+$i%4],range(1,10)),
    ],
    3 => [
        "nama"=>"Yuri Ariyanto, S.Kom., M.Kom.",
        "role"=>"Anggota",
        "img"=>"../assets/img/tim/yuri.png",
        "email"=>"yuri@example.com",
        "pendidikan"=>"S2 Teknik Informatika",
        "linkedin"=>"#",
        "scholar"=>"#",
        "publikasi"=>array_map(fn($i)=>["Publikasi Yuri $i",2020+$i%4],range(1,10)),
        "riset"=>array_map(fn($i)=>["Riset Yuri $i",2020+$i%4],range(1,10)),
        "ki"=>array_map(fn($i)=>["KI Yuri $i",2020+$i%4],range(1,10)),
        "ppm"=>array_map(fn($i)=>["PPM Yuri $i",2020+$i%4],range(1,10)),
        "aktivitas"=>array_map(fn($i)=>["Aktivitas Yuri $i",2020+$i%4],range(1,10)),
    ],
    4 => [
        "nama"=>"Triana Fatmawati, S.T., M.T.",
        "role"=>"Anggota",
        "img"=>"../assets/img/tim/triana.jpg",
        "email"=>"triana@example.com",
        "pendidikan"=>"S2 Teknik Informatika",
        "linkedin"=>"#",
        "scholar"=>"#",
        "publikasi"=>array_map(fn($i)=>["Publikasi Triana $i",2020+$i%4],range(1,10)),
        "riset"=>array_map(fn($i)=>["Riset Triana $i",2020+$i%4],range(1,10)),
        "ki"=>array_map(fn($i)=>["KI Triana $i",2020+$i%4],range(1,10)),
        "ppm"=>array_map(fn($i)=>["PPM Triana $i",2020+$i%4],range(1,10)),
        "aktivitas"=>array_map(fn($i)=>["Aktivitas Triana $i",2020+$i%4],range(1,10)),
    ],
    5 => [
        "nama"=>"Noprianto, S.Kom., M.Eng.",
        "role"=>"Anggota",
        "img"=>"../assets/img/tim/noprianto.png",
        "email"=>"noprianto@example.com",
        "pendidikan"=>"S2 Teknik Informatika",
        "linkedin"=>"#",
        "scholar"=>"#",
        "publikasi"=>array_map(fn($i)=>["Publikasi Noprianto $i",2020+$i%4],range(1,10)),
        "riset"=>array_map(fn($i)=>["Riset Noprianto $i",2020+$i%4],range(1,10)),
        "ki"=>array_map(fn($i)=>["KI Noprianto $i",2020+$i%4],range(1,10)),
        "ppm"=>array_map(fn($i)=>["PPM Noprianto $i",2020+$i%4],range(1,10)),
        "aktivitas"=>array_map(fn($i)=>["Aktivitas Noprianto $i",2020+$i%4],range(1,10)),
    ],
    6 => [
        "nama"=>"Mustika Mentari, S.Kom., M.Kom.",
        "role"=>"Anggota",
        "img"=>"../assets/img/tim/mustika.png",
        "email"=>"mustika@example.com",
        "pendidikan"=>"S2 Teknik Informatika",
        "linkedin"=>"#",
        "scholar"=>"#",
        "publikasi"=>array_map(fn($i)=>["Publikasi Mustika $i",2020+$i%4],range(1,10)),
        "riset"=>array_map(fn($i)=>["Riset Mustika $i",2020+$i%4],range(1,10)),
        "ki"=>array_map(fn($i)=>["KI Mustika $i",2020+$i%4],range(1,10)),
        "ppm"=>array_map(fn($i)=>["PPM Mustika $i",2020+$i%4],range(1,10)),
        "aktivitas"=>array_map(fn($i)=>["Aktivitas Mustika $i",2020+$i%4],range(1,10)),
    ],
    7 => [
        "nama"=>"Kadek Suarjuna Batubulan, S.Kom., MT",
        "role"=>"Anggota",
        "img"=>"../assets/img/tim/kadek.png",
        "email"=>"kadek@example.com",
        "pendidikan"=>"S2 Teknik Informatika",
        "linkedin"=>"#",
        "scholar"=>"#",
        "publikasi"=>array_map(fn($i)=>["Publikasi Kadek $i",2020+$i%4],range(1,10)),
        "riset"=>array_map(fn($i)=>["Riset Kadek $i",2020+$i%4],range(1,10)),
        "ki"=>array_map(fn($i)=>["KI Kadek $i",2020+$i%4],range(1,10)),
        "ppm"=>array_map(fn($i)=>["PPM Kadek $i",2020+$i%4],range(1,10)),
        "aktivitas"=>array_map(fn($i)=>["Aktivitas Kadek $i",2020+$i%4],range(1,10)),
    ],
    8 => [
        "nama"=>"Muhammad Afif Hendrawan, S.Kom., M.T.",
        "role"=>"Anggota",
        "img"=>"../assets/img/tim/afif.jpg",
        "email"=>"afif@example.com",
        "pendidikan"=>"S2 Teknik Informatika",
        "linkedin"=>"#",
        "scholar"=>"#",
        "publikasi"=>array_map(fn($i)=>["Publikasi Afif $i",2020+$i%4],range(1,10)),
        "riset"=>array_map(fn($i)=>["Riset Afif $i",2020+$i%4],range(1,10)),
        "ki"=>array_map(fn($i)=>["KI Afif $i",2020+$i%4],range(1,10)),
        "ppm"=>array_map(fn($i)=>["PPM Afif $i",2020+$i%4],range(1,10)),
        "aktivitas"=>array_map(fn($i)=>["Aktivitas Afif $i",2020+$i%4],range(1,10)),
    ],
    9 => [
        "nama"=>"Chandrasena Setiadi, S.T., M.Tr.T",
        "role"=>"Anggota",
        "img"=>"../assets/img/tim/chandrasena.jpg",
        "email"=>"chandrasena@example.com",
        "pendidikan"=>"S2 Teknik Informatika",
        "linkedin"=>"#",
        "scholar"=>"#",
        "publikasi"=>array_map(fn($i)=>["Publikasi Chandrasena $i",2020+$i%4],range(1,10)),
        "riset"=>array_map(fn($i)=>["Riset Chandrasena $i",2020+$i%4],range(1,10)),
        "ki"=>array_map(fn($i)=>["KI Chandrasena $i",2020+$i%4],range(1,10)),
        "ppm"=>array_map(fn($i)=>["PPM Chandrasena $i",2020+$i%4],range(1,10)),
        "aktivitas"=>array_map(fn($i)=>["Aktivitas Chandrasena $i",2020+$i%4],range(1,10)),
    ],
    // Data dummy untuk ID 10 & 11 jika diakses
    10 => [
        "nama"=>"Retno Damayanti, S.Pd., M.T.",
        "role"=>"Anggota",
        "img"=>"../assets/img/tim/retno.jpg",
        "email"=>"retno@example.com",
        "pendidikan"=>"S2 Teknik Informatika",
        "linkedin"=>"#",
        "scholar"=>"#",
        "publikasi"=>array_map(fn($i)=>["Publikasi Retno $i",2020+$i%4],range(1,10)),
        "riset"=>array_map(fn($i)=>["Riset Retno $i",2020+$i%4],range(1,10)),
        "ki"=>array_map(fn($i)=>["KI Retno $i",2020+$i%4],range(1,10)),
        "ppm"=>array_map(fn($i)=>["PPM Retno $i",2020+$i%4],range(1,10)),
        "aktivitas"=>array_map(fn($i)=>["Aktivitas Retno $i",2020+$i%4],range(1,10)),
    ],
];

// Fallback jika ID tidak ditemukan (agar tidak error)
for($i=11; $i<=15; $i++){
    if(!isset($members[$i])) {
        $members[$i] = $members[1]; 
        $members[$i]['nama'] = "Anggota Dummy $i";
    }
}

// Ambil ID dari query string
$id = $_GET['id'] ?? 1;
$member = $members[$id] ?? $members[1];
?>

<style>
    /* CSS Tambahan Khusus Halaman Ini */
    
/* --- PERUBAHAN DESAIN TOMBOL KEMBALI (GLASSY GRAY) --- */
    .btn-back-custom {
        text-decoration: none;
        color: #555; /* Warna teks abu tua */
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        padding: 10px 25px; /* Padding agar berbentuk tombol */
        border-radius: 50px; /* Sudut membulat */
        
        /* Desain Glassy Abu-abu */
        background: rgba(0, 0, 0, 0.05); /* Latar belakang abu-abu sangat transparan */
        border: 1px solid rgba(0, 0, 0, 0.08); /* Border halus */
        backdrop-filter: blur(5px); /* Efek blur kaca (opsional, terlihat jika ada elemen di belakangnya) */
        box-shadow: 0 2px 5px rgba(0,0,0,0.05); /* Bayangan tipis */
        
        transition: all 0.3s ease;
    }

    .btn-back-custom:hover {
        background: rgba(0, 0, 0, 0.1); /* Sedikit lebih gelap saat hover */
        color: #222; /* Teks lebih gelap */
        transform: translateY(-3px); /* Efek naik sedikit */
        box-shadow: 0 5px 15px rgba(0,0,0,0.1); /* Bayangan lebih tegas */
    }

    .btn-back-custom i {
        margin-right: 10px; /* Jarak antara ikon dan teks */
    }
    /* -------------------------------------------------- */

    /* Card Profil Utama (Tanpa Overlap, Flat Design) */
    .profile-card-main {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        padding: 30px;
        border: none;
    }

    /* Foto Profil */
    .detail-photo {
        width: 180px;
        height: 180px;
        object-fit: cover;
        border-radius: 15px;
        border: 1px solid #eee;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    /* Tabel Detail */
    .detail-table td {
        padding: 10px 5px;
        vertical-align: middle;
        font-size: 1rem;
    }

    /* Nav Tabs Custom */
    .custom-tabs .nav-link {
        color: #555;
        font-weight: 600;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 12px 20px;
    }
    .custom-tabs .nav-link.active {
        color: #0d6efd;
        background: none;
        border-bottom: 3px solid #0d6efd;
    }
    .custom-tabs .nav-link:hover {
        color: #0d6efd;
    }
</style>

<section class="hero-section text-center" style="background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%); padding: 80px 0; margin-bottom: 2rem; color: white;">
    <div class="container">
        <h1 class="fw-bold display-5">Profile Dosen</h1>
        <p class="lead opacity-75 mt-2">Detail informasi dan rekam jejak akademik.</p>
    </div>
</section>

<div class="container pb-5">

    <div class="mb-4">
        <a href="struktur.php" class="btn-back-custom">
            <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="profile-card-main mb-5">
        <div class="row align-items-center">
            <div class="col-md-4 text-center mb-3 mb-md-0">
                <img src="<?= $member['img'] ?>" class="detail-photo" alt="Foto">
                <div class="mt-3">
                    <span class="badge bg-primary px-3 py-2 rounded-pill"><?= $member['role'] ?></span>
                </div>
            </div>
            <div class="col-md-8">
                <h3 class="fw-bold mb-3 text-dark"><?= $member['nama'] ?></h3>
                <table class="table table-borderless detail-table">
                    <tr>
                        <td width="30px"><i class="bi bi-envelope text-primary"></i></td>
                        <td class="fw-bold" width="150px">Email</td>
                        <td>: <?= $member['email'] ?></td>
                    </tr>
                    <tr>
                        <td><i class="bi bi-mortarboard text-primary"></i></td>
                        <td class="fw-bold">Pendidikan</td>
                        <td>: <?= $member['pendidikan'] ?></td>
                    </tr>
                    <tr>
                        <td><i class="bi bi-link-45deg text-primary"></i></td>
                        <td class="fw-bold">Profil Lain</td>
                        <td>
                            <a href="<?= $member['linkedin'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-linkedin"></i> LinkedIn</a>
                            <a href="<?= $member['scholar'] ?>" class="btn btn-sm btn-outline-info"><i class="bi bi-google"></i> Scholar</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs custom-tabs mb-4 justify-content-center">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#publikasi">Publikasi</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#riset">Riset</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#ki">HAKI</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#ppm">PPM</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#aktivitas">Aktivitas</a></li>
    </ul>

    <div class="tab-content">
        <?php
        $tabs = ['publikasi','riset','ki','ppm','aktivitas'];
        foreach($tabs as $t):
        ?>
        <div class="tab-pane fade <?= $t=='publikasi' ? 'show active':'' ?>" id="<?= $t ?>">
            <div class="card border-0 shadow-sm p-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" width="60">No</th>
                                <th>Judul / Deskripsi Kegiatan</th>
                                <th class="text-center" width="100">Tahun</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(isset($member[$t]) && is_array($member[$t])):
                                foreach(array_slice($member[$t],0,10) as $i=>$item): 
                            ?>
                            <tr>
                                <td class="text-center fw-bold text-muted"><?= $i+1 ?></td>
                                <td><?= $item[0] ?></td>
                                <td class="text-center"><span class="badge bg-light text-dark border"><?= $item[1] ?></span></td>
                            </tr>
                            <?php 
                                endforeach; 
                            else:
                            ?>
                            <tr><td colspan="3" class="text-center text-muted py-4">Tidak ada data.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<?php include '../includes/footer.php'; ?>