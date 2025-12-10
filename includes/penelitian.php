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
$query = "SELECT id, judul, peneliti, tahun, deskripsi, created_at
          FROM public.penelitian
          ORDER BY tahun DESC";
$result = pg_query($conn, $query);

if (!$result) {
    die("Query gagal: " . pg_last_error($conn));
}

$dataPenelitian = pg_fetch_all($result);
?>

<style>
    body {
        background-color: #f8f9fa;
    }
    .hero-section {
        background: linear-gradient(rgba(0,51,102,0.6), rgba(0,51,102,0.6)),
                    url('../assets/img/home-lab.jpg.jpg') center/cover no-repeat;
        height: 40vh;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        border-bottom-left-radius: 50px;
        border-bottom-right-radius: 50px;
    }
    .research-card {
        transition: transform 0.3s;
    }
    .research-card:hover {
        transform: translateY(-5px);
    }
</style>

<!-- Hero Section -->
<section class="hero-section text-center">
    <div>
        <h1 class="display-4 fw-bold">Hasil Penelitian Laboratorium</h1>
        <p class="lead">Kumpulan hasil penelitian dari Laboratorium Applied Informatics</p>
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

<!-- Footer -->
<footer class="bg-primary text-white text-center py-3 mt-5">
    <small>&copy; <?= date('Y') ?> Laboratorium for Applied Informatics</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
