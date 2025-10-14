<?php
require '../../config/db.php';
if (session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['level']) || $_SESSION['level']!='admin') { header('Location: /inventory-php/login.php'); exit; }
$id = (int)($_GET['id'] ?? 0);
if($id){
    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id=?");
    mysqli_stmt_bind_param($stmt,"i",$id);
    mysqli_stmt_execute($stmt);
}
header('Location: list.php');
exit;
?>