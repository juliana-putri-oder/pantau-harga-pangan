<?php
session_start();
include '../koneksi.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    
    // Query untuk mencocokkan username dan password teks biasa
    $query = "SELECT * FROM tb_login WHERE username='$username' AND password='$password'";
    $result = mysqli_query($koneksi, $query);
    
    if(mysqli_num_rows($result) > 0) {
        $_SESSION['username'] = $username;
        // Pindah ke dasbord.php yang ada di folder utama (root)
        header("Location: ../dashboard.php"); 
        exit; 
    } else {
        header("Location: ../login.php?error=1");
        exit;
    }
}
?>