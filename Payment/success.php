<?php
include 'koneksi.php';

$id_order = $_GET['id'];

$data = mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT *
FROM orders
WHERE id_order='$id_order'
"));
?>

<!DOCTYPE html>
<html>
<head>
<title>Pembayaran Berhasil</title>
</head>
<body>

<center>

<h1>✅ Pembayaran Berhasil</h1>

<h2>INV-<?= $id_order ?></h2>

<p>
Total :
Rp<?= number_format($data['total_harga']) ?>
</p>

<a href="index.php">
Kembali Belanja
</a>

</center>

</body>
</html>