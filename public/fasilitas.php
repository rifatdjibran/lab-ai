<?php
include '../config/database.php';
include '../includes/header.php';
include '../includes/navbar.php';

// Ambil semua fasilitas
$query = "SELECT id, nama_fasilitas, deskripsi, gambar, kategori, created_at 
          FROM fasilitas 
          ORDER BY created_at DESC";

$result = pg_query($conn, $query);

if (!$result) {
    die("Query gagal: " . pg_last_error($conn));
}

$fasilitasList = pg_fetch_all($result);
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="text-center text-white">
        <h1 class="fw-bold">Fasilitas Laboratorium</h1>
        <p class="lead mt-2">Lihat sarana dan fasilitas pendukung kegiatan di Lab AI</p>
    </div>
</section>

<!-- Fasilitas Section -->
<section class="py-5">
    <div class="container">
        <h2 class="mb-4 text-center">Daftar Fasilitas</h2>

        <div class="row g-4">

            <?php if ($fasilitasList): ?>
                <?php foreach ($fasilitasList as $f): ?>
                    <div class="col-md-4">
                        <div class="card agenda-card shadow-sm">

                            <!-- Gambar -->
                            <img 
                                src="../assets/uploads/fasilitas/<?= htmlspecialchars($f['gambar']) ?>" 
                                class="card-img-top"
                                style="height: 250px; object-fit: cover;"
                                alt="<?= htmlspecialchars($f['nama_fasilitas']) ?>">

                            <div class="card-body">

                                <!-- Nama Fasilitas -->
                                <h5 class="card-title fw-bold">
                                    <?= htmlspecialchars($f['nama_fasilitas']) ?>
                                </h5>

                                <!-- Deskripsi Pendek -->
                                <p class="card-text text-muted">
                                    <?= mb_strimwidth(strip_tags($f['deskripsi']), 0, 120, "..."); ?>
                                </p>

                                <!-- Kategori -->
                                <p class="mb-1">
                                    <strong>Kategori:</strong>
                                    <?= htmlspecialchars($f['kategori']) ?>
                                </p>

                                <!-- Tanggal -->
                                <p class="mb-1">
                                    <strong>Ditambahkan:</strong>
                                    <?= date('d F Y', strtotime($f['created_at'])) ?>
                                </p>

                                <div class="mt-3">
                                    <a href="detail_fasilitas.php?id=<?= $f['id'] ?>" 
                                       class="btn btn-primary btn-sm">
                                        Lihat Selengkapnya →
                                    </a>
                                </div>

                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Belum ada fasilitas yang tersedia.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
