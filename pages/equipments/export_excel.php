<?php
require '../../config/db.php';
if (session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user'])) { header('Location: /inventory-php/login.php'); exit; }

// Simple Excel export via HTML table (Excel will open it)
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=equipments.xls");
echo "<table border='1'><tr><th>Kode</th><th>Nama</th><th>Merk</th><th>Model</th><th>Jumlah</th><th>Kondisi</th><th>Lokasi</th><th>Tanggal Dibuat</th></tr>";
$result = mysqli_query($conn, "SELECT * FROM equipments ORDER BY id DESC");
while($row = mysqli_fetch_assoc($result)){
    echo '<tr>';
    echo '<td>'.htmlspecialchars($row['code']).'</td>';
    echo '<td>'.htmlspecialchars($row['name']).'</td>';
    echo '<td>'.htmlspecialchars($row['brand']).'</td>';
    echo '<td>'.htmlspecialchars($row['model']).'</td>';
    echo '<td>'.htmlspecialchars($row['quantity']).'</td>';
    echo '<td>'.htmlspecialchars($row['condition']).'</td>';
    echo '<td>'.htmlspecialchars($row['location']).'</td>';
    echo '<td>'.htmlspecialchars($row['created_at']).'</td>';
    echo '</tr>';
}
echo '</table>';
exit;