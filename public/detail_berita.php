<?php
include '../config/database.php';

// Ambil ID dari URL
$id = $_GET['id'];

// Query berita berdasarkan ID
$query = "SELECT * FROM berita WHERE id = $id";
$result = pg_query($conn, $query);
$data = pg_fetch_assoc($result);
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="container py-5">

    <a href="berita.php" class="text-primary fw-bold mb-4 d-inline-block">
        ← Kembali ke Berita
    </a>

    <h1 class="fw-bold"><?= $data['judul']; ?></h1>

    <p class="text-muted">
        <?= date("F d, Y", strtotime($data['tanggal'])); ?> • <?= $data['penulis']; ?>
    </p>

    <!-- Gambar Berita -->
    <?php if (!empty($data['gambar'])) { ?>
        <img src="../uploads/<?= $data['gambar']; ?>" width="150" class="mt-3">
    <?php } ?>

    <div style="font-size: 17px; line-height: 1.7;">
        <?= nl2br($data['isi']); ?>
    </div>

</div>

<?php include '../includes/footer.php'; ?>
