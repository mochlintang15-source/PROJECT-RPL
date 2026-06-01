<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/koneksi.php';

requireAdmin();

$id = (int) postParam('id', 0);
$nama = trim((string) postParam('nama'));
$email = trim((string) postParam('email'));
$password = (string) postParam('password');
$role = (string) postParam('role', 'user');
$alamat = trim((string) postParam('alamat'));
$noHp = trim((string) postParam('no_hp'));

if (
    $id <= 0 ||
    $nama === '' ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    $alamat === '' ||
    $noHp === '' ||
    !in_array($role, ['admin', 'user'], true)
) {
    redirect('../index.php?page=admin-users&error=invalid');
}

if ($password !== '') {
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($conn, 'UPDATE users SET nama = ?, email = ?, password = ?, role = ?, alamat = ?, no_hp = ?, updated_at = NOW() WHERE id_user = ?');
    mysqli_stmt_bind_param($stmt, 'ssssssi', $nama, $email, $passwordHash, $role, $alamat, $noHp, $id);
} else {
    $stmt = mysqli_prepare($conn, 'UPDATE users SET nama = ?, email = ?, role = ?, alamat = ?, no_hp = ?, updated_at = NOW() WHERE id_user = ?');
    mysqli_stmt_bind_param($stmt, 'sssssi', $nama, $email, $role, $alamat, $noHp, $id);
}
mysqli_stmt_execute($stmt);

if (isset($_SESSION['user']['id_user']) && (int) $_SESSION['user']['id_user'] === $id) {
    $_SESSION['user']['nama'] = $nama;
    $_SESSION['user']['email'] = $email;
    $_SESSION['user']['role'] = $role;
    $_SESSION['user']['alamat'] = $alamat;
    $_SESSION['user']['no_hp'] = $noHp;
}

redirect('../index.php?page=admin-users&success=update_user');
