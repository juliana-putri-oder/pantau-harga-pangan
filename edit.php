<?php 
session_start();
if(!isset($_SESSION['username'])) {
    header("Location: login/login.php");
    exit;
}
include 'partials/header.php'; 
include 'koneksi.php';

if(!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID Data tidak ditemukan!'); window.location.href='data_harga.php';</script>";
    exit;
}

$id = $_GET['id'];

$query_select = "SELECT * FROM tb_data_harga WHERE Id_Pangan = ?";
$stmt = $koneksi->prepare($query_select);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0) {
    echo "<script>alert('Data tidak ditemukan di database!'); window.location.href='data_harga.php';</script>";
    exit;
}

$data = $result->fetch_assoc();

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $Komoditas = $_POST['Komoditas'];
    $Provinsi = $_POST['Provinsi'];
    $BulanTahun = $_POST['Bulan_Tahun'];
    $Harga = $_POST['Harga'];
    $Satuan = $_POST['Satuan'];
    $Keterangan = $_POST['Keterangan'];
    
    $foto = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];
    $folder = "uploads/";

    if(!empty($foto)) {
        if(!file_exists($folder)){
            mkdir($folder, 0777, true);
        }
        $target_file = $folder . time() . '_' . basename($foto);
        
        if(move_uploaded_file($tmp, $target_file)) {
            
            if(!empty($data['Gambar']) && file_exists($data['Gambar'])) {
                unlink($data['Gambar']);
            }
            
            $sql_update = "UPDATE tb_data_harga SET Komoditas=?, Provinsi=?, `Bulan/Tahun`=?, Harga=?, Satuan=?, Gambar=?, Keterangan=? WHERE Id_Pangan=?";
            $stmt_update = $koneksi->prepare($sql_update);
            $stmt_update->bind_param("sssdsssi", $Komoditas, $Provinsi, $BulanTahun, $Harga, $Satuan, $target_file, $Keterangan, $id);
        } else {
            echo "<script>alert('Error: Gagal mengupload gambar baru.');</script>";
        }
    } else {
        
        $sql_update = "UPDATE tb_data_harga SET Komoditas=?, Provinsi=?, `Bulan/Tahun`=?, Harga=?, Satuan=?, Keterangan=? WHERE Id_Pangan=?";
        $stmt_update = $koneksi->prepare($sql_update);
        $stmt_update->bind_param("sssdssi", $Komoditas, $Provinsi, $BulanTahun, $Harga, $Satuan, $Keterangan, $id);
    }

    if(isset($stmt_update) && $stmt_update->execute()) {
        echo "<script>alert('Data berhasil diperbarui'); window.location.href='data_harga.php';</script>";
    } else {
        echo "<script>alert('Error Database: " . $koneksi->error . "');</script>";
    }
}
?>

<div class="main-content">
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-lg-12">
                <header class="py-2 mb-4">
                    <h1 class="fw-bold">Edit Data Pangan</h1>
                </header>
                <div class="card shadow-sm border-0 rounded-4 p-4">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="Komoditas" class="form-label">Komoditas</label>
                            <input type="text" class="form-control" id="Komoditas" name="Komoditas" value="<?= htmlspecialchars($data['Komoditas']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="Provinsi" class="form-label">Provinsi</label>
                            <input type="text" class="form-control" id="Provinsi" name="Provinsi" value="<?= htmlspecialchars($data['Provinsi']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="Bulan_Tahun" class="form-label">Bulan/Tahun</label>
                            <input type="date" class="form-control" id="Bulan_Tahun" name="Bulan_Tahun" value="<?= $data['Bulan/Tahun'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="Harga" class="form-label">Harga</label>
                            <input type="number" step="0.01" class="form-control" id="Harga" name="Harga" value="<?= $data['Harga'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="Satuan" class="form-label">Satuan</label>
                            <select name="Satuan" id="Satuan" class="form-control" required>
                                <option value="Kg" <?= $data['Satuan'] == 'Kg' ? 'selected' : '' ?>>Kilogram</option>
                                <option value="Liter" <?= $data['Satuan'] == 'Liter' ? 'selected' : '' ?>>Liter</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="Keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="Keterangan" name="Keterangan" rows="3"><?= htmlspecialchars($data['Keterangan']) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">Gambar Pangan</label>
                            <input class="form-control" type="file" id="file" name="file" accept="image/*">
                            <small class="text-muted d-block mt-1">Biarkan kosong jika tidak ingin mengubah gambar lama.</small>
                            
                            <div class="mt-3">
                                <p class="mb-1 small text-muted">Preview Gambar:</p>
                                <?php if(!empty($data['Gambar']) && file_exists($data['Gambar'])): ?>
                                    <img id="preview" src="<?= $data['Gambar'] ?>" alt="Preview" style="max-width:200px; border-radius: 8px;">
                                <?php else: ?>
                                    <img id="preview" src="#" alt="Preview" style="display:none; max-width:200px; border-radius: 8px;">
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="py-3">
                            <button type="submit" class="btn btn-primary px-4">Update Data</button>
                            <a href="data_harga.php" class="btn btn-secondary px-4">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('file').addEventListener('change', function(event){
    const file = event.target.files[0];
    if (file){
        const reader = new FileReader();
        document.getElementById('preview').style.display = 'block';
        reader.onload = function(){
            const preview = document.getElementById('preview');
            preview.src = reader.result;
        }
        reader.readAsDataURL(file);
    }
});
</script>

<?php 
$koneksi->close();
include 'partials/footer.php'; 
?>