<?php
require '../../config/db.php';
if (session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user'])) { header('Location: /inventory-php/login.php'); exit; }
$id = (int)($_GET['id'] ?? 0);
if($id){
    $stmt = mysqli_prepare($conn, "DELETE FROM pemeliharaan_alat WHERE id_pemeliharaan=?");
    mysqli_stmt_bind_param($stmt,"i",$id);
    mysqli_stmt_execute($stmt);
}
header('Location: list.php');
exit;
?>