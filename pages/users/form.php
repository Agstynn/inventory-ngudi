<?php
require '../../config/db.php';
if (session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['level']) || $_SESSION['level']!='admin') { header('Location: /inventory-php/login.php'); exit; }
include '../../includes/header.php';

$id = isset($_GET['id'])?(int)$_GET['id']:0;
$data = null;
if($id){
    $stmt = mysqli_prepare($conn, "SELECT id, username, full_name, level FROM users WHERE id=?");
    mysqli_stmt_bind_param($stmt,'i',$id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($res);
}

if($_SERVER['REQUEST_METHOD']=='POST'){
    $username = $_POST['username']; $full_name = $_POST['full_name']; $level = $_POST['level'];
    $password = $_POST['password'] ?? '';
    if($id){
        if(!empty($password)){
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "UPDATE users SET username=?, full_name=?, level=?, password_hash=? WHERE id=?");
            mysqli_stmt_bind_param($stmt,'ssssi',$username,$full_name,$level,$hash,$id);
        } else {
            $stmt = mysqli_prepare($conn, "UPDATE users SET username=?, full_name=?, level=? WHERE id=?");
            mysqli_stmt_bind_param($stmt,'sssi',$username,$full_name,$level,$id);
        }
        mysqli_stmt_execute($stmt);
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "INSERT INTO users (username,password_hash,full_name,level,created_at) VALUES (?,?,?,?,NOW())");
        mysqli_stmt_bind_param($stmt,'sssi',$username,$hash,$full_name,$level);
        mysqli_stmt_execute($stmt);
    }
    header('Location: list.php');
    exit;
}
?>
<h3><?= $id? 'Edit' : 'Tambah' ?> User</h3>
<form method="POST" class="row g-3">
  <div class="col-md-4"><label>Username</label><input class="form-control" name="username" value="<?=htmlspecialchars($data['username'] ?? '')?>" required></div>
  <div class="col-md-4"><label>Nama Lengkap</label><input class="form-control" name="full_name" value="<?=htmlspecialchars($data['full_name'] ?? '')?>"></div>
  <div class="col-md-2"><label>Level</label>
    <select name="level" class="form-select">
      <option value="admin" <?= (isset($data['level']) && $data['level']=='admin')?'selected':'' ?>>admin</option>
      <option value="staff" <?= (isset($data['level']) && $data['level']=='staff')?'selected':'' ?>>staff</option>
    </select>
  </div>
  <div class="col-md-4"><label>Password <?= $id? '(isi jika ingin ganti)': '' ?></label><input class="form-control" type="password" name="password" <?= $id? '':'required' ?>></div>
  <div class="col-12">
    <button class="btn btn-success"><?= $id? 'Update' : 'Simpan' ?></button>
    <a href="list.php" class="btn btn-secondary">Batal</a>
  </div>
</form>
<?php include '../../includes/footer.php'; ?>