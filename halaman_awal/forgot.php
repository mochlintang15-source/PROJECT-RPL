<?php
include 'config.php';

if (!isset($_POST['email'])) {
    header('Location: beauty-always1.php?msg=Isi+email+untuk+reset+password&from=forgot');
    exit;
}

$email = mysqli_real_escape_string($conn, $_POST['email']);
$newpass = "123456";
$hash = password_hash($newpass, PASSWORD_DEFAULT);

$cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
if(mysqli_num_rows($cek) == 0){
    header('Location: beauty-always1.php?msg=Email+tidak+ditemukan!&from=forgot');
    exit;
}

$sql = "UPDATE users SET password='$hash' WHERE email='$email'";
if(mysqli_query($conn,$sql)){
    header('Location: beauty-always1.php?msg=Password+berhasil+direset+ke+123456&from=forgot');
    exit;
}else{
    header('Location: beauty-always1.php?msg=Gagal+reset+password!&from=forgot');
    exit;
}
?>