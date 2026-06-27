<?php 
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
} 
if(!isset($_SESSION['username'])) {
    header("Location: login/login.php"); 
    exit;
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pantau Harga Pangan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    body { background-color: #506e5e; overflow-x: hidden; }
    
    /* Konfigurasi RESPONSIVITAS SIDEBAR */
    .sidebar { 
      min-height: 100vh; 
      background: linear-gradient(180deg, #2E7D32 0%, #1B5E20 100%); 
      color: white; 
      position: fixed; 
      top: 0; 
      left: 0; 
      width: 250px; 
      transition: all 0.3s ease; 
      z-index: 1040; 
    }
    .sidebar .logo { padding: 1.5rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .sidebar .logo img { width: 40px; height: 40px; object-fit: contain; }
    .sidebar .nav-link { color: rgba(255,255,255,0.8); padding: 0.8rem 1rem; margin: 0.2rem 0.5rem; border-radius: 0.5rem; transition: all 0.2s; text-decoration: none; display: block; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: rgba(255,255,255,0.15); color: white; }
    
    /* konten Utama bergeser 250px jika di layar Desktop */
    .main-content { margin-left: 250px; transition: all 0.3s ease; min-height: 100vh; }
    .top-navbar { background: #506e5e; box-shadow: 0 2px 4px rgba(0,0,0,0.05); padding: 1rem 1.5rem; }

    /* RESPONSIVE BREAKPOINT (Untuk HP dan Tablet) */
    @media (max-width: 991.98px) {
      .sidebar { left: -250px; }       /* sembunyiin sidebar ke kiri di HP */
      .sidebar.show { left: 0; }        /* tampilin jika ditambahkan class 'show' via JS */
      .main-content { margin-left: 0; } /* konten utama melebar penuh di HP */
      
      /* efek backdrop/overlay gelap saat menu terbuka di HP */
      .sidebar-backdrop {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        background: rgba(0,0,0,0.4); z-index: 1030; display: none;
      }
      .sidebar-backdrop.show { display: block; }
    }
  </style>
</head>
<body>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<div class="sidebar" id="sidebar">
  <div class="logo d-flex align-items-center">
    <img src="images/logo.png" alt="Logo" class="me-2" onerror="this.src='https://via.placeholder.com/40'">
    <div>
      <h6 class="mb-0 fw-bold">Pantau Harga Pangan</h6>
    </div>
  </div>
  
  <nav class="nav flex-column mt-3 px-2">
    <a class="nav-link <?= ($current_page == 'dashboard.php') ? 'active' : '' ?>" href="dashboard.php">
      <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a>
    <a class="nav-link <?= ($current_page == 'data_harga.php') ? 'active' : '' ?>" href="data_harga.php">
      <i class="bi bi-table me-2"></i> Data Harga
    </a>
    <a class="nav-link <?= ($current_page == 'grafik.php') ? 'active' : '' ?>" href="grafik.php">
      <i class="bi bi-graph-up me-2"></i> Grafik & Analisis
    </a>
    <hr class="mx-2 my-2 opacity-25">
    <a class="nav-link" href="login/logout.php">
      <i class="bi bi-box-arrow-right me-2"></i> Logout
    </a>
  </nav>
</div>

<div class="main-content" id="mainContent">
  <nav class="top-navbar d-flex justify-content-between align-items-center">
    <div>
      <button class="btn btn-success d-lg-none text-white border-0 shadow-sm" id="sidebarToggle" type="button">
        <i class="bi bi-list fs-5"></i>
      </button>
    </div>
    
    <div class="dropdown">
      <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" data-bs-toggle="dropdown">
        <i class="bi bi-person-circle fs-4 text-success me-2"></i>
        <span><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></span>
      </a>
      <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
        <li><a class="dropdown-item text-danger" href="login/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
      </ul>
    </div>
  </nav>
  
  <div class="container-fluid p-3 p-md-4">

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const sidebar = document.getElementById("sidebar");
      const toggleBtn = document.getElementById("sidebarToggle");
      const backdrop = document.getElementById("sidebarBackdrop");

      // Fungsi untuk buka/tutup menu
      function toggleSidebar() {
        sidebar.classList.toggle("show");
        if(backdrop) backdrop.classList.toggle("show");
      }

      // Jalanin fungsi jika tombol hamburger diklik
      if(toggleBtn) {
        toggleBtn.addEventListener("click", function(e) {
          e.stopPropagation();
          toggleSidebar();
        });
      }

      // tutup kembali menu jika area luar/backdrop diklik 
        backdrop.addEventListener("click", toggleSidebar);
      }
    );
  </script>