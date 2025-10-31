<?php
require '../../config/db.php';
if (session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user'])) { header('Location: /inventory-php/login.php'); exit; }
include '../../includes/header.php';
$alat_res = mysqli_query($conn, "SELECT id, code AS kode, name AS nama, model AS no_seri, location AS ruangan FROM equipments ORDER BY name");
$id = isset($_GET['id'])?(int)$_GET['id']:0;
$data = null;
if($id){
    $stmt = mysqli_prepare($conn, "SELECT * FROM pemeliharaan_alat WHERE id_pemeliharaan=?");
    mysqli_stmt_bind_param($stmt,'i',$id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($res);
}
if($_SERVER['REQUEST_METHOD']=='POST'){
    $tanggal = $_POST['tanggal']; $alat_id = $_POST['alat_id']; $nama_alat = $_POST['nama_alat'];
    $no_seri = $_POST['no_seri']; $ruangan = $_POST['ruangan']; $jenis = $_POST['jenis']; $keterangan = $_POST['keterangan'];
    if($id){
        $stmt = mysqli_prepare($conn, "UPDATE pemeliharaan_alat SET tanggal=?, alat_id=?, nama_alat=?, no_seri=?, ruangan=?, jenis_pemeliharaan=?, keterangan=? WHERE id_pemeliharaan=?");
        mysqli_stmt_bind_param($stmt,'sisssssi',$tanggal,$alat_id,$nama_alat,$no_seri,$ruangan,$jenis,$keterangan,$id);
        mysqli_stmt_execute($stmt);
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO pemeliharaan_alat (tanggal,alat_id,nama_alat,no_seri,ruangan,jenis_pemeliharaan,keterangan) VALUES (?,?,?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt,'sisssss',$tanggal,$alat_id,$nama_alat,$no_seri,$ruangan,$jenis,$keterangan);
        mysqli_stmt_execute($stmt);
    }
    header('Location: list.php');
    exit;
}
?>
<h3><?= $id? 'Edit' : 'Tambah' ?> Pemeliharaan</h3>
<form method="POST" class="row g-3">
  <div class="col-md-3"><label>Tanggal</label><input type="date" name="tanggal" class="form-control" value="<?=htmlspecialchars($data['tanggal'] ?? '')?>" required></div>
  <div class="col-md-4">
    <label>Nama Alat (pilih)</label>
    <select name="alat_id" id="alat_id" class="form-select" onchange="fillSerial()" required>
      <option value="">-- pilih alat --</option>
      <?php while($a = mysqli_fetch_assoc($alat_res)): ?>
        <option value="<?=$a['id']?>" data-nama="<?=htmlspecialchars($a['nama'])?>" data-seri="<?=htmlspecialchars($a['no_seri'])?>" <?= isset($data['alat_id']) && $data['alat_id']==$a['id']?'selected':'' ?>>
          <?=$a['kode']?> - <?=$a['nama']?>
        </option>
      <?php endwhile; ?>
    </select>
  </div>
  <div class="col-md-3"><label>No Seri</label><input type="text" id="no_seri" name="no_seri" class="form-control" value="<?=htmlspecialchars($data['no_seri'] ?? '')?>"></div>
  <div class="col-md-2"><label>Ruangan</label><input type="text" name="ruangan" class="form-control" value="<?=htmlspecialchars($data['ruangan'] ?? '')?>"></div>
  <div class="col-md-3"><label>Jenis Pemeliharaan</label><input type="text" name="jenis" class="form-control" value="<?=htmlspecialchars($data['jenis_pemeliharaan'] ?? '')?>"></div>
  <div class="col-md-9"><label>Keterangan</label><textarea name="keterangan" class="form-control"><?=htmlspecialchars($data['keterangan'] ?? '')?></textarea></div>
  <div class="col-12">
    <button class="btn btn-success"><?= $id? 'Update' : 'Simpan' ?></button>
    <a href="list.php" class="btn btn-secondary">Batal</a>
  </div>
</form>
<script>
function fillSerial(){
  const sel = document.getElementById('alat_id');
  const idx = sel.selectedIndex;
  if(idx>0){
    const opt = sel.options[idx];
    document.getElementById('no_seri').value = opt.getAttribute('data-seri') || '';
  }
}
</script>
<?php include '../../includes/footer.php'; ?>