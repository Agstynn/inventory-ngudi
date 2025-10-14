<?php include('../../config/db.php'); 
if (session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user'])) { header('Location: /inventory-php/login.php'); exit; }

$id = (int)($_GET['id'] ?? 0);
$stmt = mysqli_prepare($conn, "SELECT * FROM equipments WHERE id=?");
mysqli_stmt_bind_param($stmt,'i',$id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($res);
if(!$data){ header('Location: list.php'); exit; }

if(isset($_POST['update'])) {
    $code = $_POST['code']; $name = $_POST['name']; $brand = $_POST['brand'];
    $quantity = (int)$_POST['quantity']; $condition = $_POST['condition']; $location = $_POST['location'];

    $gambar_name = $data['gambar'];
    if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] == UPLOAD_ERR_OK){
        $allowed = array('jpg','jpeg','png');
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        if(!in_array($ext, $allowed)){
            $err = "Format file tidak didukung. Gunakan jpg/jpeg/png.";
        } else if($_FILES['gambar']['size'] > 2 * 1024 * 1024){
            $err = "File terlalu besar (maks 2MB)."; 
        } else {
            if(!is_dir(__DIR__ . '/../../uploads')) mkdir(__DIR__ . '/../../uploads', 0755, true);
            $newname = time() . '_' . preg_replace('/[^A-Za-z0-9_\.\-]/', '_', $_FILES['gambar']['name']);
            move_uploaded_file($_FILES['gambar']['tmp_name'], __DIR__ . '/../../uploads/' . $newname);
            // delete old file
            if(!empty($gambar_name) and file_exists(__DIR__ . '/../../uploads/' . $gambar_name)){
                @unlink(__DIR__ . '/../../uploads/' . $gambar_name);
            }
            $gambar_name = $newname;
        }
    }

    $stmt = mysqli_prepare($conn, "UPDATE equipments SET code=?, name=?, brand=?, quantity=?, `condition`=?, location=?, gambar=? WHERE id=?");
    mysqli_stmt_bind_param($stmt,'sssiissi',$code,$name,$brand,$quantity,$condition,$location,$gambar_name,$id);
    mysqli_stmt_execute($stmt);
    header('Location: list.php');
    exit;
}
include '../../includes/header.php';
?>
<div class="container mt-2">
<h3>Edit Alat</h3>
<?php if(isset($err)): ?><div class="alert alert-danger"><?=htmlspecialchars($err)?></div><?php endif; ?>
<form method="POST" enctype="multipart/form-data">
  <div class="mb-3"><label>Kode</label><input class="form-control" name="code" value="<?=htmlspecialchars($data['code'])?>" required></div>
  <div class="mb-3"><label>Nama</label><input class="form-control" name="name" value="<?=htmlspecialchars($data['name'])?>" required></div>
  <div class="mb-3"><label>Merk</label><input class="form-control" name="brand" value="<?=htmlspecialchars($data['brand'])?>"></div>
  <div class="mb-3"><label>Jumlah</label><input class="form-control" type="number" name="quantity" value="<?=htmlspecialchars($data['quantity'])?>" required></div>
  <div class="mb-3"><label>Kondisi</label>
    <select name="condition" class="form-select">
      <option value="Baik" <?= $data['condition']=='Baik'?'selected':'' ?>>Baik</option>
      <option value="Rusak" <?= $data['condition']=='Rusak'?'selected':'' ?>>Rusak</option>
      <option value="Perbaikan" <?= $data['condition']=='Perbaikan'?'selected':'' ?>>Perbaikan</option>
    </select>
  </div>
  <div class="mb-3"><label>Lokasi</label><input class="form-control" name="location" value="<?=htmlspecialchars($data['location'])?>"></div>
  <div class="mb-3">
    <label>Gambar (jpg/png, max 2MB)</label>
    <?php if(!empty($data['gambar']) && file_exists(__DIR__ . '/../../uploads/' . $data['gambar'])): ?>
      <div class="mb-2"><img src="<?= '/inventory-php/uploads/' . $data['gambar'] ?>" style="max-width:180px"></div>
    <?php endif; ?>
    <input class="form-control" type="file" name="gambar" accept="image/*">
  </div>
  <button class="btn btn-success" name="update">Update</button>
  <a href="list.php" class="btn btn-secondary">Batal</a>
</form>
</div>
<?php include '../../includes/footer.php'; ?>