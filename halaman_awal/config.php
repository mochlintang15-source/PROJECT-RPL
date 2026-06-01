menentukan koneksi ke database menggunakan mysqli_connect dengan parameter host, user, password, 
dan dbname yang telah didefinisikan sebelumnya.
Jika koneksi gagal, maka akan menampilkan pesan error menggunakan die() dan mysqli_connect_error().



<?php
$host     = "localhost";
$user     = "root";
$password = "";
$dbname   = "apa";

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>