Panduan singkat instalasi (XAMPP):
1. Ekstrak folder inventory-php ke dalam htdocs (mis: C:/xampp/htdocs/inventory-php).
2. Jalankan XAMPP, aktifkan Apache dan MySQL.
3. Import SQL: buka http://localhost/phpmyadmin -> Import -> pilih file inventory-php/sql/inventory_ngudi.sql
   (atau jalankan file SQL via command line).
4. Setelah import, buka browser: http://localhost/inventory-php/create_admin.php
   - Script ini akan membuat akun admin default: username 'admin' dan password 'admin123'.
   - Setelah dibuat, segera login dan ganti password.
5. Buka: http://localhost/inventory-php/login.php untuk login.
6. Jika ada masalah koneksi DB, edit config/db.php dan sesuaikan username/password.

Catatan:
- Export Excel menghasilkan file .xls sederhana yang bisa dibuka dengan Excel.
- Export CSV menghasilkan file .csv.
- Untuk mencetak/menyimpan PDF, gunakan tombol 'Cetak / Simpan PDF' di halaman Cetak.
- Ganti password default setelah login.
