<?php
require '../../config/db.php';
if (session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user'])) { header('Location: /inventory-php/login.php'); exit; }
include '../../includes/header.php';
$where = [];
if(!empty($_GET['from'])) $where[] = "tanggal >= '" . mysqli_real_escape_string($conn,$_GET['from']) . "'";
if(!empty($_GET['to'])) $where[] = "tanggal <= '" . mysqli_real_escape_string($conn,$_GET['to']) . "'";
$w = count($where)?("WHERE ".implode(" AND ",$where)):"";
$result = mysqli_query($conn, "SELECT p.*, a.kode AS kode_alat FROM perbaikan_alat p LEFT JOIN alat a ON p.alat_id=a.id $w ORDER BY p.tanggal DESC");
?>
<div class="d-flex justify-content-between mb-3">
  <h2>Perbaikan Alat</h2>
  <div>
    <a href="form.php" class="btn btn-success">+ Tambah Perbaikan</a>
    <a href="export_excel.php" class="btn btn-outline-primary">Export Excel</a>
    <a href="export_pdf.php" class="btn btn-outline-danger">Cetak / PDF</a>
  </div>
</div>
<form class="row g-2 mb-3">
  <div class="col-auto"><input type="date" name="from" class="form-control" value="<?=htmlspecialchars($_GET['from'] ?? '')?>"></div>
  <div class="col-auto"><input type="date" name="to" class="form-control" value="<?=htmlspecialchars($_GET['to'] ?? '')?>"></div>
  <div class="col-auto"><button class="btn btn-secondary">Filter</button></div>
</form>
<table class="table table-bordered">
  <thead class="table-light"><tr><th>Tanggal</th><th>Nama Alat</th><th>No Seri</th><th>Ruangan</th><th>Status</th><th>Keterangan</th><th>Aksi</th></tr></thead>
  <tbody>
    <?php while($row = mysqli_fetch_assoc($result)): ?>
    <tr>
      <td><?=htmlspecialchars($row['tanggal'])?></td>
      <td><?=htmlspecialchars($row['nama_alat'])?></td>
      <td><?=htmlspecialchars($row['no_seri'])?></td>
      <td><?=htmlspecialchars($row['ruangan'])?></td>
      <td><?=htmlspecialchars($row['status'])?></td>
      <td><?=htmlspecialchars($row['keterangan'])?></td>
      <td>
        <a href="form.php?id=<?=$row['id_perbaikan']?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="delete.php?id=<?=$row['id_perbaikan']?>" onclick="return confirm('Hapus data?')" class="btn btn-sm btn-danger">Hapus</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<?php include '../../includes/footer.php'; ?>