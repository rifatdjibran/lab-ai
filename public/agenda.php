<?php
include '../config/database.php';
include '../includes/header.php';
include '../includes/navbar.php';

// Koneksi database PostgreSQL
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$conn) {
    die("Koneksi ke database gagal: " . pg_last_error());
}

date_default_timezone_set('Asia/Jakarta');

// Ambil data kegiatan
$query = "SELECT id, nama_kegiatan, deskripsi, tanggal_mulai, tanggal_selesai,
                 lokasi, gambar, created_at
          FROM public.kegiatan
          ORDER BY tanggal_mulai ASC";

$result = pg_query($conn, $query);

if (!$result) {
    die("Query gagal: " . pg_last_error($conn));
}

$agendaLab = pg_fetch_all($result);
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="text-center text-white">
        <h1 class="fw-bold">Agenda Laboratorium</h1>
        <p class="lead mt-2">Ikuti jadwal kegiatan dan workshop terbaru dari Laboratorium</p>
    </div>
</section>

<!-- Agenda Section -->
<section class="py-5">
    <div class="container">
        <h2 class="mb-4 text-center">Agenda Laboratorium</h2>

        <div class="row g-4">

            <?php if ($agendaLab): ?>
                <?php foreach ($agendaLab as $kegiatan): ?>
                    <div class="col-md-4">
                        <div class="card agenda-card shadow-sm">

                            <img src="../assets/img/banners/<?= $kegiatan['gambar'] ?>"
                                 class="card-img-top"
                                 alt="<?= htmlspecialchars($kegiatan['nama_kegiatan']) ?>">

                            <div class="card-body">
                                <h5 class="card-title">
                                    <?= htmlspecialchars($kegiatan['nama_kegiatan']) ?>
                                </h5>

                                <p class="card-text">
                                    <?= htmlspecialchars($kegiatan['deskripsi']) ?>
                                </p>

                                <p class="mb-1">
                                    <strong>Tanggal:</strong>
                                    <?= date('d F Y', strtotime($kegiatan['tanggal_mulai'])) ?> –
                                    <?= date('d F Y', strtotime($kegiatan['tanggal_selesai'])) ?>
                                </p>

                                <p class="mb-1">
                                    <strong>Lokasi:</strong>
                                    <?= htmlspecialchars($kegiatan['lokasi']) ?>
                                </p>

                                <?php
                                    $today = date('Y-m-d');

                                    if ($today < $kegiatan['tanggal_mulai']) {
                                        echo '<span class="badge bg-warning text-dark badge-status">Segera</span>';
                                    } elseif ($today >= $kegiatan['tanggal_mulai'] && $today <= $kegiatan['tanggal_selesai']) {
                                        echo '<span class="badge bg-success badge-status">Sedang Berlangsung</span>';
                                    } else {
                                        echo '<span class="badge bg-secondary badge-status">Selesai</span>';
                                    }
                                ?>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Belum ada agenda saat ini.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
