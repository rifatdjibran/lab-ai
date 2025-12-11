<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once "../config/database.php";

// LOGIKA HAPUS
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM kontak WHERE id = $1";
    $result = pg_query_params($conn, $query, array($id));
    header("Location: kontakAdmin.php?hapus=1");
    exit;
}

// AMBIL DATA
$result = pg_query($conn, "SELECT * FROM kontak ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kotak Masuk | Admin Lab AI</title>
    
    <link rel="icon" type="image/png" href="../assets/img/logoclear.png">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- 1. THEME VARIABLES --- */
        :root {
            --primary-color: #4361ee;
            --primary-dark: #3a0ca3;
            --accent-color: #4cc9f0;
            --bg-body: #f3f5f9;
            --text-main: #2b3674;
            --text-muted: #a3aed0;
        }

        body {
            background-color: var(--bg-body);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-main);
        }

        /* --- 2. HEADER & CARDS --- */
        .page-title {
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: -0.5px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 15px 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .stat-icon-box {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        /* --- 3. MODERN TABLE STYLING --- */
        .table-container {
            padding-bottom: 50px;
        }
        
        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 15px; /* Jarak antara Header dan Baris Data */
        }

        /* HEADER TABLE (Dibuat Menonjol & Bold) */
        .custom-table thead tr {
            /* Pilihan 1: Warna Putih Clean */
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
            
            /* Pilihan 2: Jika ingin warna biru gradient aktifkan baris bawah ini */
            /* background: linear-gradient(90deg, var(--primary-color), var(--accent-color)); */
        }

        .custom-table thead th {
            font-size: 0.8rem;
            color: var(--text-muted); /* Ubah jadi 'white' jika pakai background gradient */
            font-weight: 800; /* BOLD */
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border: none;
            padding: 18px 20px;
        }

        /* Rounded Corners untuk Header */
        .custom-table thead th:first-child {
            border-top-left-radius: 16px;
            border-bottom-left-radius: 16px;
        }
        .custom-table thead th:last-child {
            border-top-right-radius: 16px;
            border-bottom-right-radius: 16px;
        }

        /* ROW DATA (Card Style) */
        .custom-table tbody tr {
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .custom-table tbody td {
            padding: 20px;
            vertical-align: middle;
            border: none;
            color: var(--text-main);
        }

        .custom-table tbody td:first-child {
            border-top-left-radius: 16px;
            border-bottom-left-radius: 16px;
        }
        .custom-table tbody td:last-child {
            border-top-right-radius: 16px;
            border-bottom-right-radius: 16px;
        }

        .custom-table tbody tr:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 30px rgba(67, 97, 238, 0.1);
            z-index: 10;
        }

        /* --- 4. COMPONENTS --- */
        .avatar-box {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .status-new {
            background-color: #e0e7ff; 
            color: #4361ee;
        }
        .status-read {
            background-color: #f4f7fe;
            color: #a3aed0;
        }

        /* Action Button */
        .btn-action {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: none;
            background: transparent;
            color: var(--text-muted);
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto; /* Center button */
        }
        .btn-action:hover, .dropdown.show .btn-action {
            background: #f4f7fe;
            color: var(--primary-color);
        }

        /* Dropdown */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            border-radius: 12px;
            padding: 8px;
        }
        .dropdown-item {
            border-radius: 8px;
            font-size: 0.9rem;
            padding: 8px 12px;
            color: var(--text-main);
            font-weight: 500;
        }
        .dropdown-item:hover {
            background-color: #f4f7fe;
            color: var(--primary-color);
        }

        /* Modal */
        .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        }
        .msg-bubble {
            background-color: #f8f9fa;
            border-radius: 16px;
            padding: 25px;
            font-size: 0.95rem;
            line-height: 1.7;
            color: #444;
            border: 1px solid #eee;
        }
    </style>
</head>

<body>

<?php include "../includes/header_admin.php"; ?>

