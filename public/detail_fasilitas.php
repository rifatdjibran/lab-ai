<?php
include '../config/database.php';
include '../includes/header.php';
include '../includes/navbar.php';

// Cek apakah ada ID
if (!isset($_GET['id'])) {
    die("ID fasilitas tidak ditemukan.");
}

$id = intval($_GET['id']);

// Ambil data fasilitas
$query = "SELECT id, nama_fasilitas, deskripsi, gambar, kategori, created_at
          FROM fasilitas 
          WHERE id = $id";

$result = pg_query($conn, $query);

if (!$result) {
    die("Query gagal: " . pg_last_error($conn));
}

$data = pg_fetch_assoc($result);

if (!$data) {
    die("Fasilitas tidak ditemukan.");
}
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="text-center text-white">
        <h1 class="fw-bold">Detail Fasilitas</h1>
    </div>
</section>

<!-- Detail Fasilitas -->
<section class="py-5">
    <div class="container">
        <div class="card shadow-lg p-4">

            <!-- Nama Fasilitas -->
            <h2 class="fw-bold mb-3 text-center">
                <?= htmlspecialchars($data['nama_fasilitas']) ?>
            </h2>

            <!-- Info Kategori & Tanggal -->
            <p class="text-muted text-center">
                Kategori: <strong><?= htmlspecialchars($data['kategori']) ?></strong><br>
                Ditambahkan pada:
                <strong><?= date('d F Y', strtotime($data['created_at'])) ?></strong>
            </p>

            <!-- Gambar -->
            <div class="text-center my-4">
                <img src="../assets/uploads/fasilitas/<?= htmlspecialchars($data['gambar']) ?>" 
                     class="img-fluid rounded"
                     style="max-height: 450px; object-fit: cover;">
            </div>

            <!-- Deskripsi -->
            <div class="mt-4" style="font-size: 1.1rem; line-height: 1.7;">
                <?= nl2br($data['deskripsi']) ?>
            </div>

            <!-- Tombol Kembali -->
            <div class="text-center mt-5">
                <a href="fasilitas.php" class="btn btn-primary px-4">
                    ← Kembali ke Daftar Fasilitas
                </a>
            </div>

        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
