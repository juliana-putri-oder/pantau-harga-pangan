<?php
session_start();
if(!isset($_SESSION['username'])) { 
    header("Location: index.php"); 
    exit; 
}

require_once ('vendor/autoload.php');
include 'koneksi.php';

$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); // 'L' untuk Landscape agar tabel muat lebar

$pdf->setCreator(PDF_CREATOR);
$pdf->setTitle("Laporan Real-Time Harga Pangan");

$pdf->setHeaderData('', 0, 'LAPORAN DATA HARGA KOMODITAS PANGAN', 'Sistem Informasi Pantau Harga Pangan Real-Time');
$pdf->setMargins(15, 25, 15);
$pdf->setHeaderMargin(10);
$pdf->setFooterMargin(10);

$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->setFont('helvetica', '', 10);

$pdf->AddPage();

$query = "SELECT Id_Pangan, Provinsi, Komoditas, `Bulan/Tahun`, Harga, Satuan, Gambar, Keterangan FROM tb_data_harga ORDER BY `Bulan/Tahun` DESC";
$result = mysqli_query($koneksi, $query);

$html = '<h2 style="text-align: center; color: #1B5E20;">Daftar Harga Komoditas Pangan Real-Time</h2>';
$html .= '<table border="1" cellpadding="6" cellspacing="0" style="width: 100%; border-collapse: collapse;">';
$html .= '<thead style="background-color: #2E7D32; color: white; font-weight: bold;">
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 12%; text-align: center;">Gambar</th>
                <th style="width: 18%;">Komoditas</th>
                <th style="width: 18%;">Provinsi</th>
                <th style="width: 12%; text-align: center;">Bulan/Tahun</th>
                <th style="width: 15%; text-align: right;">Harga</th>
                <th style="width: 10%; text-align: center;">Satuan</th>
                <th style="width: 10%; text-align: center;">Tren</th>
            </tr>
          </thead>
          <tbody>';

$no = 1;

while ($row = mysqli_fetch_assoc($result)) {
    
    $nama_gambar = isset($row['Gambar']) ? trim($row['Gambar']) : '';
    $tampilan_gambar = '<span style="color:#777; font-size:9px;">Tanpa Foto</span>'; 

    if ($nama_gambar !== "") {
        $file_fisik = dirname(__FILE__) . DIRECTORY_SEPARATOR . $nama_gambar;
        
        if (file_exists($file_fisik) && is_file($file_fisik)) {
            $tipe_file = pathinfo($file_fisik, PATHINFO_EXTENSION);
            $data_gambar = @file_get_contents($file_fisik);
            
            if ($data_gambar !== false) {
                $base64 = 'data:image/' . $tipe_file . ';base64,' . base64_encode($data_gambar);
                $tampilan_gambar = '<img src="' . $base64 . '" style="width:40px; height:40px; object-fit:cover;">';
            }
        } else {
            $tampilan_gambar = '<span style="color:red; font-size:8px;">File Tidak Ada</span>';
        }
    }

    $harga_format = 'Rp ' . number_format($row['Harga'], 0, ',', '.');
    
    $warna_tren = '#333';
    if ($row['Keterangan'] == 'Naik') $warna_tren = '#d32f2f';
    if ($row['Keterangan'] == 'Turun') $warna_tren = '#388e3c';

    $html .= '<tr>
                <td style="text-align:center; width: 5%;">' . $no++ . '</td>
                <td style="text-align:center; vertical-align:middle; width: 12%;">' . $tampilan_gambar . '</td>
                <td style="width: 18%; font-weight: bold;">' . htmlspecialchars($row['Komoditas']) . '</td>
                <td style="width: 18%;">' . htmlspecialchars($row['Provinsi']) . '</td>
                <td style="text-align:center; width: 12%;">' . htmlspecialchars($row['Bulan/Tahun']) . '</td>
                <td style="text-align:right; width: 15%; color: #2E7D32; font-weight: bold;">' . $harga_format . '</td>
                <td style="text-align:center; width: 10%;">' . htmlspecialchars($row['Satuan']) . '</td>
                <td style="text-align:center; width: 10%; color: ' . $warna_tren . '; font-weight: bold;">' . htmlspecialchars($row['Keterangan']) . '</td>
            </tr>';
}

$html .='</tbody></table>';

$pdf->writeHTML($html, true, false, true, false, '');

if (ob_get_contents()) ob_end_clean();

$pdf->Output('Laporan_Harga_Pangan_Realtime.pdf', 'I');
?>