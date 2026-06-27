<?php
session_start();
if(!isset($_SESSION['username'])) { header("Location: login/login.php"); exit; }
include 'partials/header.php';
include 'koneksi.php';


$data_komoditas = [];
$res = $koneksi->query("SELECT Komoditas, AVG(Harga) as avg_harga FROM tb_data_harga GROUP BY Komoditas ORDER BY avg_harga DESC LIMIT 8");
if($res) {
    while($row = $res->fetch_assoc()) $data_komoditas[] = $row;
}


$bulan = [];
$harga_beras = [];
$harga_cabai = [];
$res = $koneksi->query("SELECT `Bulan/Tahun`, Komoditas, AVG(Harga) as avg FROM tb_data_harga WHERE Komoditas LIKE '%Beras%' OR Komoditas LIKE '%Cabai%' GROUP BY `Bulan/Tahun`, Komoditas ORDER BY `Bulan/Tahun` ASC LIMIT 12");

if($res) {
    while($row = $res->fetch_assoc()){

        $timestamp = strtotime($row['Bulan/Tahun']);
        $label_bulan = $timestamp ? date('M Y', $timestamp) : $row['Bulan/Tahun'];
        
        if(!in_array($label_bulan, $bulan)) {
            $bulan[] = $label_bulan;
        }
        if(strpos($row['Komoditas'], 'Beras') !== false) $harga_beras[] = $row['avg'];
        if(strpos($row['Komoditas'], 'Cabai') !== false) $harga_cabai[] = $row['avg'];
    }
}


$data_provinsi = [];
$res = $koneksi->query("SELECT Provinsi, AVG(Harga) as avg FROM tb_data_harga GROUP BY Provinsi ORDER BY avg DESC LIMIT 5");
if($res) {
    while($row = $res->fetch_assoc()) $data_provinsi[] = $row;
}
?>
<h3 class="fw-bold mb-4">Grafik & Analisis Harga Pangan</h3>

<div class="row g-3 mb-4">
  <div class="col-12 col-lg-8">
    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body p-4">
        <h5 class="fw-bold mb-3">Besaran Tren Harga Beras vs Cabai</h5>
        <canvas id="lineChart" height="100"></canvas>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-4">
    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body p-4">
        <h5 class="fw-bold mb-3">5 Komoditas Termahal</h5>
        <canvas id="barChart"></canvas>
      </div>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-12 col-lg-6">
    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body p-4">
        <h5 class="fw-bold mb-3">5 Provinsi Harga Rata2 Tertinggi</h5>
        <canvas id="provChart"></canvas>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-6">
    <div class="card border-0 shadow-sm rounded-4 bg-success text-white">
      <div class="card-body p-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-lightbulb me-2"></i>Insight Otomatis</h5>
        <ul class="mb-0">
          <li>Komoditas termahal: <b><?= !empty($data_komoditas) ? htmlspecialchars($data_komoditas[0]['Komoditas']) : '-'?></b> Rp<?= number_format(($data_komoditas[0]['avg_harga'] ?? 0), 0, ',', '.')?></li>
          <li>Provinsi termahal: <b><?= !empty($data_provinsi) ? htmlspecialchars($data_provinsi[0]['Provinsi']) : '-'?></b></li>
          <li>Tren: Harga <?= (count($harga_cabai) > 1 && end($harga_cabai) > $harga_cabai[0]) ? 'cenderung naik' : 'stabil'?> 6 bulan terakhir</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx1 = document.getElementById('lineChart');
new Chart(ctx1, {
  type: 'line',
  data: {
    labels: <?= json_encode($bulan)?>,
    datasets: [{
      label: 'Beras',
      data: <?= json_encode($harga_beras)?>,
      borderColor: '#2E7D32',
      backgroundColor: 'rgba(46, 125, 50, 0.1)',
      tension: 0.4,
      fill: true
    },{
      label: 'Cabai',
      data: <?= json_encode($harga_cabai)?>,
      borderColor: '#F57C00',
      backgroundColor: 'rgba(245, 124, 0, 0.1)',
      tension: 0.4,
      fill: true
    }]
  },
  options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

const ctx2 = document.getElementById('barChart');
new Chart(ctx2, {
  type: 'bar',
  data: {
    labels: <?= json_encode(array_slice(array_column($data_komoditas, 'Komoditas'), 0, 5))?>,
    datasets: [{
      label: 'Rata2 Harga',
      data: <?= json_encode(array_slice(array_column($data_komoditas, 'avg_harga'), 0, 5))?>,
      backgroundColor: '#2E7D32'
    }]
  },
  options: { responsive: true, plugins: { legend: { display: false } }, indexAxis: 'y' }
});

const ctx3 = document.getElementById('provChart');
new Chart(ctx3, {
  type: 'doughnut',
  data: {
    labels: <?= json_encode(array_column($data_provinsi, 'Provinsi'))?>,
    datasets: [{
      data: <?= json_encode(array_column($data_provinsi, 'avg'))?>,
      backgroundColor: ['#2E7D32', '#43A047', '#66BB6A', '#81C784', '#A5D6A7']
    }]
  },
  options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
</script>
<style>     
body { background-color: #506e5e; }
</style>
<?php 
$koneksi->close();
include 'partials/footer.php';
?>