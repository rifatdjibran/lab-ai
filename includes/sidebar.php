<style>
/* ================================ */
/* SIDEBAR (Glass UI) */
/* ================================ */
.sidebar {
    top: 60px;
    left: 0;
    width: 250px;
    height: calc(100vh - 60px) !important;
    

    background: rgba(230,230,230,0.45);
    backdrop-filter: blur(20px);

    padding: 25px 20px;
    transition: 0.3s ease;
    overflow: hidden;
    z-index: 500;
}

/* Collapse */
.sidebar.collapsed {
    width: 70px;
}

/* Glow background */
.circle {
    position: absolute;
    width: 160px;
    height: 160px;
    border-radius: 50%;
    filter: blur(110px);
    opacity: 1;
    z-index: -1;
}
.circle.top { top: -60px; left: -50px; background: rgb(198,110,82); }
.circle.bottom { bottom: -60px; left: -50px; background: rgb(138,190,185); }

/* Toggle button (pojok kanan atas sidebar) */
#toggleSidebar {
    position: absolute;
    top: 10px;
    right: 15px;
    background: none;
    border: none;
    font-size: 24px;
    color: #333;
    cursor: pointer;
    z-index: 999;
}

/* LOGO AREA */
.sidebar-logo-wrapper {
    text-align: center;
    margin-top: 45px; /* TURUNKAN SEDIKIT */
    margin-bottom: 25px;
}

.sidebar-logo {
    height: 60px;
    margin-bottom: 10px;
}

.sidebar-title {
    display: block;
    text-align: center;
    font-size: 18px;
    font-weight: bold;
}

/* Collapse: sembunyikan teks */
.sidebar.collapsed .sidebar-title,
.sidebar.collapsed .sidebar-text {
    display: none;
}

.sidebar.collapsed .sidebar-logo-wrapper {
    margin-top: 35px;
}

.sidebar.collapsed .sidebar-logo {
    height: 32px !important;
}

/* Menu */
.sidebar-link {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 12px 15px;
    border-radius: 10px;
    font-size: 15px;
    text-decoration: none;
    color: #222;
    transition: 0.25s ease;
}

.sidebar-link:hover {
    background: rgba(255,255,255,0.45);
    transform: translateX(4px);
}

/* Saat collapsed, icon saja */
.sidebar.collapsed .sidebar-link {
    justify-content: center;
}

/* Active menu */
.sidebar-link.active {
    background: linear-gradient(135deg, #d08b6e, #92bfba);
    color: white !important;
    transform: translateX(6px);
}

/* Konten geser */
#main-content {
    transition: 0.6s ease;
}

#main-content.collapsed {
    margin-left: 50px;
}

</style>

<div id="sidebar" class="sidebar ">

    <div class="circle top"></div>
    <div class="circle bottom"></div>

    <button id="toggleSidebar">
      <i class="bi bi-list"></i>
    </button>

    <div class="sidebar-logo-wrapper">
        <img src="../assets/img/logoclear2.png" class="sidebar-logo">
        <span class="sidebar-title">Lab AI</span>
    </div>

    <ul class="nav flex-column mb-auto">
        <li><a href="index.php" class="nav-link sidebar-link"><i class="bi bi-speedometer2"></i> <span class="sidebar-text">Dashboard</span></a></li>
        <li><a href="beritaAdmin.php" class="nav-link sidebar-link"><i class="bi bi-newspaper"></i> <span class="sidebar-text">Berita</span></a></li>
        <li><a href="agendaAdmin.php" class="nav-link sidebar-link"><i class="bi bi-calendar-event"></i> <span class="sidebar-text">Kegiatan</span></a></li>
        <li><a href="penelitianAdmin.php" class="nav-link sidebar-link"><i class="bi bi-journal-text"></i> <span class="sidebar-text">Penelitian</span></a></li>
        <li><a href="publikasiAdmin.php" class="nav-link sidebar-link"><i class="bi bi-journal-medical"></i> <span class="sidebar-text">Publikasi</span></a></li>
        
        <li><a href="timAdmin.php" class="nav-link sidebar-link"><i class="bi bi-people"></i> <span class="sidebar-text">Tim Lab</span></a></li>
        
        <li><a href="fasilitasAdmin.php" class="nav-link sidebar-link"><i class="bi bi-building"></i> <span class="sidebar-text">Fasilitas</span></a></li>
    </ul>

    <hr>
</div>

<script>
// Highlight active menu
const path = window.location.pathname;
document.querySelectorAll(".sidebar-link").forEach(a => {
    // Check if the link href matches the current path (handling relative paths if needed)
    if (a.getAttribute('href') === path.split("/").pop() || a.href === window.location.href) {
        a.classList.add("active");
    }
});
</script>