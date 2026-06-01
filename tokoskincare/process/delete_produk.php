<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/koneksi.php';

requireAdmin();

$id = (int) getParam('id', 0);
if ($id <= 0) {
    redirect('../index.php?page=admin-produk&error=invalid');
}

$stmt = mysqli_prepare($conn, 'SELECT gambar FROM products WHERE id_product = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

$stmt = mysqli_prepare($conn, 'DELETE FROM products WHERE id_product = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);

if (!empty($product['gambar']) && $product['gambar'] !== '-') {
    $file = __DIR__ . '/../uploads/products/' . $product['gambar'];
    if (is_file($file)) {
        @unlink($file);
    }
}

redirect('../index.php?page=admin-produk&success=delete_product');
