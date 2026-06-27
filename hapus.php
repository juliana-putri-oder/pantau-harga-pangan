<?php
session_start();
if(!isset($_SESSION['username'])) {
    header("Location: login/login.php");
    exit;
}
include 'koneksi.php';

if(isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    $query_select = "SELECT Gambar FROM tb_data_harga WHERE Id_Pangan = ?";
    $stmt_select = $koneksi->prepare($query_select);
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();

    if($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        if(!empty($data['Gambar']) && file_exists($data['Gambar'])) {
            unlink($data['Gambar']);
        }
    }

    
    $query_delete = "DELETE FROM tb_data_harga WHERE Id_Pangan = ?";
    $stmt_delete = $koneksi->prepare($query_delete);
    $stmt_delete->bind_param("i", $id);

    if($stmt_delete->execute()) {
        echo "<script>alert('Data berhasil dihapus!'); window.location.href='data_harga.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data dari database: " . $koneksi->error . "'); window.location.href='data_harga.php';</script>";
    }
    $stmt_delete->close();
} else {
    echo "<script>alert('ID Data tidak valid!'); window.location.href='data_harga.php';</script>";
}

$koneksi->close();
?>