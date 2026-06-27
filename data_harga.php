<?php 
include 'partials/header.php';
include 'koneksi.php';

$query = "SELECT Id_Pangan, Provinsi, Komoditas, `Bulan/Tahun`, Harga, Satuan, Gambar, Keterangan FROM tb_data_harga ORDER BY `Bulan/Tahun` DESC";
$result = $koneksi->query($query);
?>

<h3 class="fw-bold mb-4">Data Harga Komoditas</h3>

<div class="card shadow-sm border-0 rounded-4">
  <div class="card-body p-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h5 class="fw-bold mb-1">Tabel Harga Real-Time</h5>
        <small class="text-muted">34 Provinsi • Update otomatis</small>
      </div>
      <div class="d-flex gap-2">
        <a href="cetak_pdf.php" class="btn btn-danger" target="_blank">
          <i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF
        </a>
        <a href="tambah.php" class="btn btn-success">
          <i class="bi bi-plus-circle me-1"></i> Tambah Data
        </a>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-4 mb-2">
        <input type="text" class="form-control" id="searchInput" placeholder="Cari komoditas/provinsi...">
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle" id="tabelHarga">
        <thead class="table-success">
          <tr>
            <th>No</th>
            <th>Gambar</th>
            <th>Komoditas</th>
            <th>Provinsi</th>
            <th>Tggl Update</th>
            <th>Harga</th>
            <th>Satuan</th>
            <th>Keterangan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $no = 1;
          if($result && $result->num_rows > 0):
          while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td>
              <?php if(!empty($row['Gambar']) && file_exists($row['Gambar'])): ?>
                <img src="<?= $row['Gambar'] ?>" alt="Pangan" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
              <?php else: ?>
                <i class="bi bi-image text-muted fs-4"></i>
              <?php endif; ?>
            </td>
            <td class="fw-semibold"><?= htmlspecialchars($row['Komoditas']) ?></td>
            <td><?= htmlspecialchars($row['Provinsi']) ?></td>
            <td><?= htmlspecialchars($row['Bulan/Tahun']) ?></td>
            <td class="text-success fw-bold">Rp <?= number_format($row['Harga'], 0, ',', '.') ?></td>
            <td><?= htmlspecialchars($row['Satuan']) ?></td>
            <td><small class="text-muted"><?= htmlspecialchars($row['Keterangan']) ?></small></td>
            <td>
              <a href="edit.php?id=<?= $row['Id_Pangan'] ?>" class="btn btn-sm btn-outline-warning me-1">
                <i class="bi bi-pencil"></i>
              </a>
              <a href="hapus.php?id=<?= $row['Id_Pangan'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus data <?= htmlspecialchars($row['Komoditas']) ?>?')">
                <i class="bi bi-trash"></i>
              </a>
            </td>
          </tr>
          <?php endwhile;
          else: ?>
          <tr>
            <td colspan="9" class="text-center text-muted py-5">
              <i class="bi bi-inbox fs-2 d-block mb-2"></i> Belum ada data harga
            </td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

<script>
  document.getElementById('searchInput').addEventListener('keyup', function() {
    let input = this.value.toLowerCase();
    let rows = document.querySelectorAll('#tabelHarga tbody tr');
    rows.forEach(row => {
      row.style.display = row.innerText.toLowerCase().includes(input) ? '' : 'none';
    });
  });
</script>
<style>     
body { background-color: #506e5e; }
</style>
<?php 
$koneksi->close();
include 'partials/footer.php';
?>