<?php
include 'koneksi.php';
session_start();

// ================= VALIDASI DASAR =================
if(empty($_SESSION['cart'])){
    die("Keranjang kosong!");
}

if(!isset($_POST['nama'], $_POST['email'], $_POST['alamat'], $_POST['metode'], $_POST['nomor'])){
    die("Data tidak lengkap!");
}

// ================= AMBIL DATA =================
$nama    = mysqli_real_escape_string($conn, $_POST['nama']);
$email   = mysqli_real_escape_string($conn, $_POST['email']);
$alamat  = mysqli_real_escape_string($conn, $_POST['alamat']);
$metode  = mysqli_real_escape_string($conn, $_POST['metode']);
$nomor   = mysqli_real_escape_string($conn, $_POST['nomor']);

$kurir   = isset($_POST['kurir']) ? mysqli_real_escape_string($conn, $_POST['kurir']) : '';
$layanan = isset($_POST['layanan']) ? mysqli_real_escape_string($conn, $_POST['layanan']) : '';
$ongkir  = isset($_POST['ongkir']) ? (int)$_POST['ongkir'] : 0;

$id_user = 1; // sementara (belum login system)

// ================= HITUNG TOTAL =================
$total = 0;

foreach($_SESSION['cart'] as $item){
    $id  = (int)$item['id'];
    $qty = (int)$item['qty'];

    $q = mysqli_query($conn, "SELECT harga FROM products WHERE id_product=$id");

    if(!$q || mysqli_num_rows($q) == 0){
        die("Produk tidak ditemukan!");
    }

    $d = mysqli_fetch_assoc($q);

    $total += $d['harga'] * $qty;
}

// tambah ongkir
$total += $ongkir;

// tanggal
$tanggal = date('Y-m-d H:i:s');

// ================= INSERT ORDER =================
$insertOrder = mysqli_query($conn, "
INSERT INTO orders 
(id_user, nama, email, alamat, total_harga, ongkir, kurir, layanan, status_order, created_at)
VALUES 
($id_user, '$nama', '$email', '$alamat', $total, $ongkir, '$kurir', '$layanan', 'dibayar', '$tanggal')
");

if(!$insertOrder){
    die("Gagal simpan order: " . mysqli_error($conn));
}

$id_order = mysqli_insert_id($conn);

// ================= INSERT ORDER ITEMS =================
foreach($_SESSION['cart'] as $item){
    $id  = (int)$item['id'];
    $qty = (int)$item['qty'];

    $q = mysqli_query($conn, "SELECT harga FROM products WHERE id_product=$id");
    $d = mysqli_fetch_assoc($q);

    $insertItem = mysqli_query($conn, "
    INSERT INTO order_items (id_order, id_product, jumlah, harga_satuan)
    VALUES ($id_order, $id, $qty, {$d['harga']})
    ");

    if(!$insertItem){
        die("Gagal simpan item!");
    }
}

// ================= INSERT PAYMENT =================
$insertPayment = mysqli_query($conn, "
INSERT INTO payments 
(id_order, metode_pembayaran, nomor_pembayaran, status_pembayaran, tanggal_bayar)
VALUES 
($id_order, '$metode', '$nomor', 'lunas', '$tanggal')
");

if(!$insertPayment){
    die("Gagal simpan pembayaran!");
}

// ================= KOSONGKAN CART =================
unset($_SESSION['cart']);

// ================= REDIRECT =================
if($metode == "Transfer"){
    // debit / bank → OTP
    header("Location: otp.php?id=$id_order");
} else {
    // e-wallet → QR
    header("Location: qr.php?id=$id_order");
}

exit;
?>