<?php
include('../../config/db.php'); 

if (session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user'])) { 
    header('Location: /inventory-php/login.php'); 
    exit; 
}

$err = ''; // untuk menampung pesan error

if (isset($_POST['save'])) {
    $code = $_POST['code']; 
    $name = $_POST['name']; 
    $brand = $_POST['brand'];
    $model = $_POST['model']; 
    $quantity = (int)$_POST['quantity']; 
    $condition = $_POST['condition'];
    $location = $_POST['location'];

    $gambar_name = NULL;

    // Handle upload gambar
    if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE){
        $allowed = ['jpg','jpeg','png'];
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));

        if(!in_array($ext, $allowed)){
            $err = "Format file tidak didukung. Gunakan jpg/jpeg/png.";
        } elseif($_FILES['gambar']['size'] > 2 * 1024 * 1024){
            $err = "File terlalu besar (maks 2MB)."; 
        } else {
            $uploadDir = __DIR__ . '/../../uploads/';
            if(!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $gambar_name = time() . '_' . preg_replace('/[^A-Za-z0-9_\.\-]/', '_', $_FILES['gambar']['name']);
            $destPath = $uploadDir . $gambar_name;

            if(!move_uploaded_file($_FILES['gambar']['tmp_name'], $destPath)){
                $err = "Gagal mengunggah gambar.";
            }
        }
    }

    // Jika tidak ada error, simpan ke database
    if(empty($err)){
        $stmt = mysqli_prepare($conn, "INSERT INTO equipments (code,name,brand,model,quantity,`condition`,location,gambar) VALUES (?,?,?,?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt,'ssssisss',$code,$name,$brand,$model,$quantity,$condition,$location,$gambar_name);
        if(mysqli_stmt_execute($stmt)){
            header('Location: list.php');
            exit;
        } else {
            $err = "Gagal menyimpan data: " . mysqli_stmt_error($stmt);
        }
    }
}

include '../../includes/header.php';
?>

<div class="container mt-2">
<h3>Tambah Alat</h3>

<?php if(!empty($err)): ?>
    <div class="alert alert-danger"><?=htmlspecialchars($err)?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
  <div class="mb-3"><label>Kode</label><input class="form-control" name="code" required></div>
  <div class="mb-3"><label>Nama</label><input class="form-control" name="name" required></div>
  <div class="mb-3"><label>Merk</label><input class="form-control" name="brand"></div>
  <div class="mb-3"><label>Model</label><input class="form-control" name="model"></div>
  <div class="mb-3"><label>Jumlah</label><input class="form-control" type="number" name="quantity" value="1" required></div>
  <div class="mb-3"><label>Kondisi</label>
    <select name="condition" class="form-select">
      <option value="Baik">Baik</option>
      <option value="Rusak">Rusak</option>
      <option value="Perbaikan">Perbaikan</option>
    </select>
  </div>
  <div class="mb-3"><label>Lokasi</label><input class="form-control" name="location"></div>
  <div class="mb-3"><label>Gambar (jpg/png, max 2MB)</label><input class="form-control" type="file" name="gambar" accept="image/*"></div>
  <button class="btn btn-success" name="save">Simpan</button>
  <a href="list.php" class="btn btn-secondary">Batal</a>
</form>
</div>

<?php include '../../includes/footer.php'; ?>
