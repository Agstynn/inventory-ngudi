<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Inventory Alkes - UNIVERSITAS NGUDI WALUYO</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
  <div class="container d-flex justify-content-between">
    <a class="navbar-brand" href="/inventory-php/index.php">🏥 Inventory Alkes - Universitas Ngudi Waluyo</a>
    <div>
      <a href="/inventory-php/pages/equipments/list.php" class="btn btn-sm btn-outline-light me-1">Alat</a>
      <a href="/inventory-php/pages/pemeliharaan/list.php" class="btn btn-sm btn-outline-light me-1">Pemeliharaan</a>
      <a href="/inventory-php/pages/perbaikan/list.php" class="btn btn-sm btn-outline-light me-1">Perbaikan</a>
      <?php if (session_status() == PHP_SESSION_NONE) session_start(); ?>
      <?php if(isset($_SESSION['user']) && isset($_SESSION['level']) && $_SESSION['level']=='admin'): ?>
        <a href="/inventory-php/pages/users/list.php" class="btn btn-sm btn-outline-light me-1">Manage Users</a>
      <?php endif; ?>
      <?php if(isset($_SESSION['user'])): ?>
        <span class="text-white me-2">Halo, <?=htmlspecialchars($_SESSION['user']['full_name'])?></span>
        <a href="/inventory-php/logout.php" class="btn btn-sm btn-outline-light">Logout</a>
      <?php else: ?>
        <a href="/inventory-php/login.php" class="btn btn-sm btn-outline-light">Login</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
<div class="container mt-4">