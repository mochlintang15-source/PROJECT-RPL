<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/koneksi.php';

requireAdmin();

$id = (int) getParam('id', 0);

if ($id <= 0) {
    redirect('../index.php?page=admin-users&error=invalid');
}

if (isset($_SESSION['user']['id_user']) && (int) $_SESSION['user']['id_user'] === $id) {
    redirect('../index.php?page=admin-users&error=invalid');
}

$stmt = mysqli_prepare($conn, 'DELETE FROM users WHERE id_user = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);

redirect('../index.php?page=admin-users&success=delete_user');
