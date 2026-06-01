<?php
include 'koneksi.php';
$success = isset($_GET['success']);
$id_order = $_GET['id'];

$order = mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT *
FROM orders
WHERE id_order='$id_order'
"));
?>

<!DOCTYPE html>
<html>
<head>
<title>Upload Bukti Pembayaran</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Segoe UI;
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

.left{
flex:2;
background:white;
padding:25px;
border-radius:12px;
box-shadow:0 2px 10px rgba(0,0,0,.08);
}

.right{
flex:1;
background:white;
padding:25px;
border-radius:12px;
box-shadow:0 2px 10px rgba(0,0,0,.08);
}

.invoice{
font-size:28px;
font-weight:bold;
}

.date{
color:#777;
margin-top:5px;
}

.wait{
display:inline-block;
margin-top:15px;
padding:8px 18px;
background:#fff3cd;
color:#856404;
border-radius:20px;
font-weight:600;
}

.product{
margin-top:25px;
display:flex;
gap:15px;
align-items:center;
}

.product img{
width:90px;
height:90px;
object-fit:cover;
border-radius:10px;
}

.product-name{
font-weight:600;
font-size:18px;
}

.price{
font-size:24px;
font-weight:bold;
margin-top:8px;
}

.info{
margin-top:25px;
display:flex;
justify-content:space-between;
}

.info-title{
color:#777;
font-size:14px;
}

.info-value{
font-weight:600;
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
font-weight:bold;
font-size:18px;
margin-bottom:10px;
}

.upload-desc{
color:#666;
margin-bottom:15px;
}

input[type=file]{
padding:10px;
}

.btn{
background:#2563eb;
color:white;
border:none;
padding:12px 25px;
border-radius:8px;
cursor:pointer;
font-weight:bold;
}

.btn:hover{
background:#1d4ed8;
}

.status-title{
font-size:22px;
font-weight:bold;
margin-bottom:25px;
}

.timeline{
margin-bottom:25px;
display:flex;
gap:10px;
}

.circle-success{
width:24px;
height:24px;
background:#22c55e;
border-radius:50%;
}

.circle-wait{
width:24px;
height:24px;
background:#facc15;
border-radius:50%;
}

.status-text{
font-weight:600;
}

.status-date{
font-size:13px;
color:#888;
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

<!-- KIRI -->
<div class="left">

<div class="invoice">
#INV-<?= $id_order ?>
</div>

<div class="date">
<?= date('d M Y H:i') ?>
</div>

<div class="wait">
Menunggu Pembayaran
</div>

<div class="product">

<img src="https://img.freepik.com/premium-psd/serum-bottle-transparent-background_1085577-83676.jpg">

<div>

<div class="product-name">
Skincare Serum
</div>

<div class="price">
Rp <?= number_format($order['total_harga']) ?>
</div>

</div>

</div>

<div class="info">

<div>
<div class="info-title">
Metode Pembayaran
</div>

<div class="info-value">
Transfer Bank
</div>
</div>

<div>
<div class="info-title">
Batas Pembayaran
</div>

<div class="info-value">
1 Juni 2026 22:54
</div>
</div>

</div>

<div class="upload-box">

<div class="upload-title">
Upload Bukti Pembayaran
</div>

<div class="upload-desc">
Upload foto bukti transfer agar pesanan dapat diverifikasi lebih cepat.
</div>

<form
action="proses_upload_bukti.php"
method="POST"
enctype="multipart/form-data">

<input
type="hidden"
name="id_order"
value="<?= $id_order ?>">

<input
type="file"
name="bukti"
required>

<br><br>

<button class="btn">
Upload Bukti
</button>

</form>

</div>

</div>

<!-- KANAN -->
<div class="right">

<div class="status-title">
Status Pesanan
</div>

<div class="timeline">

<div class="circle-success"></div>

<div>
<div class="status-text">
Pesanan Dibuat
</div>

<div class="status-date">
<?= date('d M Y H:i') ?>
</div>
</div>

</div>

<div class="timeline">

<div class="circle-wait"></div>

<div>
<div class="status-text">
Menunggu Verifikasi
</div>

<div class="status-date">
Admin memeriksa bukti transfer
</div>
</div>

</div>

<div class="summary">

<h3>Ringkasan Pesanan</h3>

<br>

<div class="row">
<span>Subtotal</span>
<span>
Rp <?= number_format($order['total_harga']) ?>
</span>
</div>

<div class="row">
<span>Ongkir</span>
<span>Rp 10.000</span>
</div>

<hr><br>

<div class="row total">
<span>Total</span>
<span>
Rp <?= number_format($order['total_harga']) ?>
</span>
</div>

</div>

</div>

</div>

</div>

</body>
</html>