<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<link rel="stylesheet" href="struktural_detail.css">

<div class="page-content container my-5">

    <!-- CARD PROFIL -->
    <div class="detail-card shadow-sm p-4 mb-4 rounded-4">
        <div class="row align-items-center">
            
            <!-- Foto -->
            <div class="col-md-3 text-center">
                <img src="../assets/img/tim/ketua.png" class="detail-photo" alt="Foto">
            </div>

            <!-- Biodata -->
            <div class="col-md-9">
                <table class="table table-borderless detail-table">
                    <tr>
                        <td class="w-25 fw-bold">Nama</td>
                        <td>: Ir. Yan Watequlis Syaifudin, S.T., M.MT., Ph.D</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Email</td>
                        <td>: yan@example.com</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Pendidikan Terakhir</td>
                        <td>: S3 Teknik Informatika</td>
                    </tr>
                </table>
            </div>

        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="text-center mb-4">
        <a href="#" class="action-btn me-2">Linkedin</a>
        <a href="#" class="action-btn">Google Scholar</a>
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

        <!-- Publikasi -->
        <div class="tab-pane fade show active" id="publikasi">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered publikasi-table">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Judul</th>
                            <th class="text-center">Tahun</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i=1; $i<=10; $i++): ?>
                        <tr>
                            <td class="text-center"><?= $i ?></td>
                            <td>Judul Publikasi Contoh <?= $i ?></td>
                            <td class="text-center"><?= 2020 + ($i % 4) ?></td>
                        </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Riset -->
        <div class="tab-pane fade" id="riset">
            <p>Daftar riset...</p>
        </div>

        <!-- Kekayaan Intelektual -->
        <div class="tab-pane fade" id="ki">
            <p>Daftar KI...</p>
        </div>

        <!-- PPM -->
        <div class="tab-pane fade" id="ppm">
            <p>Daftar PPM...</p>
        </div>

        <!-- Aktivitas -->
        <div class="tab-pane fade" id="aktivitas">
            <p>Daftar aktivitas...</p>
        </div>

    </div>

</div>

<?php include '../includes/footer.php'; ?>
