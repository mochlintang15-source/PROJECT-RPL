// code ini untuk memproses login pengguna. Pertama, session dimulai untuk menyimpan informasi login pengguna. Kemudian, 
// file config.php di-include untuk mendapatkan koneksi ke database.

<?php
session_start();
include 'config.php';

if (!isset($_POST['email'], $_POST['password'])) {
    header('Location: beauty-always1.php?msg=Isi+email+dan+password&from=login');
    exit;
}

$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0){
    $user = mysqli_fetch_assoc($result);
    if(password_verify($password, $user['password'])){
        $_SESSION['user'] = $user['email'];
        $_SESSION['role']  = $user['role'];
        // redirect ke homepage
        header("Location: ./index.php");
        exit;
    } else {
        header('Location: beauty-always1.php?msg=Password+salah!&from=login');
        exit;
    }
} else {
    header('Location: beauty-always1.php?msg=Email+tidak+ditemukan!&from=login');
    exit;
}
?>