<?php
session_start();
$error = '';
$success = '';

if(isset($_GET['error'])) {
    $error = 'Username atau Password salah!';
}
if(isset($_GET['success'])) {
    $success = 'Login berhasil!';
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - PantauHargaPangan.id</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  
  <style>
    body { margin: 0; padding: 0; min-height: 100vh; }
    
    /* layout kiri */
    .left-panel {
      background: linear-gradient(rgba(46, 125, 50, 0.7), rgba(46, 125, 50, 0.7)), 
                  url('images/bg_login.jpg') center/cover no-repeat;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      text-align: center;
      padding: 3rem;
    }
    
    /* bg kanan */
    .right-panel {
      background-color: #2E7D32;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }
    
    .login-card {
      background: white;
      border-radius: 1.5rem;
      width: 100%;
      max-width: 400px;
      border: 0;
    }
    
    .btn-hijau {
      background-color: #2E7D32;
      border-color: #2E7D32;
    }
    .btn-hijau:hover {
      background-color: #1B5E20;
      border-color: #1B5E20;
    }
    
    /* responsif di HP layar < 992px, kiri pindah ke atas */
    @media (max-width: 991px) {
      .left-panel { min-height: 40vh; padding: 2rem 1rem; }
      .right-panel { min-height: 60vh; }
    }
  </style>
</head>
<body>

<div class="container-fluid p-0">
  <div class="row g-0">
    
    <!-- layout data pangan, background gambar -->
    <div class="col-12 col-lg-7">
      <div class="left-panel">
        <div>
          <i class="bi bi-graph-up-arrow fs-1 mb-3"></i>
          <h1 class="fw-bold display-5 mb-3">Data Pangan Indonesia</h1>
          <p class="fs-5 opacity-75">Transparan dan Akurat</p>
          <p class="mt-4 small">Pantau harga komoditas 34 provinsi secara real-time</p>
        </div>
      </div>
    </div>
    
    <!-- Kanan, card login putih di background hijau -->
    <div class="col-12 col-lg-5">
      <div class="right-panel">
        <div class="card login-card shadow-lg">
          <div class="card-body p-4 p-md-5">
            
            <div class="text-center mb-4">
              <i class="bi bi-shield-lock fs-1 text-success"></i>
              <h4 class="fw-bold mt-2">Masuk Dashboard</h4>
            </div>

            <?php if($error): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> <?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            <?php endif; ?>
            
            <?php if($success): ?>
              <div class="alert alert-success" role="alert">
                <i class="bi bi-check-circle-fill me-1"></i> <?= $success ?>
              </div>
            <?php endif; ?>

            <form method="POST" action="login/proses_login.php">
              <div class="mb-3">
                <label class="form-label text-muted small">Username</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0"><i class="bi bi-person"></i></span>
                  <input type="text" name="username" class="form-control border-start-0" required autofocus>
                </div>
              </div>

              <div class="mb-4">
                <label class="form-label text-muted small">Password</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock"></i></span>
                  <input type="password" name="password" class="form-control border-start-0" required>
                </div>
              </div>

              <button type="submit" class="btn btn-hijau text-white w-100 fw-bold py-2">
                Login
              </button>
            </form>

            <p class="text-center text-muted small mt-3 mb-0">Belum punya akun? buat sendiri</p>
          </div>
        </div>
      </div>
    </div>
    
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>