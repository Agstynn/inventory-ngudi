<?php
require 'config/db.php';
if (session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
// Redirect to equipments list by default
header('Location: pages/equipments/list.php');
exit;
?>