<?php
session_start();
include 'koneksi.php';

$nama   = $_POST['nama'];
$email  = $_POST['email'];
$alamat = $_POST['alamat'];
$metode = $_POST['metode'];
$telepon = $_POST['telepon'];
$nomor = $_POST['nomor_pembayaran'];

$kurir  = $_POST['kurir'];
$layanan= $_POST['layanan'];
$ongkir = $_POST['ongkir'];

$id_user = 1;

$total = 0;

foreach($_SESSION['cart'] as $item){

    $id = $item['id'];
    $qty = $item['qty'];

    $q = mysqli_query($conn,
    "SELECT harga FROM products WHERE id_product=$id");

    $d = mysqli_fetch_assoc($q);

    $total += $d['harga'] * $qty;
}

$total += $ongkir;

$tanggal = date('Y-m-d H:i:s');

$sql = mysqli_query($conn,"
INSERT INTO orders
(
id_user,
nama,
email,
alamat,
total_harga,
ongkir,
kurir,
layanan,
status_order,
created_at
)
VALUES
(
$id_user,
'$nama',
'$email',
'$alamat',
$total,
$ongkir,
'$kurir',
'$layanan',
'pending',
'$tanggal'
)
");

if(!$sql){
    die(mysqli_error($conn));
}

$id_order = mysqli_insert_id($conn);

foreach($_SESSION['cart'] as $item){

    $id = $item['id'];
    $qty = $item['qty'];

    $q = mysqli_query($conn,
    "SELECT harga FROM products WHERE id_product=$id");

    $d = mysqli_fetch_assoc($q);

    mysqli_query($conn,"
    INSERT INTO order_items
    (id_order,id_product,jumlah,harga_satuan)
    VALUES
    ($id_order,$id,$qty,{$d['harga']})
    ");
}

mysqli_query($conn,"
INSERT INTO payments
(id_order,
metode_pembayaran,
nomor_pembayaran,
status_pembayaran,
tanggal_bayar)
VALUES
(
$id_order,
'$metode',
'$nomor',
'pending',
NOW()
)
");

header("Location: upload_bukti.php?id=".$id_order);
exit;
?>