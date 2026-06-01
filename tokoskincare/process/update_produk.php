<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/koneksi.php';

requireAdmin();

$id = (int) postParam('id_product', 0);
$nama = trim((string) postParam('nama_produk'));
$harga = (float) postParam('harga', 0);
$stok = (int) postParam('stok', 0);
$deskripsi = trim((string) postParam('deskripsi', '-'));
$idKategori = (int) postParam('id_kategori', 0);
$idBrand = (int) postParam('id_brand', 0);
$gambarLama = (string) postParam('gambar_lama', '-');
$gambar = uploadProductImage('gambar', $gambarLama);

$idKategori = $idKategori > 0 ? $idKategori : null;
$idBrand = $idBrand > 0 ? $idBrand : null;

if ($id <= 0 || $nama === '' || $deskripsi === '' || $harga < 0 || $stok < 0) {
    redirect('../index.php?page=admin-produk&error=invalid');
}

$stmt = mysqli_prepare($conn, 'UPDATE products SET nama_produk = ?, deskripsi = ?, harga = ?, stok = ?, id_kategori = ?, id_brand = ?, gambar = ?, updated_at = NOW() WHERE id_product = ?');
mysqli_stmt_bind_param($stmt, 'ssdiiisi', $nama, $deskripsi, $harga, $stok, $idKategori, $idBrand, $gambar, $id);
mysqli_stmt_execute($stmt);

redirect('../index.php?page=admin-produk&success=update_product');
