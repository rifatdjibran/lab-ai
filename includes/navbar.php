<?php
// Mendapatkan nama file script saat ini untuk penanda menu 'Active'
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab-AI</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* =========================================
           NAVBAR STYLING - MINIMALIST ELEGANT
           ========================================= */
        
        /* 1. Base Navbar */
        .navbar {
            background-color: rgba(255, 255, 255, 0.95) !important; /* Putih sedikit transparan */
            backdrop-filter: blur(10px); /* Efek blur di belakang navbar */
            padding-top: 15px;
            padding-bottom: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); /* Bayangan sangat halus */
            transition: all 0.3s ease;
        }

        /* 2. Brand / Logo */
        .navbar-brand img {
            transition: transform 0.3s ease;
        }
        .navbar-brand:hover img {
            transform: scale(1.05); /* Logo sedikit membesar saat hover */
        }

        /* 3. Navigation Links */
        .navbar-nav .nav-item {
            margin: 0 5px; /* Jarak antar menu */
        }

        .navbar-nav .nav-link {
            color: #555 !important; /* Warna teks abu tua (bukan hitam pekat agar elegan) */
            font-weight: 500;
            font-size: 0.95rem;
            padding: 8px 12px;
            position: relative;
            transition: color 0.3s ease;
        }

        /* Hover Color */
        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: #0d6efd !important; /* Warna Biru Utama */
        }

        /* 4. Efek Garis Bawah (Underline Animation) */
        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 5px; /* Jarak dari teks */
            left: 50%;
            background-color: #0d6efd; /* Warna garis biru */
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55); /* Transisi smooth memantul */
            transform: translateX(-50%);
            opacity: 0;
        }

        /* Saat Hover atau Active, garis melebar */
        .navbar-nav .nav-link:hover::after,
        .navbar-nav .nav-link.active::after {
            width: 80%; /* Lebar garis 80% dari teks */
            opacity: 1;
        }

        /* Hilangkan efek garis bawah untuk Dropdown Toggle (supaya tidak tabrakan) */
        .nav-link.dropdown-toggle::after {
            display: none; 
        }

        /* 5. Dropdown Menu Styling */
        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            margin-top: 15px; /* Jarak dari navbar */
            padding: 10px;
            animation: slideUp 0.3s ease forwards;
            opacity: 0;
            display: block;
            visibility: hidden;
            transform: translateY(10px);
        }

        /* Tampilkan dropdown saat class show aktif (dari Bootstrap JS) */
        .dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            font-size: 0.9rem;
            padding: 10px 15px;
            border-radius: 8px;
            color: #555;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f0f7ff; /* Background biru sangat muda */
            color: #0d6efd;
            transform: translateX(5px); /* Geser kanan sedikit */
        }

        /* 6. Login Button Styling */
        .btn-login-nav {
            border-radius: 50px; /* Bentuk Pill */
            padding: 8px 24px;
            border: 2px solid #0d6efd;
            color: #0d6efd;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-login-nav:hover {
            background-color: #0d6efd;
            color: #fff;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3); /* Glow effect */
            transform: translateY(-2px); /* Naik sedikit */
        }

        /* Animasi Dropdown */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive Fix */
        @media (max-width: 991px) {
            .navbar-nav .nav-link::after {
                display: none; /* Hilangkan animasi garis di mode mobile agar rapi */
            }
            .navbar-nav .nav-link:hover,
            .navbar-nav .nav-link.active {
                background-color: #f8f9fa;
                border-radius: 8px;
                padding-left: 20px; /* Indentasi */
            }
            .btn-login-nav {
                width: 100%;
                margin-top: 10px;
                text-align: center;
            }
            .navbar-collapse {
                background: white;
                padding: 20px;
                border-radius: 15px;
                margin-top: 15px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            }
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container"> <a class="navbar-brand d-flex align-items-center" href="/lab-ai/index.php">
            <img src="/lab-ai/assets/img/logoclear.png" alt="Logo" height="40" class="me-2">
            </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>" href="/lab-ai/index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'struktur.php') ? 'active' : '' ?>" href="/lab-ai/public/struktur.php">Member</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= ($current_page == 'penelitian.php' || $current_page == 'publikasi.php') ? 'active' : '' ?>" 
                       href="#" id="penelitianPublikasi" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Penelitian & Publikasi
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="penelitianPublikasi">
                        <li><a class="dropdown-item" href="/lab-ai/public/penelitian.php">Hasil Penelitian</a></li>
                        <li><a class="dropdown-item" href="/lab-ai/public/publikasi.php">Publikasi Ilmiah</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'fasilitas.php') ? 'active' : '' ?>" href="/lab-ai/public/fasilitas.php">Fasilitas</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'agenda.php') ? 'active' : '' ?>" href="/lab-ai/public/agenda.php">Agenda</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'berita.php') ? 'active' : '' ?>" href="/lab-ai/public/berita.php">Berita</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'kontak.php') ? 'active' : '' ?>" href="/lab-ai/public/kontak.php">Kontak</a>
                </li>

                <li class="nav-item ms-lg-3">
                    <a href="/lab-ai/admin/login.php" class="btn btn-login-nav">
                        <i class="bi bi-person-lock me-1"></i> Login Admin
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>