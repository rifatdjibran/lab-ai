<?php
include '../config/database.php';
include '../includes/header.php';
include '../includes/navbar.php';

// Koneksi database PostgreSQL
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$conn) {
    die("Koneksi database gagal: " . pg_last_error());
}

date_default_timezone_set('Asia/Jakarta');

// Ambil data penelitian
$query = "SELECT id, judul, peneliti, tahun, deskripsi, file_laporan, created_at
          FROM public.penelitian
          ORDER BY tahun DESC";
$result = pg_query($conn, $query);

if (!$result) {
    die("Query gagal: " . pg_last_error($conn));
}

$dataPenelitian = pg_fetch_all($result);
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="text-center text-white">
        <h1 class="fw-bold">Hasil Penelitian Laboratorium</h1>
        <p class="lead mt-2">Kumpulan hasil penelitian dari Laboratorium Applied Informatics</p>
    </div>
</section>

<!-- Research List -->
<section class="py-5">
    <div class="container">
        <h2 class="mb-4 text-center">Daftar Penelitian</h2>

        <div class="row g-4">

            <?php if ($dataPenelitian): ?>
                <?php foreach ($dataPenelitian as $r): ?>
                    <div class="col-md-4">
                        <div class="card research-card shadow-sm p-3">

                            <h5 class="fw-bold mb-1">
                                <?= htmlspecialchars($r['judul']) ?>
                            </h5>

                            <p class="mb-1 text-muted">
                                <strong>Peneliti:</strong> <?= htmlspecialchars($r['peneliti']) ?>
                            </p>

                            <p class="mb-1 text-muted">
                                <strong>Tahun:</strong> <?= htmlspecialchars($r['tahun']) ?>
                            </p>

                            <p class="mb-2">
                                <?= htmlspecialchars(substr($r['deskripsi'], 0, 130)) ?>...
                            </p>

                            <?php if (!empty($r['file_laporan'])): ?>
                                <a href="../assets/uploads/penelitian/<?= $r['file_laporan'] ?>" 
                                   class="btn btn-success btn-sm" target="_blank">
                                   <i class="bi bi-file-earmark-pdf"></i> Lihat Laporan
                                </a>
                            <?php else: ?>
                                <small class="text-danger">Tidak ada file laporan</small>
                            <?php endif; ?>

                        </div>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Belum ada data penelitian.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>


<?php include '../includes/footer.php'; ?>

</body>
</html>
