<?php
require 'config/db.php';
session_start();
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = mysqli_prepare($conn, "SELECT id, username, password_hash, full_name, level FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) {
        if (password_verify($password, $row['password_hash'])) {
            $_SESSION['user'] = ['id'=>$row['id'],'username'=>$row['username'],'full_name'=>$row['full_name']];
            $_SESSION['level'] = $row['level'];
            header('Location: index.php');
            exit;
        } else {
            $err = 'Username atau password salah.';
        }
    } else {
        $err = 'Username atau password salah.';
    }
}
include 'includes/header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <h3>Login</h3>
    <?php if($err): ?>
      <div class="alert alert-danger"><?=$err?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="mb-3">
        <label>Username</label>
        <input class="form-control" name="username" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input class="form-control" type="password" name="password" required>
      </div>
      <button class="btn btn-primary">Login</button>
    </form>
    <hr>
    <p>Jika belum punya akun admin, jalankan <code>create_admin.php</code> sekali lewat browser untuk membuat akun default admin.</p>
  </div>
</div>
<?php include 'includes/footer.php'; ?>