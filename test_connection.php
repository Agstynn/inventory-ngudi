<?php
// Konfigurasi database
$host = "localhost";    // biasanya localhost di XAMPP
$username = "root";     // default XAMPP user
$password = "";         // default XAMPP password kosong
$database = "inventory_ngudi"; // ganti dengan nama database kamu

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

echo "Koneksi berhasil ke database '$database'!";

// Menutup koneksi
$conn->close();
?>
