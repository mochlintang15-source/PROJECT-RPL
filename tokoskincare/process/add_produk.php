<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/koneksi.php';

requireAdmin();

$nama = trim((string) postParam('nama_produk'));
$harga = (float) postParam('harga', 0);
$stok = (int) postParam('stok', 0);
$deskripsi = trim((string) postParam('deskripsi', '-'));
$idKategori = (int) postParam('id_kategori', 0);
$idBrand = (int) postParam('id_brand', 0);
$gambar = uploadProductImage('gambar');

$idKategori = $idKategori > 0 ? $idKategori : null;
$idBrand = $idBrand > 0 ? $idBrand : null;

if ($nama === '' || $deskripsi === '' || $harga < 0 || $stok < 0) {
    redirect('../index.php?page=tambah-produk&error=invalid');
}

$stmt = mysqli_prepare($conn, 'INSERT INTO products (nama_produk, deskripsi, harga, stok, id_kategori, id_brand, gambar) VALUES (?, ?, ?, ?, ?, ?, ?)');
mysqli_stmt_bind_param($stmt, 'ssdiiis', $nama, $deskripsi, $harga, $stok, $idKategori, $idBrand, $gambar);
mysqli_stmt_execute($stmt);

redirect('../index.php?page=admin-produk&success=add_product');
