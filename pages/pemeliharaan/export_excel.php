<?php
require '../../config/db.php';
if (session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user'])) { header('Location: /inventory-php/login.php'); exit; }
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=pemeliharaan.xls");
echo "<table border='1'><tr><th>Tanggal</th><th>Nama Alat</th><th>No Seri</th><th>Ruangan</th><th>Jenis</th><th>Keterangan</th></tr>";
$res = mysqli_query($conn, "SELECT * FROM pemeliharaan_alat ORDER BY tanggal DESC");
while($r = mysqli_fetch_assoc($res)){
  echo '<tr>';
  echo '<td>'.htmlspecialchars($r['tanggal']).'</td>';
  echo '<td>'.htmlspecialchars($r['nama_alat']).'</td>';
  echo '<td>'.htmlspecialchars($r['no_seri']).'</td>';
  echo '<td>'.htmlspecialchars($r['ruangan']).'</td>';
  echo '<td>'.htmlspecialchars($r['jenis_pemeliharaan']).'</td>';
  echo '<td>'.htmlspecialchars($r['keterangan']).'</td>';
  echo '</tr>';
}
echo '</table>';
exit;