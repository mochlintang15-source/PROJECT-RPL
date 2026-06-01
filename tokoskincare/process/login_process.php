<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/koneksi.php';

$email = trim((string) postParam('email'));
$password = (string) postParam('password');

if ($email === '' || $password === '') {
    redirect('../index.php?page=login&error=invalid');
}

$stmt = mysqli_prepare($conn, 'SELECT * FROM users WHERE email = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

$passwordInDb = $user['password'] ?? '';
$isHashed = substr($passwordInDb, 0, 4) === '$2y$' || substr($passwordInDb, 0, 7) === '$argon2';
$isValid = false;

if ($user) {
    if ($isHashed) {
        $isValid = password_verify($password, $passwordInDb);
    } else {
        $isValid = hash_equals((string) $passwordInDb, $password);
    }
}

if ($user && $isValid) {
    if (!$isHashed) {
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $update = mysqli_prepare($conn, 'UPDATE users SET password = ? WHERE id_user = ?');
        mysqli_stmt_bind_param($update, 'si', $newHash, $user['id_user']);
        mysqli_stmt_execute($update);
        $user['password'] = $newHash;
    }

    $_SESSION['user'] = $user;

    if ($user['role'] === 'admin') {
        redirect('../index.php?page=admin-dashboard');
    }

    redirect('../index.php?page=katalog');
}

redirect('../index.php?page=login&error=login_failed');
