<?php
include 'koneksi.php';

$id = $_GET['id'];

// update payment
mysqli_query($conn, "
UPDATE payments 
SET status_pembayaran='lunas'
WHERE id_order=$id
");

// update order
mysqli_query($conn, "
UPDATE orders 
SET status_order='dibayar'
WHERE id_order=$id
");

header("Location: admin.php");
exit;
?>