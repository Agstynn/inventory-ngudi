<?php
require '../../config/db.php';
if (session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user'])) { header('Location: /inventory-php/login.php'); exit; }
$result = mysqli_query($conn, "SELECT * FROM equipments ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Cetak Daftar Alat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    @media print {
      .no-print { display:none; }
    }
  </style>
</head>
<body>
<div class="container mt-3">
  <div class="d-flex justify-content-between mb-3">
    <h3>Daftar Alat - Universitas Ngudi Waluyo</h3>
    <div class="no-print">
      <button onclick="window.print()" class="btn btn-primary">Cetak / Simpan PDF</button>
      <a href="list.php" class="btn btn-secondary">Kembali</a>
    </div>
  </div>
  <table class="table table-bordered">
    <thead><tr><th>Kode</th><th>Nama</th><th>Merk</th><th>Qty</th><th>Kondisi</th><th>Lokasi</th></tr></thead>
    <tbody>
    <?php while($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td><?=htmlspecialchars($row['code'])?></td>
        <td><?=htmlspecialchars($row['name'])?></td>
        <td><?=htmlspecialchars($row['brand'])?></td>
        <td><?=htmlspecialchars($row['quantity'])?></td>
        <td><?=htmlspecialchars($row['condition'])?></td>
        <td><?=htmlspecialchars($row['location'])?></td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
