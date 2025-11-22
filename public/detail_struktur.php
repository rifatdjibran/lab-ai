<?php include '../includes/header.php'; ?> 
<?php include '../includes/navbar.php'; ?>

<link rel="stylesheet" href="struktural_detail.css">

<?php
// Data anggota dan detail publikasi/riset/KI/PPM/Aktivitas
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
        "nama"=>"M. Hasyim Ratsanjani, S.Kom., M.Kom.",
        "role"=>"Anggota",
        "img"=>"../assets/img/tim/hasyim.png",
        "email"=>"hasyim@example.com",
        "pendidikan"=>"S2 Teknik Informatika",
        "linkedin"=>"#",
        "scholar"=>"#",
        "publikasi"=>array_map(fn($i)=>["Publikasi Hasyim $i",2020+$i%4],range(1,10)),
        "riset"=>array_map(fn($i)=>["Riset Hasyim $i",2020+$i%4],range(1,10)),
        "ki"=>array_map(fn($i)=>["KI Hasyim $i",2020+$i%4],range(1,10)),
        "ppm"=>array_map(fn($i)=>["PPM Hasyim $i",2020+$i%4],range(1,10)),
        "aktivitas"=>array_map(fn($i)=>["Aktivitas Hasyim $i",2020+$i%4],range(1,10)),
    ],
    6 => [
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
    7 => [
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
    8 => [
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
    9 => [
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
    10 => [
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
    11 => [
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
    ]
];

// Ambil ID dari query string
$id = $_GET['id'] ?? 1;
$member = $members[$id] ?? $members[1];
?>

<div class="page-content container my-5">

    <!-- CARD PROFIL -->
    <div class="detail-card shadow-sm p-4 mb-4 rounded-4">
        <div class="row align-items-center">
            <div class="col-md-3 text-center">
                <img src="<?= $member['img'] ?>" class="detail-photo" alt="Foto">
            </div>
            <div class="col-md-9">
                <table class="table table-borderless detail-table">
                    <tr>
                        <td class="w-25 fw-bold">Nama</td>
                        <td>: <?= $member['nama'] ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Email</td>
                        <td>: <?= $member['email'] ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Pendidikan Terakhir</td>
                        <td>: <?= $member['pendidikan'] ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="text-center mb-4">
        <a href="<?= $member['linkedin'] ?>" class="action-btn me-2">Linkedin</a>
        <a href="<?= $member['scholar'] ?>" class="action-btn">Google Scholar</a>
    </div>

    <!-- TAB MENU -->
    <ul class="nav nav-tabs custom-tabs mb-3">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#publikasi">Publikasi</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#riset">Riset</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#ki">Kekayaan Intelektual</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#ppm">PPM</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#aktivitas">Aktivitas</a></li>
    </ul>

    <!-- TAB CONTENT -->
    <div class="tab-content">

        <?php
        $tabs = ['publikasi','riset','ki','ppm','aktivitas'];
        foreach($tabs as $t):
        ?>
        <div class="tab-pane fade <?= $t=='publikasi' ? 'show active':'' ?>" id="<?= $t ?>">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Judul</th>
                            <th class="text-center">Tahun</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach(array_slice($member[$t],0,10) as $i=>$item): ?>
                        <tr>
                            <td class="text-center"><?= $i+1 ?></td>
                            <td><?= $item[0] ?></td>
                            <td class="text-center"><?= $item[1] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; ?>

    </div>

</div>

<?php include '../includes/footer.php'; ?>
