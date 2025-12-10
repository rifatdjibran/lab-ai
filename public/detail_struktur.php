<?php 
include '../includes/header.php'; 
include '../includes/navbar.php'; 
require_once '../config/database.php'; // Pastikan koneksi DB tersedia

// Ambil ID dari query string
$id = (int)($_GET['id'] ?? 1);

// --- FUNGSI PENGAMBIL DATA REKAM JEJAK ---
// Note: Kita akan menggunakan prepared statements untuk keamanan dan efisiensi

function getKaryaDosen($conn, $id_anggota, $jenis) {
    // Ambil Judul, Tahun, dan Nomor Dokumen (Nomor Dokumen hanya relevan untuk HAKI)
    $sql = "SELECT judul, tahun, nomor_dokumen FROM karya_dosen 
            WHERE id_anggota = $1 AND jenis_karya = $2 
            ORDER BY tahun DESC, judul ASC";
            
    $stmt = pg_prepare($conn, "get_karya_$jenis", $sql);
    $result = pg_execute($conn, "get_karya_$jenis", array($id_anggota, $jenis));
    
    return pg_fetch_all($result) ?: [];
}

function getPublikasi($conn, $id_anggota) {
    // Ambil Judul dan Tahun dari tabel publikasi
    $sql = "SELECT judul, tahun FROM publikasi 
            WHERE id_anggota_utama = $1 
            ORDER BY tahun DESC, judul ASC";
            
    $stmt = pg_prepare($conn, "get_publikasi", $sql);
    $result = pg_execute($conn, "get_publikasi", array($id_anggota));
    
    return pg_fetch_all($result) ?: [];
}


// --- 1. AMBIL DETAIL ANGGOTA (DATA UTAMA) ---
$query_member = "SELECT id, nama, jabatan, foto, email, pendidikan FROM struktur_organisasi WHERE id = $1";
$stmt_member = pg_prepare($conn, "get_member_detail", $query_member);
$member = pg_fetch_assoc(pg_execute($conn, "get_member_detail", array($id)));

if (!$member) {
    // Jika anggota tidak ditemukan, alihkan ke daftar atau tampilkan error
    header("Location: struktur.php");
    exit();
}

// --- 2. AMBIL SEMUA DATA REKAM JEJAK (Untuk tab) ---
$rekam_jejak = [
    'publikasi' => getPublikasi($conn, $member['id']),
    'riset'     => getKaryaDosen($conn, $member['id'], 'Riset'),
    'ki'        => getKaryaDosen($conn, $member['id'], 'HAKI'), // KI = HAKI
    'ppm'       => getKaryaDosen($conn, $member['id'], 'PPM'),
    'aktivitas' => getKaryaDosen($conn, $member['id'], 'Aktivitas'),
];

?>

<style>
/* ... (STYLE CSS SAMA, TIDAK ADA PERUBAHAN) ... */
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
                <img src="<?= '../assets/img/tim/' . htmlspecialchars($member['foto']) ?>" class="detail-photo" alt="Foto">
                <div class="mt-3">
                    <span class="badge bg-primary px-3 py-2 rounded-pill"><?= htmlspecialchars($member['jabatan']) ?></span>
                </div>
            </div>
            <div class="col-md-8">
                <h3 class="fw-bold mb-3 text-dark"><?= htmlspecialchars($member['nama']) ?></h3>
                <table class="table table-borderless detail-table">
                    <tr>
                        <td width="30px"><i class="bi bi-envelope text-primary"></i></td>
                        <td class="fw-bold" width="150px">Email</td>
                        <td>: <?= htmlspecialchars($member['email']) ?></td>
                    </tr>
                    <tr>
                        <td><i class="bi bi-mortarboard text-primary"></i></td>
                        <td class="fw-bold">Pendidikan</td>
                        <td>: <?= htmlspecialchars($member['pendidikan']) ?></td>
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
            // Ambil data untuk tab saat ini dari array yang sudah diisi dari DB
            $data_tab = $rekam_jejak[$t] ?? [];
        ?>
        <div class="tab-pane fade <?= $t=='publikasi' ? 'show active':'' ?>" id="<?= $t ?>">
            <div class="card border-0 shadow-sm p-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" width="60">No</th>
                                <th>Judul / Deskripsi Kegiatan</th>
                                <?php if ($t == 'ki'): // Tambah kolom khusus untuk HAKI ?>
                                    <th class="text-center" width="200">No. Permohonan</th>
                                <?php endif; ?>
                                <th class="text-center" width="100">Tahun</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(!empty($data_tab)):
                                foreach($data_tab as $i=>$item): 
                            ?>
                            <tr>
                                <td class="text-center fw-bold text-muted"><?= $i+1 ?></td>
                                <td><?= htmlspecialchars($item['judul']) ?></td>
                                
                                <?php if ($t == 'ki'): // Tampilkan nomor permohonan untuk HAKI ?>
                                    <td class="text-center">
                                        <?= htmlspecialchars($item['nomor_dokumen'] ?? '-') ?>
                                    </td>
                                <?php endif; ?>
                                
                                <td class="text-center"><span class="badge bg-light text-dark border"><?= htmlspecialchars($item['tahun']) ?></span></td>
                            </tr>
                            <?php 
                                endforeach; 
                            else:
                            ?>
                            <tr><td colspan="<?= ($t == 'ki' ? 3 : 2) + 1 ?>" class="text-center text-muted py-4">Tidak ada data.</td></tr>
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