<?php
require '../../config/db.php';
if (session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user'])) { header('Location: /inventory-php/login.php'); exit; }

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=equipments.csv');
$output = fopen('php://output', 'w');
fputcsv($output, ['Kode','Nama','Merk','Model','Jumlah','Kondisi','Lokasi','Tanggal Dibuat']);
$result = mysqli_query($conn, "SELECT * FROM equipments ORDER BY id DESC");
while($row = mysqli_fetch_assoc($result)){
    fputcsv($output, [$row['code'],$row['name'],$row['brand'],$row['model'],$row['quantity'],$row['condition'],$row['location'],$row['created_at']]);
}
fclose($output);
exit;