<?php 
$server = "localhost";
$username = "root";
$password = "";
$database = "pantau_harga_pangan";
$koneksi = mysqli_connect($server, $username, $password, $database);

if (!$koneksi){
    die("Koneksi gagal " . mysqli_connect_error());
}