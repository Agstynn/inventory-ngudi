<?php
// Run this once to create default admin account if users table exists but no admin user.
// Access via browser: http://localhost/inventory-php/create_admin.php
require 'config/db.php';

// check users table exists
$exists = mysqli_query($conn, "SELECT COUNT(*) as c FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name='users'");
$r = mysqli_fetch_assoc($exists);
if(!$r || $r['c']==0){
    echo "Tabel users belum ada. Import file SQL (inventory_ngudi_v3.sql) dulu via phpMyAdmin atau run schema.";
    exit;
}

$check = mysqli_query($conn, "SELECT id FROM users WHERE username='admin'");
if(mysqli_num_rows($check)>0){
    echo "User admin sudah ada. Jika ingin reset, hapus user 'admin' dulu atau gunakan halaman Users (admin) untuk ubah.";
    exit;
}

$username = 'admin';
$password_plain = '12345';
$full_name = 'Administrator';

$hash = password_hash($password_plain, PASSWORD_DEFAULT);
$stmt = mysqli_prepare($conn, "INSERT INTO users (username,password_hash,full_name,level,created_at) VALUES (?,?,?,?,NOW())");
$level = 'admin';
mysqli_stmt_bind_param($stmt,'sssi',$username,$hash,$full_name,$level);
mysqli_stmt_execute($stmt);

if(mysqli_affected_rows($conn)>0){
    echo "Admin berhasil dibuat. Username: admin / Password: 12345. Silakan login dan segera ganti password.";
} else {
    echo "Gagal membuat admin. Cek koneksi atau hak akses database.";
}
?>