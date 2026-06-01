PROJECT RPL - TOKO SKINCARE TERINTEGRASI

Folder utama yang dipakai sekarang hanya:
- tokoskincare/

Folder lama seperti Payment, halaman_awal, dan toko-skincare sudah dirapikan/digabungkan ke dalam folder tokoskincare.
Jadi yang perlu dimasukkan ke htdocs cukup folder tokoskincare saja.

DATABASE
- File SQL yang benar hanya satu:
  tokoskincare/database/toko_skincare_kosmetik.sql
- Nama database: toko_skincare_kosmetik

AKUN DEFAULT
1. Admin
   Email    : admin@test.com
   Password : admin123

2. User
   Email    : user@test.com
   Password : 123

CARA MENJALANKAN DI XAMPP
1. Extract ZIP ini.
2. Copy folder tokoskincare ke folder htdocs.
   Contoh: C:/xampp/htdocs/tokoskincare
3. Buka phpMyAdmin.
4. Buat database baru dengan nama: toko_skincare_kosmetik
5. Import file SQL:
   tokoskincare/database/toko_skincare_kosmetik.sql
6. Buka browser:
   http://localhost/tokoskincare/

ALUR FITUR
1. User membuka halaman awal.
2. User membuka katalog.
3. User menambahkan produk ke keranjang.
4. User checkout.
5. User upload bukti pembayaran.
6. Admin login.
7. Admin membuka menu Bukti Pembayaran.
8. Admin melakukan validasi pembayaran: Valid atau Tolak.
9. Status pembayaran dan order ikut berubah.

FILE/FOLDER PENTING
- index.php                       : router utama aplikasi
- user/home.php                   : halaman awal yang sudah digabung
- user/katalog.php                : katalog produk
- user/keranjang.php              : keranjang
- user/checkout.php               : checkout
- user/upload_bukti.php           : upload bukti pembayaran
- user/status_pesanan.php         : status pesanan user
- admin/bukti_pembayaran.php      : halaman admin untuk bukti pembayaran
- process/verify_payment.php      : proses validasi pembayaran
- uploads/payment_proofs/         : tempat file bukti pembayaran
- database/toko_skincare_kosmetik.sql : database yang harus diimport

CATATAN
- Jangan jalankan folder Payment, halaman_awal, atau toko-skincare lama. Semuanya sudah digabung ke tokoskincare.
- Kalau database pernah dibuat sebelumnya, hapus/drop dulu database lama atau import SQL ini ke database baru dengan nama toko_skincare_kosmetik.
