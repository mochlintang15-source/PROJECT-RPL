<?php
include 'koneksi.php';

$id_order = $_GET['id'];

mysqli_query($conn,"
UPDATE payments
SET status_pembayaran='lunas'
WHERE id_order='$id_order'
");

mysqli_query($conn,"
UPDATE orders
SET status_order='selesai'
WHERE id_order='$id_order'
");

header("Location: success.php?id=".$id_order);