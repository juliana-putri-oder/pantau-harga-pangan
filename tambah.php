<?php 
session_start();
if(!isset($_SESSION['username'])) {
    header("Location: login/login.php");
    exit;
}
include 'partials/header.php'; 
include 'koneksi.php';

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $Komoditas = $_POST['Komoditas'];
    $Provinsi = $_POST['Provinsi'];
    $BulanTahun = $_POST['Bulan_Tahun']; 
    $Harga = $_POST['Harga'];
    $Satuan = $_POST['Satuan'];
    $Keterangan = $_POST['Keterangan'];
    $foto = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];
    $folder = "uploads/";

    if(!file_exists($folder)){
        mkdir($folder, 0777, true);
    }

    $target_file = $folder . time() . '_' . basename($foto); 

    if(move_uploaded_file($tmp, $target_file)){
        $sql = "INSERT INTO tb_data_harga (Komoditas, Provinsi, `Bulan/Tahun`, Harga, Satuan, Gambar, Keterangan) 
                VALUES ('$Komoditas', '$Provinsi', '$BulanTahun', '$Harga', '$Satuan', '$target_file', '$Keterangan')";
        
        if(mysqli_query($koneksi, $sql)){
            echo "<script>alert('Data berhasil disimpan');window.location.href='data_harga.php';</script>";
        }else{
            echo "<script>alert('Error Database: " . mysqli_error($koneksi) . "');</script>";
        }
    }else{
        echo "<script>alert('Error: Gagal mengupload gambar ke server.');</script>";
    }
}
?>
<div class="container-fluid mt-4">
    <div class="card shadow-sm border-0 rounded-4 p-4">
        <h1 class="fw-bold mb-4">Tambah Data Pangan</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="Komoditas" class="form-label">Komoditas</label>
                <input type="text" class="form-control" id="Komoditas" name="Komoditas" required>
            </div>
            <div class="mb-3">
                <label for="Provinsi" class="form-label">Provinsi</label>
                <input type="text" class="form-control" id="Provinsi" name="Provinsi" required>
            </div>
            <div class="mb-3">
                <label for="Bulan_Tahun" class="form-label">Tggl Update</label>
                <input type="date" class="form-control" id="Bulan_Tahun" name="Bulan_Tahun" required>
            </div>
            
            <div class="mb-3">
                <label for="Harga" class="form-label">Harga</label>
                <div class="input-group">
                    <span class="input-group-text bg-light fw-bold text-muted">Rp</span>
                    <input type="number" step="0.01" class="form-control" id="Harga" name="Harga" placeholder="Contoh: 56800" required>
                </div>
                <small class="text-muted d-block mt-1">*Masukkan angka saja tanpa tanda titik ribuan.</small>
            </div>
            
            <div class="mb-3">
                <label for="Satuan" class="form-label">Satuan</label>
                <select name="Satuan" id="Satuan" class="form-control" required>
                    <option value="">Pilih Satuan</option>
                    <option value="Kg">Kilogram</option>
                    <option value="Liter">Liter</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="Keterangan" class="form-label">Keterangan (Tren)</label>
                <select name="Keterangan" id="Keterangan" class="form-control">
                    <option value="Stabil">Stabil</option>
                    <option value="Naik">Naik</option>
                    <option value="Turun">Turun</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">Gambar</label>
                <input class="form-control" type="file" id="file" name="file" accept="image/*" required>
                <img id="preview" src="#" alt="Preview" style="display:none; max-width:200px; margin-top:10px; border-radius: 8px;">
            </div>
            <button type="submit" class="btn btn-primary px-4">Simpan</button>
            <a href="data_harga.php" class="btn btn-secondary px-4">Kembali</a>
        </form>
    </div>
</div>

<script>
document.getElementById('file').addEventListener('change', function(event){
    const file = event.target.files[0];
    if (file){
        const reader = new FileReader();
        reader.onload = function(){
            const preview = document.getElementById('preview');
            preview.src = reader.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});
</script>
<?php include 'partials/footer.php'; ?>