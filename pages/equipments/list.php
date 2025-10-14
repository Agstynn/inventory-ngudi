<?php
require '../../config/db.php';
if (session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user'])) { header('Location: /inventory-php/login.php'); exit; }

include '../../includes/header.php';

$result = mysqli_query($conn, "SELECT * FROM equipments ORDER BY id DESC");
?>

<!-- Tambahkan CSS kecil untuk memperbesar tampilan foto -->
<style>
.img-thumb {
  width: 150px;          /* ukuran lebih besar */
  height: 150px;         /* proporsional */
  object-fit: cover;     /* menjaga proporsi tanpa distorsi */
  border-radius: 8px;    /* opsional: sudut lembut */
  box-shadow: 0 2px 6px rgba(0,0,0,0.15); /* opsional: bayangan halus */
}
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h2>Daftar Alat</h2>
  <div>
    <a href="add.php" class="btn btn-success">+ Tambah Alat</a>
    <a href="export_excel.php" class="btn btn-outline-primary">Export Excel</a>
    <a href="export_csv.php" class="btn btn-outline-secondary">Export CSV</a>
    <a href="export_pdf.php" class="btn btn-outline-danger">Cetak / PDF</a>
  </div>
</div>

<table class="table table-bordered align-middle text-center">
  <thead class="table-light">
    <tr>
      <th>Foto</th>
      <th>Kode</th>
      <th>Nama</th>
      <th>Merk</th>
      <th>Jumlah</th>
      <th>Kondisi</th>
      <th>Lokasi</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php while($row = mysqli_fetch_assoc($result)): ?>
    <tr>
    <td>
  <?php 
    $path = __DIR__ . '/../../uploads/' . $row['gambar']; 
    if (!empty($row['gambar']) && file_exists($path)): 
  ?>
    <img src="<?= '/inventory-php/uploads/' . htmlspecialchars($row['gambar']) ?>" 
         alt="Foto Alat" 
         style="width: 100px; height: auto; object-fit: cover; border-radius: 8px;">
  <?php else: ?>
    <span class="text-muted">-</span>
  <?php endif; ?>
</td>

      <td><?=htmlspecialchars($row['code'])?></td>
      <td><?=htmlspecialchars($row['name'])?></td>
      <td><?=htmlspecialchars($row['brand'])?></td>
      <td><?=htmlspecialchars($row['quantity'])?></td>
      <td><?=htmlspecialchars($row['condition'])?></td>
      <td><?=htmlspecialchars($row['location'])?></td>
      <td>
        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="delete.php?id=<?= $row['id'] ?>" 
           onclick="return confirm('Hapus data ini?')" 
           class="btn btn-sm btn-danger">Hapus</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include '../../includes/footer.php'; ?>
