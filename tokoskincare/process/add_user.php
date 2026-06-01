<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/koneksi.php';

requireAdmin();

$nama = trim((string) postParam('nama'));
$email = trim((string) postParam('email'));
$password = (string) postParam('password');
$role = (string) postParam('role', 'user');
$alamat = trim((string) postParam('alamat'));
$noHp = trim((string) postParam('no_hp'));

if (
    $nama === '' ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    $password === '' ||
    $alamat === '' ||
    $noHp === '' ||
    !in_array($role, ['admin', 'user'], true)
) {
    redirect('../index.php?page=admin-users&error=invalid');
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$stmt = mysqli_prepare($conn, 'INSERT INTO users (nama, email, password, role, alamat, no_hp) VALUES (?, ?, ?, ?, ?, ?)');
mysqli_stmt_bind_param($stmt, 'ssssss', $nama, $email, $passwordHash, $role, $alamat, $noHp);
mysqli_stmt_execute($stmt);

redirect('../index.php?page=admin-users&success=add_user');
