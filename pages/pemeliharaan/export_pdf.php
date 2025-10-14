<?php
require '../../config/db.php';
if (session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user'])) { header('Location: /inventory-php/login.php'); exit; }
$res = mysqli_query($conn, "SELECT * FROM pemeliharaan_alat ORDER BY tanggal DESC");
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Cetak Pemeliharaan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body>
<div class="container mt-3">
  <h3>Daftar Pemeliharaan</h3>
  <table class="table table-bordered"><thead><tr><th>Tanggal</th><th>Nama Alat</th><th>No Seri</th><th>Ruangan</th><th>Jenis</th><th>Keterangan</th></tr></thead><tbody>
<?php while($r=mysqli_fetch_assoc($res)): ?>
<tr>
<td><?=htmlspecialchars($r['tanggal'])?></td>
<td><?=htmlspecialchars($r['nama_alat'])?></td>
<td><?=htmlspecialchars($r['no_seri'])?></td>
<td><?=htmlspecialchars($r['ruangan'])?></td>
<td><?=htmlspecialchars($r['jenis_pemeliharaan'])?></td>
<td><?=htmlspecialchars($r['keterangan'])?></td>
</tr>
<?php endwhile; ?>
</tbody></table>
<div class="no-print"><button onclick="window.print()" class="btn btn-primary">Cetak / Simpan PDF</button></div>
</div></body></html>
<?php exit;?>