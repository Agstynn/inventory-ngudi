<?php
require '../../config/db.php';
if (session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user'])) { header('Location: /inventory-php/login.php'); exit; }

include '../../includes/header.php';

// Ambil daftar alat dari tabel equipments
$alat_res = mysqli_query($conn, "SELECT id, code, name, location FROM equipments ORDER BY code ASC");

// Jika edit data
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = null;
if ($id) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM perbaikan_alat WHERE id_perbaikan=?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($res);
}

// === HANDLE SIMPAN / UPDATE ===
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
    $alat_id = (int)($_POST['alat_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    $keterangan = $_POST['keterangan'] ?? '';

    if (!$alat_id) {
        echo "<script>alert('Pilih alat terlebih dahulu!'); history.back();</script>";
        exit;
    }

    // Ambil data alat dari tabel equipments (wajib ada)
    $q = mysqli_query($conn, "SELECT id, code, name, location FROM equipments WHERE id = $alat_id LIMIT 1");
    if (!$q || mysqli_num_rows($q) == 0) {
        echo "<script>alert('Error: Data alat tidak ditemukan di tabel equipments.'); history.back();</script>";
        exit;
    }

    $alat = mysqli_fetch_assoc($q);
    $nama_alat = $alat['name'];
    $no_seri = $alat['code']; // gunakan code sebagai pengganti nomor seri
    $ruangan = $alat['location'];

    if ($id) {
        $stmt = mysqli_prepare($conn, "UPDATE perbaikan_alat 
            SET tanggal=?, alat_id=?, nama_alat=?, no_seri=?, ruangan=?, status=?, keterangan=? 
            WHERE id_perbaikan=?");
        mysqli_stmt_bind_param($stmt, 'sisssssi', 
            $tanggal, $alat_id, $nama_alat, $no_seri, $ruangan, $status, $keterangan, $id
        );
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO perbaikan_alat 
            (tanggal, alat_id, nama_alat, no_seri, ruangan, status, keterangan)
            VALUES (?,?,?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt, 'sisssss', 
            $tanggal, $alat_id, $nama_alat, $no_seri, $ruangan, $status, $keterangan
        );
    }

    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_error($stmt)) {
        echo "<pre>Gagal menyimpan: " . htmlspecialchars(mysqli_stmt_error($stmt)) . "</pre>";
        exit;
    }

    header('Location: list.php');
    exit;
}
?>

<h3><?= $id ? 'Edit' : 'Tambah' ?> Perbaikan</h3>

<form method="POST" class="row g-3">
  <div class="col-md-3">
    <label>Tanggal</label>
    <input type="date" name="tanggal" class="form-control" 
           value="<?=htmlspecialchars($data['tanggal'] ?? '')?>" required>
  </div>

  <div class="col-md-5">
    <label>Nama Alat (pilih)</label>
    <select name="alat_id" id="alat_id" class="form-select" onchange="fillInfo()" required>
      <option value="">-- pilih alat --</option>
      <?php while ($a = mysqli_fetch_assoc($alat_res)): ?>
      <option value="<?= $a['id'] ?>"
              data-code="<?= htmlspecialchars($a['code']) ?>"
              data-location="<?= htmlspecialchars($a['location']) ?>"
              <?= isset($data['alat_id']) && $data['alat_id'] == $a['id'] ? 'selected' : '' ?>>
        <?= $a['code'] ?> - <?= $a['name'] ?> (<?= $a['location'] ?>)
      </option>
      <?php endwhile; ?>
    </select>
  </div>

  <div class="col-md-2">
    <label>No Seri</label>
    <input type="text" id="no_seri" name="no_seri" class="form-control" 
           value="<?=htmlspecialchars($data['no_seri'] ?? '')?>" readonly>
  </div>

  <div class="col-md-2">
    <label>Ruangan</label>
    <input type="text" id="ruangan" name="ruangan" class="form-control" 
           value="<?=htmlspecialchars($data['ruangan'] ?? '')?>" readonly>
  </div>

  <div class="col-md-3">
    <label>Status</label>
    <select name="status" class="form-select">
      <option value="Dalam Proses" <?= (isset($data['status']) && $data['status']=='Dalam Proses')?'selected':'' ?>>Dalam Proses</option>
      <option value="Selesai" <?= (isset($data['status']) && $data['status']=='Selesai')?'selected':'' ?>>Selesai</option>
      <option value="Dibatalkan" <?= (isset($data['status']) && $data['status']=='Dibatalkan')?'selected':'' ?>>Dibatalkan</option>
    </select>
  </div>

  <div class="col-md-9">
    <label>Keterangan</label>
    <textarea name="keterangan" class="form-control"><?=htmlspecialchars($data['keterangan'] ?? '')?></textarea>
  </div>

  <div class="col-12">
    <button class="btn btn-success"><?= $id ? 'Update' : 'Simpan' ?></button>
    <a href="list.php" class="btn btn-secondary">Batal</a>
  </div>
</form>

<script>
function fillInfo() {
  const sel = document.getElementById('alat_id');
  const opt = sel.options[sel.selectedIndex];
  document.getElementById('no_seri').value = opt.getAttribute('data-code') || '';
  document.getElementById('ruangan').value = opt.getAttribute('data-location') || '';
}
</script>

<?php include '../../includes/footer.php'; ?>
