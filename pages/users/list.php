<?php
require '../../config/db.php';
if (session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['level']) || $_SESSION['level']!='admin') { header('Location: /inventory-php/login.php'); exit; }
include '../../includes/header.php';

$res = mysqli_query($conn, "SELECT id, username, full_name, level, created_at FROM users ORDER BY id ASC");
?>
<div class="d-flex justify-content-between mb-3">
  <h2>Manage Users</h2>
  <div>
    <a href="form.php" class="btn btn-success">+ Tambah User</a>
  </div>
</div>

<table class="table table-bordered">
  <thead class="table-light"><tr><th>ID</th><th>Username</th><th>Nama Lengkap</th><th>Level</th><th>Dibuat</th><th>Aksi</th></tr></thead>
  <tbody>
  <?php while($r = mysqli_fetch_assoc($res)): ?>
    <tr>
      <td><?=htmlspecialchars($r['id'])?></td>
      <td><?=htmlspecialchars($r['username'])?></td>
      <td><?=htmlspecialchars($r['full_name'])?></td>
      <td><?=htmlspecialchars($r['level'])?></td>
      <td><?=htmlspecialchars($r['created_at'])?></td>
      <td>
        <a href="form.php?id=<?=$r['id']?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="delete.php?id=<?=$r['id']?>" onclick="return confirm('Hapus user ini?')" class="btn btn-sm btn-danger">Hapus</a>
      </td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>

<?php include '../../includes/footer.php'; ?>