<div class="d-flex">
    <?php include "../includes/sidebar.php"; ?>

    <div id="main-content" class="p-4 p-md-5 flex-grow-1" style="min-height: 100vh;">
        
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <h2 class="page-title display-6 mb-1">Pesan Masuk</h2>
                <p class="text-muted mb-0">Kelola keluhan dan masukan dari pengguna.</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon-box">
                    <i class="bi bi-envelope-fill"></i>
                </div>
                <div>
                    <small class="text-muted d-block" style="font-size: 0.75rem; font-weight: 600;">TOTAL PESAN</small>
                    <span class="fs-4 fw-bold text-dark"><?= pg_num_rows($result) ?></span>
                </div>
            </div>
        </div>

        <?php if (isset($_GET['hapus'])) { ?>
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center bg-white">
                <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
                <div class="text-dark fw-medium">Pesan berhasil dihapus dari sistem.</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th class="ps-4">PENGIRIM</th>
                        <th>SUBJEK</th>
                        <th>WAKTU</th>
                        <th>STATUS</th>
                        <th class="text-center pe-4">MENU</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (pg_num_rows($result) > 0) {
                        while ($Row = pg_fetch_assoc($result)) { 
                    ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-box me-3 shadow-sm">
                                    <?= strtoupper(substr($Row['nama'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($Row['nama']); ?></div>
                                    <div class="text-muted small" style="font-size: 0.8rem;"><?= htmlspecialchars($Row['email']); ?></div>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="fw-semibold text-secondary">
                                <?= htmlspecialchars($Row['subjek']); ?>
                            </span>
                        </td>

                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold" style="font-size: 0.85rem; color: #555;">
                                    <?= date('d M Y', strtotime($Row['tanggal'])); ?>
                                </span>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    <?= date('H:i', strtotime($Row['tanggal'])); ?> WIB
                                </small>
                            </div>
                        </td>

                        <td>
                            <?php if ($Row['status'] == 'baru'): ?>
                                <span class="status-badge status-new">
                                    <span class="dot"></span> Baru
                                </span>
                            <?php else: ?>
                                <span class="status-badge status-read">Dibaca</span>
                            <?php endif; ?>
                        </td>

                        <td class="text-center pe-4">
                            <div class="dropdown">
                                <button class="btn-action" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <button class="dropdown-item d-flex align-items-center" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewModal"
                                                data-nama="<?= htmlspecialchars($Row['nama']) ?>"
                                                data-email="<?= htmlspecialchars($Row['email']) ?>"
                                                data-subjek="<?= htmlspecialchars($Row['subjek']) ?>"
                                                data-tanggal="<?= date('d F Y, H:i', strtotime($Row['tanggal'])) ?>"
                                                data-pesan="<?= htmlspecialchars($Row['pesan']) ?>">
                                            <i class="bi bi-eye me-2 opacity-50"></i> Lihat Pesan
                                        </button>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <a class="dropdown-item text-danger d-flex align-items-center" 
                                           onclick="return confirm('Hapus pesan ini permanen?')"
                                           href="kontakAdmin.php?delete=<?= $Row['id']; ?>">
                                            <i class="bi bi-trash me-2 opacity-50"></i> Hapus
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else {
                    ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <div class="bg-light rounded-circle p-3 mb-3">
                                    <i class="bi bi-inbox text-muted fs-1"></i>
                                </div>
                                <h6 class="text-muted fw-bold">Tidak ada pesan masuk</h6>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title fw-bold text-dark">Detail Pesan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      
      <div class="modal-body p-4 p-lg-5">
        <div class="d-flex align-items-start justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3 fw-bold" 
                     style="width: 50px; height: 50px; font-size: 1.2rem;">
                    <i class="bi bi-person"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-0" id="modalNama">Nama Pengirim</h5>
                    <div class="text-primary small" id="modalEmail">email@example.com</div>
                </div>
            </div>
            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill" id="modalTanggal">Tanggal</span>
        </div>

        <div class="mb-3">
            <label class="small text-muted fw-bold text-uppercase mb-1">Subjek</label>
            <h5 class="fw-bold text-dark" id="modalSubjek">Judul Subjek</h5>
        </div>

        <div class="msg-bubble">
            <p class="mb-0" id="modalPesan" style="white-space: pre-wrap;">Isi pesan...</p>
        </div>
      </div>
      
      <div class="modal-footer border-0 px-5 pb-4 pt-0">
        <button type="button" class="btn btn-light px-4 py-2 rounded-pill fw-bold text-muted" data-bs-dismiss="modal">Tutup</button>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const viewModal = document.getElementById('viewModal')
    viewModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget
        viewModal.querySelector('#modalNama').textContent = button.getAttribute('data-nama')
        viewModal.querySelector('#modalEmail').textContent = button.getAttribute('data-email')
        viewModal.querySelector('#modalSubjek').textContent = button.getAttribute('data-subjek')
        viewModal.querySelector('#modalPesan').textContent = button.getAttribute('data-pesan')
        viewModal.querySelector('#modalTanggal').textContent = button.getAttribute('data-tanggal')
    })
</script>

</body>
</html>