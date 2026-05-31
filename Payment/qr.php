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

// DATA UNTUK QR
$qrText = "Order ID: ".$row['id_order'].
          "\nNama: ".$row['nama'].
          "\nTotal: Rp".number_format($row['total_harga']).
          "\nMetode: ".$row['metode_pembayaran'];
?>

<!DOCTYPE html>
<html>
<head>
  <title>QR Pembayaran</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="checkout-container">

<div class="left">
<h2>Scan QR untuk Pembayaran</h2>

<p><?= $row['nama'] ?></p>
<p>Total: Rp<?= number_format($row['total_harga']) ?></p>
<p>Metode: <?= $row['metode_pembayaran'] ?></p>

<br>
<center>
<!-- QR CODE (PAKAI API GRATIS) -->
<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode($qrText) ?>">
<p id="timer"></p>

<script>
let time = 300; // 5 menit

setInterval(() => {
  let m = Math.floor(time / 60);
  let s = time % 60;

  document.getElementById("timer").innerText =
    "Batas waktu: " + m + ":" + (s < 10 ? "0"+s : s);

  time--;

  if(time < 0){
    alert("Waktu habis!");
    window.location.href = "batal.php?id=<?= $row['id_order'] ?>";
  }

}, 1000);
</script>

<br><br>
</center>
<p>Silakan scan QR ini untuk melakukan pembayaran</p>

<br>

<a href="success.php?id=<?= $row['id_order'] ?>">
  <button class="pay-btn">Saya Sudah Bayar</button>
</a>

</div>

<div class="right">
<h3>Detail Transaksi</h3>

<p>Nama: <?= $row['nama'] ?></p>
<p>Email: <?= $row['email'] ?></p>
<p>Alamat: <?= $row['alamat'] ?></p>

<hr>

<p>Metode: <?= $row['metode_pembayaran'] ?></p>
<p>Nomor: <?= $row['nomor_pembayaran'] ?></p>

<hr>

<h2>Rp<?= number_format($row['total_harga']) ?></h2>

</div>

</div>
<?php
include 'footer.php';
?>
</body>
</html>