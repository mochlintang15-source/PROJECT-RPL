<?php
include 'koneksi.php';

if(!isset($_GET['id'])){
    die("ID Pesanan tidak ditemukan");
}

$id_order = intval($_GET['id']);

$query = mysqli_query($conn,"
SELECT *
FROM orders
WHERE id_order='$id_order'
");

$order = mysqli_fetch_assoc($query);

if(!$order){
    die("Pesanan tidak ditemukan");
}

/* =========================
   AMBIL DATA PRODUK
========================= */

$item = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT
p.nama_produk,
p.gambar,
oi.jumlah,
oi.harga_satuan
FROM order_items oi
JOIN products p
ON oi.id_product = p.id_product
WHERE oi.id_order='$id_order'
LIMIT 1
"));

/* =========================
   HITUNG TOTAL
========================= */

$ongkir = isset($order['ongkir'])
          ? $order['ongkir']
          : 0;
$subtotal = $order['total_harga'] - $ongkir;
$total    = $order['total_harga'];

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Status Pesanan</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Segoe UI',sans-serif;
}

body{
background:#f5f7fb;
}

.container{
width:1200px;
margin:30px auto;
}

.card{
display:flex;
gap:20px;
}

.left,
.right{
background:white;
padding:25px;
border-radius:15px;
box-shadow:0 2px 10px rgba(0,0,0,.08);
}

.left{
flex:2;
}

.right{
flex:1;
}

.invoice{
font-size:28px;
font-weight:bold;
}

.date{
color:#666;
margin-top:5px;
}

.badge{
display:inline-block;
padding:8px 18px;
border-radius:20px;
margin-top:15px;
font-weight:600;
}

.pending{
background:#fff3cd;
color:#856404;
}

.shipped{
background:#dbeafe;
color:#1d4ed8;
}

.success{
background:#dcfce7;
color:#166534;
}

.product{
display:flex;
align-items:center;
gap:15px;
margin-top:25px;
}

.product img{
width:90px;
height:90px;
object-fit:cover;
border-radius:10px;
}

.product-name{
font-size:18px;
font-weight:bold;
}

.price{
font-size:24px;
font-weight:bold;
margin-top:5px;
}

.upload-box{
margin-top:30px;
background:#eef4ff;
padding:20px;
border-radius:10px;
border:1px solid #dbeafe;
}

.upload-title{
font-size:18px;
font-weight:bold;
margin-bottom:10px;
}

.upload-desc{
color:#666;
margin-bottom:15px;
}

.btn{
background:#2563eb;
color:white;
padding:12px 20px;
border:none;
border-radius:8px;
cursor:pointer;
font-weight:bold;
text-decoration:none;
display:inline-block;
}

.btn:hover{
background:#1d4ed8;
}

.timeline{
display:flex;
gap:12px;
margin-bottom:25px;
}

.circle-success{
width:22px;
height:22px;
background:#22c55e;
border-radius:50%;
}

.circle-wait{
width:22px;
height:22px;
background:#facc15;
border-radius:50%;
}

.status-title{
font-weight:bold;
font-size:16px;
}

.status-date{
font-size:13px;
color:#777;
}

.summary{
margin-top:30px;
border-top:1px solid #ddd;
padding-top:20px;
}

.row{
display:flex;
justify-content:space-between;
margin-bottom:12px;
}

.total{
font-size:22px;
font-weight:bold;
}

</style>
</head>
<body>

<div class="container">

<div class="card">

<!-- LEFT -->
<div class="left">

<div class="invoice">
#INV-<?= $id_order ?>
</div>

<div class="date">
<?= date('d M Y H:i', strtotime($order['created_at'])) ?>
</div>

<?php
if($order['status_order']=='pending'){
    echo '<div class="badge pending">Menunggu Verifikasi</div>';
}
elseif($order['status_order']=='dikirim'){
    echo '<div class="badge shipped">Pesanan Dikirim</div>';
}
elseif($order['status_order']=='selesai'){
    echo '<div class="badge success">Pesanan Selesai</div>';
}
?>

<div class="product">

<img src="<?= !empty($item['gambar']) ? $item['gambar'] : 'no-image.png' ?>">

<div class="product-name">
<?= $item['nama_produk'] ?>
</div>

<div style="color:#777;">
<?= $item['jumlah'] ?> x Rp <?= number_format($item['harga_satuan']) ?>
</div>

<div class="price">
Rp <?= number_format($subtotal) ?>
</div>

</div>

<div class="upload-box">

<div class="upload-title">
Informasi Pesanan
</div>

<div class="upload-desc">
<b>Kurir :</b> <?= $order['kurir'] ?><br>
<b>Layanan :</b> <?= $order['layanan'] ?><br><br>
<?php

if($order['status_order']=='pending'){
echo "Bukti pembayaran sudah diterima dan sedang diverifikasi admin.";
}

elseif($order['status_order']=='dikirim'){
echo "Pembayaran berhasil diverifikasi dan pesanan sedang dikirim.";
}

elseif($order['status_order']=='selesai'){
echo "Pesanan telah diterima pelanggan.";
}

?>

</div>

<a href="riwayat_pesanan.php" class="btn">
Riwayat Pesanan
</a>

</div>

</div>

<!-- RIGHT -->
<div class="right">

<h2>Status Pesanan</h2>

<br>

<div class="timeline">

<div class="circle-success"></div>

<div>

<div class="status-title">
Pesanan Dibuat
</div>

<div class="status-date">
Pesanan berhasil dibuat
</div>

</div>

</div>

<div class="timeline">

<div class="circle-success"></div>

<div>

<div class="status-title">
Bukti Pembayaran Dikirim
</div>

<div class="status-date">
Bukti transfer diterima sistem
</div>

</div>

</div>

<?php if($order['status_order']=='pending'){ ?>

<div class="timeline">

<div class="circle-wait"></div>

<div>

<div class="status-title">
Menunggu Verifikasi Admin
</div>

<div class="status-date">
Admin sedang memeriksa pembayaran
</div>

</div>

</div>

<?php } ?>

<?php if($order['status_order']=='dikirim'){ ?>

<div class="timeline">

<div class="circle-success"></div>

<div>

<div class="status-title">
Pembayaran Diverifikasi
</div>

</div>

</div>

<div class="timeline">

<div class="circle-success"></div>

<div>

<div class="status-title">
Pesanan Dikirim
</div>

</div>

</div>

<?php } ?>

<?php if($order['status_order']=='selesai'){ ?>

<div class="timeline">

<div class="circle-success"></div>

<div>

<div class="status-title">
Pembayaran Diverifikasi
</div>

</div>

</div>

<div class="timeline">

<div class="circle-success"></div>

<div>

<div class="status-title">
Pesanan Dikirim
</div>

</div>

</div>

<div class="timeline">

<div class="circle-success"></div>

<div>

<div class="status-title">
Pesanan Selesai
</div>

</div>

</div>

<?php } ?>

<div class="summary">

<h3>Ringkasan Pesanan</h3>

<br>

<div class="row">
<span>Subtotal</span>
<span>Rp <?= number_format($subtotal) ?></span>
</div>

<div class="row">
<span>Ongkir</span>
<span>Rp <?= number_format($ongkir) ?></span>
</div>

<hr><br>

<div class="row total">
<span>Total</span>
<span>Rp <?= number_format($total) ?></span>
</div>

</div>

</div>

</div>

</div>

</div>

</body>
</html>