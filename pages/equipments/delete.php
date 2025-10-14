<?php
require '../../config/db.php';
if (session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user'])) { header('Location: /inventory-php/login.php'); exit; }

$id = (int)($_GET['id'] ?? 0);
if($id){
    // get current image
    $stmt = mysqli_prepare($conn, "SELECT gambar FROM equipments WHERE id=?");
    mysqli_stmt_bind_param($stmt,'i',$id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    if($row && !empty($row['gambar'])){
        $path = __DIR__ . '/../../uploads/' . $row['gambar'];
        if(file_exists($path)) @unlink($path);
    }

    $stmt = mysqli_prepare($conn, "DELETE FROM equipments WHERE id=?");
    mysqli_stmt_bind_param($stmt,"i",$id);
    mysqli_stmt_execute($stmt);
}
header('Location: list.php');
exit;
?>