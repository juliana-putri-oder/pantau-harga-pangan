<?php
include 'koneksi.php';


$query = "SELECT Komoditas, Gambar FROM tb_data_harga GROUP BY Komoditas ORDER BY Id_Pangan DESC";
$result = $koneksi->query($query);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Katalog Komoditas Pangan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    body {

      background: radial-gradient(circle at top right, rgba(46, 125, 50, 0.05), transparent 40%),
                  linear-gradient(to bottom, #f7f5f0 0%, #eef1ec 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    

    .komoditas-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border: 1px solid rgba(255, 255, 255, 0.5) !important;
      background-color: rgba(255, 255, 255, 0.8) !important;
      backdrop-filter: blur(5px);
    }
    .komoditas-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
    .img-wrapper {
      height: 180px;
      overflow: hidden;
      border-top-left-radius: calc(0.5rem - 1px);
      border-top-right-radius: calc(0.5rem - 1px);
    }
    .img-wrapper img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }
    .komoditas-card:hover .img-wrapper img {
      transform: scale(1.08);
    }
  </style>
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container d-flex justify-content-between align-items-center">
      
      <a class="navbar-brand d-flex align-items-center fw-bold text-success" href="#">
        <img src="images/logo.png" alt="Logo" class="me-2" style="width: 40px; height: 40px; object-fit: contain;" onerror="this.src='https://via.placeholder.com/40'">
        PantauHarga
      </a>
      
      <a href="login.php" class="btn btn-outline-success px-4 rounded-pill fw-semibold">
        <i class="bi bi-box-arrow-in-right me-1"></i> Login
      </a>

    </div>
  </nav>

  <div class="container text-center my-5">
    <h1 class="display-5 fw-bold text-dark">Komoditas Pangan</h1>
    <p class="text-muted col-lg-6 mx-auto">Daftar info persediaan pangan nasional. Silakan masuk ke sistem untuk melihat detail info harga real-time, perkembangan grafik tren, dan analisis wilayah.</p>
  </div>

  <div class="container mb-5">
    <div class="row g-4">
      <?php 
      if($result && $result->num_rows > 0):
        while($row = $result->fetch_assoc()): 
      ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
          <div class="card h-100 border-0 shadow-sm rounded-3 komoditas-card">
            
            <div class="img-wrapper bg-light d-flex align-items-center justify-content-center">
              <?php if(!empty($row['Gambar']) && file_exists($row['Gambar'])): ?>
                <img src="<?= $row['Gambar'] ?>" alt="<?= htmlspecialchars($row['Komoditas']) ?>">
              <?php else: ?>
                <i class="bi bi-image text-muted display-4"></i>
              <?php endif; ?>
            </div>
            
            <div class="card-body d-flex flex-column justify-content-between text-center p-4">
              <h5 class="card-title fw-bold text-dark mb-3 text-capitalize"><?= htmlspecialchars($row['Komoditas']) ?></h5>
              
              <a href="login.php" class="btn btn-success w-100 rounded-pill py-2 fw-semibold shadow-sm">
                Lihat Detail <i class="bi bi-arrow-right-short ms-1"></i>
              </a>
            </div>

          </div>
        </div>
      <?php 
        endwhile;
      else: 
      ?>
        <div class="col-12 text-center py-5">
          <i class="bi bi-inbox text-muted display-3"></i>
          <p class="text-muted mt-2">Belum ada data komoditas pangan yang tersedia.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <footer class="text-center py-4 bg-white border-top mt-auto">
    <p class="text-muted mb-0">&copy; 2026 PantauHarga Pangan. All rights reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>