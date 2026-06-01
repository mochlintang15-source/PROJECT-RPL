<?php
session_start();
include 'config.php';

if (!isset($_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['phone'], $_POST['password'], $_POST['confirm_password'])) {
    header('Location: beauty-always1.php?msg=Lengkapi+semua+formulir&from=register');
    exit;
}

$first  = mysqli_real_escape_string($conn, $_POST['first_name']);
$last   = mysqli_real_escape_string($conn, $_POST['last_name']);
$email  = mysqli_real_escape_string($conn, $_POST['email']);
$phone  = mysqli_real_escape_string($conn, $_POST['phone']);
$pass   = $_POST['password'];
$confirm= $_POST['confirm_password'];

// gabung nama
$nama = $first . " " . $last;

// validasi password
if($pass !== $confirm){
    header('Location: beauty-always1.php?msg=Password+tidak+sama!&from=register');
    exit;
}

// cek email sudah ada atau belum
$cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
if(mysqli_num_rows($cek) > 0){
    header('Location: beauty-always1.php?msg=Email+sudah+terdaftar!&from=register');
    exit;
}

// HASH PASSWORD (PENTING)
$password_hash = password_hash($pass, PASSWORD_DEFAULT);

// insert ke DB (sesuai struktur kamu)
$sql = "INSERT INTO users 
(nama, email, password, role, alamat, no_hp, created_at) 
VALUES 
('$nama', '$email', '$password_hash', 'user', 'Belum diisi', '$phone', NOW())";

if(mysqli_query($conn, $sql)){
    $_SESSION['user'] = $email;
    $_SESSION['role'] = 'user';
    header("Location: ../homepage/index_homepage.php");
    exit;
} else {
    header('Location: beauty-always1.php?msg=Registrasi+gagal!&from=register');
    exit;
}
?>