<?php
session_start();
if(!isset($_SESSION['username'])) {
    header("Location: login/login.php"); 
    exit;
}
include 'partials/header.php';
include 'koneksi.php';

$res_prov = $koneksi->query("SELECT COUNT(DISTINCT Provinsi) AS total FROM tb_data_harga");
$p_data = $res_prov->fetch_assoc();
$kpi_provinsi = $p_data['total'] ?? 0;

$res_cabai = $koneksi->query("SELECT MAX(Harga) AS max_price FROM tb_data_harga WHERE Komoditas LIKE '%Cabai%'");
$c_data = $res_cabai->fetch_assoc();
$kpi_cabai_max = $c_data['max_price'] ?? 0;

$res_kom = $koneksi->query("SELECT COUNT(DISTINCT Komoditas) AS total FROM tb_data_harga");
$k_data = $res_kom->fetch_assoc();
$kpi_total_komoditas = $k_data['total'] ?? 0;

$res_time = $koneksi->query("SELECT `Bulan/Tahun` FROM tb_data_harga ORDER BY Id_Pangan DESC LIMIT 1");
$t_data = $res_time->fetch_assoc();
$kpi_update_terakhir = $t_data['Bulan/Tahun'] ?? '-';


$harga_terbaru = [];
$res_table = $koneksi->query("SELECT Komoditas, Harga, Keterangan FROM tb_data_harga ORDER BY Id_Pangan DESC LIMIT 5");
if($res_table && $res_table->num_rows > 0) {
    while($row = $res_table->fetch_assoc()) {

        $badge = 'secondary';
        if(strtolower($row['Keterangan']) == 'naik') $badge = 'danger';
        if(strtolower($row['Keterangan']) == 'turun') $badge = 'success';
        if(strtolower($row['Keterangan']) == 'stabil') $badge = 'warning';
        
        $harga_terbaru[] = [
            'komoditas' => $row['Komoditas'],
            'harga' => $row['Harga'],
            'status' => !empty($row['Keterangan']) ? ucfirst($row['Keterangan']) : 'Stabil',
            'badge' => $badge
        ];
    }
}


$bulan = [];
$harga_beras = [];
$res_chart = $koneksi->query("SELECT `Bulan/Tahun`, Harga FROM tb_data_harga WHERE Komoditas LIKE '%Beras%' ORDER BY `Bulan/Tahun` ASC LIMIT 6");
if($res_chart && $res_chart->num_rows > 0) {
    while($row = $res_chart->fetch_assoc()) {
        $bulan[] = $row['Bulan/Tahun'];
        $harga_beras[] = $row['Harga'];
    }
}
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - PantauHargaPangan.id</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    body { background-color: #506e5e; }
    .card { border-radius: 1rem; }
    .text-hijau { color: #2E7D32 !important; }
    .text-orange { color: #F57C00 !important; }
  </style>
</head>
<body>

<div class="container-fluid p-4">
  <h2 class="fw-bold mb-4">Dashboard Harga Pangan</h2>

  <div class="row g-3 mb-4">
    <div class="col-12 col-md-3">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
          <i class="bi bi-geo-alt fs-2 text-primary"></i>
          <h6 class="text-muted mt-2 mb-1">Provinsi Terpantau</h6>
          <h3 class="fw-bold text-hijau"><?= $kpi_provinsi ?></h3>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
          <i class="bi bi-fire fs-2 text-danger"></i>
          <h6 class="text-muted mt-2 mb-1">Harga Cabai Tertinggi</h6>
          <h3 class="fw-bold text-orange">Rp <?= number_format($kpi_cabai_max, 0, ',', '.') ?></h3>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
          <i class="bi bi-box-seam fs-2 text-warning"></i>
          <h6 class="text-muted mt-2 mb-1">Total Komoditas</h6>
          <h3 class="fw-bold text-hijau"><?= $kpi_total_komoditas ?></h3>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
          <i class="bi bi-clock-history fs-2 text-info"></i>
          <h6 class="text-muted mt-2 mb-1">Update Terakhir</h6>
          <h3 class="fs-5 fw-bold mt-2"><?= htmlspecialchars($kpi_update_terakhir) ?></h3>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-12 col-lg-8">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
          <h5 class="fw-bold mb-3">Tren Harga Beras 6 Bulan Terakhir</h5>
          <?php if(!empty($harga_beras)): ?>
            <canvas id="chartBeras" height="100"></canvas>
          <?php else: ?>
            <p class="text-muted text-center py-5">Belum ada data beras untuk grafik.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
    
    <div class="col-12 col-lg-4">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
          <h5 class="fw-bold mb-3">Harga Terbaru</h5>
          <div class="table-responsive">
            <table class="table table-sm table-borderless align-middle">
              <thead>
                <tr class="text-muted small">
                  <th>Komoditas</th>
                  <th>Harga</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php if(!empty($harga_terbaru)): ?>
                  <?php foreach($harga_terbaru as $row): ?>
                  <tr>
                    <td><?= htmlspecialchars($row['komoditas']) ?></td>
                    <td class="fw-bold">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td><span class="badge bg-<?= $row['badge'] ?> rounded-pill"><?= $row['status'] ?></span></td>
                  </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="3" class="text-center text-muted small">Tidak ada data terbaru</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if(!empty($harga_beras)): ?>
const ctx = document.getElementById('chartBeras');
new Chart(ctx, {
  type: 'line',
  data: {
    labels: <?= json_encode($bulan) ?>,
    datasets: [{
      label: 'Harga Beras /kg',
      data: <?= json_encode($harga_beras) ?>,
      borderColor: '#2E7D32',
      backgroundColor: 'rgba(46, 125, 50, 0.1)',
      tension: 0.4,
      fill: true
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: false } }
  }
});
<?php endif; ?>
</script>
</body>
</html>

<?php include 'partials/footer.php'; ?>