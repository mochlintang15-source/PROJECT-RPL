<?php
include 'koneksi.php';
include 'navbar.php';

$id = $_GET['id'];

$data = mysqli_query($conn, "
SELECT o.*, p.metode_pembayaran, p.nomor_pembayaran
FROM orders o
JOIN payments p ON o.id_order = p.id_order
WHERE o.id_order=$id
");

$row = mysqli_fetch_assoc($data);

// format tanggal Indonesia
date_default_timezone_set('Asia/Jakarta');
$tanggal = date('d F Y H:i', strtotime($row['created_at']));
?>

<!DOCTYPE html>
<html>
<head>
<title>Struk</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ================= CONTAINER ================= -->
<div style="
  max-width: 600px;
  margin: 50px auto;
  background: white;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
">

  <!-- SUCCESS -->
  <h2 style="color:green; text-align:center;">✔ Transaksi Berhasil</h2>

  <h1 style="text-align:center;">
    Rp<?= number_format($row['total_harga']) ?>
  </h1>

  <!-- CUSTOMER -->
  <div style="margin-top:20px;">
    <p><b><?= $row['nama'] ?></b></p>
    <p><?= $row['email'] ?></p>
    <p><?= $row['alamat'] ?></p>
  </div>

  <hr>

  <!-- DETAIL -->
  <div>
    <p><b>Tanggal:</b> <?= $tanggal ?></p>
    <p><b>Kurir:</b> <?= $row['kurir'] ?> (<?= $row['layanan'] ?>)</p>
    <p><b>Ongkir:</b> Rp<?= number_format($row['ongkir']) ?></p>
  </div>

  <hr>

  <!-- PAYMENT -->
  <div>
    <p><b>Metode:</b> <?= $row['metode_pembayaran'] ?></p>
    <p><b>Nomor:</b> <?= $row['nomor_pembayaran'] ?></p>
  </div>

  <hr>

  <!-- STATUS -->
  <div style="text-align:center; margin-top:15px;">
    <p style="color:green; font-weight:bold;">STATUS: LUNAS</p>
  </div>

  <!-- BUTTON -->
  <button onclick="window.print()" class="pay-btn">
    Cetak Struk
  </button>

</div>

<?php include 'footer.php'; ?>

</body>
</html>