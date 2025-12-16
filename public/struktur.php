<?php 
include '../includes/header.php';
include '../includes/navbar.php';
require_once '../config/database.php'; // Pastikan koneksi DB tersedia

// --- 1. AMBIL DATA KETUA LAB ---
// Mencari anggota dengan jabatan 'Ketua Laboratorium' atau 'Ketua Lab'
$query_ketua = "
    SELECT id, nama, jabatan, foto
    FROM struktur_organisasi
    WHERE jabatan ILIKE '%ketua%'
    ORDER BY urutan ASC
    LIMIT 1
";
$result_ketua = pg_query($conn, $query_ketua);
$ketua = pg_fetch_assoc($result_ketua);

// --- 2. AMBIL DATA ANGGOTA LAIN ---
// Ambil semua anggota kecuali yang jabatannya Ketua
$query_anggota = "
    SELECT id, nama, jabatan, foto
    FROM struktur_organisasi
    WHERE jabatan NOT ILIKE '%ketua%'
    ORDER BY urutan ASC, nama ASC
";
$result_anggota = pg_query($conn, $query_anggota);
?>

<style>
/* ... (STYLE CSS SAMA, TIDAK ADA PERUBAHAN) ... */
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
        <h1 class="fw-bold display-5">Member Laboratorium</h1>
        <p class="lead opacity-75 mt-2">Daftar lengkap dosen dan anggota Laboratorium AI.</p>
    </div>
</section>

<section class="container my-5">

    <div class="text-center mb-5">
        <h2 class="fw-bold" style="color: #333;">Tim <span class="text-primary">Laboratorium</span></h2>
        <div style="width: 60px; height: 3px; background: #0d6efd; margin: 10px auto;"></div>
    </div>

    <?php if ($ketua): ?>
    <h4 class="mb-4 fw-bold text-center text-muted">Ketua Laboratorium</h4>

    <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-md-4 justify-content-center mb-5">
        <div class="col">
            <div class="org-card shadow-sm h-100">
                <img src="<?= '../assets/img/tim/' . htmlspecialchars($ketua['foto']) ?>" 
                     alt="<?= htmlspecialchars($ketua['nama']) ?>">

                <div class="card-body">
                    <span class="badge bg-primary mb-2">KETUA LAB</span>
                    <h6 class="org-title"><?= htmlspecialchars($ketua['nama']) ?></h6>
                    <small class="org-role"><?= htmlspecialchars($ketua['jabatan']) ?></small>

                    <div class="mt-3">
                        <a href="detail_struktur.php?id=<?= $ketua['id'] ?>" class="org-more">
                            Selengkapnya →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
        <p class="text-center text-danger">Data Ketua Laboratorium belum diisi di database.</p>
    <?php endif; ?>

    <h4 class="mb-4 fw-bold text-center text-muted">Anggota Laboratorium</h4>
    <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-md-4 justify-content-center">
        <?php if (pg_num_rows($result_anggota) > 0): ?>
            <?php while ($m = pg_fetch_assoc($result_anggota)): ?>
                <div class="col">
                    <div class="org-card shadow-sm h-100">
                        <img src="<?= '../assets/img/tim/' . htmlspecialchars($m['foto']) ?>" 
                            alt="<?= htmlspecialchars($m['nama']) ?>">
                        <div class="card-body">
                            <h6 class="org-title"><?= htmlspecialchars($m['nama']) ?></h6>
                            <small class="org-role"><?= htmlspecialchars($m['jabatan']) ?></small>
                            <div class="mt-3">
                                <a href="detail_struktur.php?id=<?= $m['id'] ?>" class="org-more">Selengkapnya →</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center text-muted">Belum ada data anggota tim yang ditambahkan.</p>
        <?php endif; ?>
    </div>


</section>

<?php include '../includes/footer.php'; ?>