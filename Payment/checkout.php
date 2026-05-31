<?php
session_start();
include 'koneksi.php';


if(empty($_SESSION['cart'])){
  die("Keranjang kosong");
}

$products = [];
$q = mysqli_query($conn, "SELECT * FROM products");

while($r = mysqli_fetch_assoc($q)){
  $products[$r['id_product']] = $r;
}

$total = 0;

// ===== TANGGAL INDONESIA =====
date_default_timezone_set('Asia/Jakarta');

$hari = date('l');
$hariIndo = [
  'Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa',
  'Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'
];

$tanggal = $hariIndo[$hari] . ", " . date('d F Y H:i');
?>

<!DOCTYPE html>
<html>
<head>
  <title>Checkout</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="checkout-container">

<!-- ================= LEFT ================= -->
<div class="left">
<h2>Checkout</h2>

<!-- TANGGAL -->
<p style="color:gray; font-size:14px;">
  <?= $tanggal ?>
</p>

<form action="proses.php" method="POST">

<input type="hidden" name="tanggal" value="<?= $tanggal ?>">

<label>Nama</label>
<input type="text" name="nama" required>

<label>Email</label>
<input type="email" name="email" required>

<label>Alamat</label>
<input type="text" name="alamat" required>

<label>Nomor Handpone</label>
<input type="text" name="nomor" placeholder="08xxxx" required>

<!-- ================= PAYMENT ================= -->
<h3>Metode Pembayaran</h3>

<div class="payment-grid">

  <div class="pay-card" onclick="pilihMetode(this,'Gopay')">
    <img src="https://tse3.mm.bing.net/th/id/OIP.mx8WUihUUFpA4WHPqVj13AHaHa?pid=Api">
    <p>Gopay</p>
  </div>

  <div class="pay-card" onclick="pilihMetode(this,'OVO')">
    <img src="https://tse3.mm.bing.net/th/id/OIP.sUSLOaPi8PkZwcSYNqLhmAHaEl?pid=Api">
    <p>OVO</p>
  </div>

  <div class="pay-card" onclick="pilihMetode(this,'Dana')">
    <img src="https://tse2.mm.bing.net/th/id/OIP.gSs_zjnw7N7vzRDqshvTbAHaI1?pid=Api">
    <p>DANA</p>
  </div>

  <div class="pay-card" onclick="pilihMetode(this,'Transfer')">
    <img src="https://tse2.mm.bing.net/th/id/OIP.vv09DZE2PCnnl-u6iV1I2wHaEl?pid=Api">
    <p>Transfer</p>
  </div>

</div>

<input type="hidden" name="metode" id="metode">
<div id="inputArea"></div>

<!-- ================= SHIPPING ================= -->
<h3>Jasa Pengiriman</h3>

<div class="payment-grid">

  <div class="pay-card" onclick="pilihPengiriman(this,'JNE','REG',10000)">
    <p>JNE REG</p>
    <small>3-5 Hari</small>
    <b>Rp10.000</b>
  </div>

  <div class="pay-card" onclick="pilihPengiriman(this,'JNE','YES',20000)">
    <p>JNE YES</p>
    <small>1 Hari</small>
    <b>Rp20.000</b>
  </div>

  <div class="pay-card" onclick="pilihPengiriman(this,'J&T','REG',12000)">
    <p>J&T REG</p>
    <small>3-5 Hari</small>
    <b>Rp12.000</b>
  </div>

  <div class="pay-card" onclick="pilihPengiriman(this,'J&T','YES',22000)">
    <p>J&T YES</p>
    <small>1 Hari</small>
    <b>Rp22.000</b>
  </div>

  <div class="pay-card" onclick="pilihPengiriman(this,'SiCepat','REG',11000)">
    <p>SiCepat REG</p>
    <small>3-5 Hari</small>
    <b>Rp11.000</b>
  </div>

  <div class="pay-card" onclick="pilihPengiriman(this,'SiCepat','BEST',21000)">
    <p>SiCepat BEST</p>
    <small>1 Hari</small>
    <b>Rp21.000</b>
  </div>

</div>

<input type="hidden" name="kurir" id="kurir">
<input type="hidden" name="layanan" id="layanan">
<input type="hidden" name="ongkir" id="ongkir">

<br><br>
<button class="pay-btn" onclick="return validasi()">Bayar Sekarang</button>

</form>
</div>

<!-- ================= RIGHT ================= -->
<div class="right">
<h3>Ringkasan</h3>

<?php foreach($_SESSION['cart'] as $item): 
  if(!isset($products[$item['id']])) continue;

  $p = $products[$item['id']];
  $subtotal = $p['harga'] * $item['qty'];
  $total += $subtotal;
?>

<div class="item" style="display:flex; gap:10px; align-items:center; margin-bottom:10px;">

  <!-- GAMBAR -->
  <img src="https://img.freepik.com/premium-psd/serum-bottle-transparent-background_1085577-83676.jpg" 
       style="width:60px; height:60px; object-fit:cover; border-radius:8px;">

  <!-- INFO -->
  <div style="flex:1;">
    <p style="margin:0;"><?= $p['nama_produk'] ?></p>
    <small><?= $item['qty'] ?>x</small>
  </div>

  <!-- HARGA -->
  <span>Rp<?= number_format($subtotal) ?></span>

</div>

<?php endforeach; ?>

<hr>

<div class="summary-row">
  <span>Subtotal</span>
  <span>Rp<?= number_format($total) ?></span>
</div>

<div class="summary-row">
  <span>Ongkir</span>
  <span id="ongkirText">Rp0</span>
</div>

<hr>

<h2 id="totalText">Total: Rp<?= number_format($total) ?></h2>

</div>

</div>

<!-- ================= SCRIPT ================= -->
<script>
let subtotal = <?= $total ?>;

// ================= PAYMENT =================
function pilihMetode(el, metode){

  // HANYA reset payment
  document.querySelectorAll('.payment-grid')[0]
    .querySelectorAll('.pay-card')
    .forEach(card => card.classList.remove('selected'));

  el.classList.add('selected');
  document.getElementById("metode").value = metode;

  let html = "";

  // E-WALLET
  if(metode === "Gopay" || metode === "OVO" || metode === "Dana"){
    html = `
      <label>Nomor HP (${metode})</label>
      <input type="text" name="nomor" placeholder="08xxxx" required>
      <small>Pembayaran via QR</small>
    `;
  }

  // BANK / DEBIT
  if(metode === "Transfer"){
    html = `
      <label>Nomor Rekening / Kartu</label>
      <input type="text" name="nomor" required>

      <label>Nama pemilik Rekening</label>
      <input type="text" name="nama_rek" required>

    `;
  }

  document.getElementById("inputArea").innerHTML = html;
}

// ================= SHIPPING =================
function pilihPengiriman(el, kurir, layanan, ongkir){

  // HANYA reset shipping
  document.querySelectorAll('.payment-grid')[1]
    .querySelectorAll('.pay-card')
    .forEach(card => card.classList.remove('selected'));

  el.classList.add('selected');

  document.getElementById("kurir").value = kurir;
  document.getElementById("layanan").value = layanan;
  document.getElementById("ongkir").value = ongkir;

  document.getElementById("ongkirText").innerText = "Rp" + ongkir.toLocaleString();

  let total = subtotal + ongkir;
  document.getElementById("totalText").innerText = "Total: Rp" + total.toLocaleString();
}

// ================= VALIDASI =================
function validasi(){
  let metode = document.getElementById("metode").value;
  let nomor = document.querySelector("input[name='nomor']");
  let kurir = document.getElementById("kurir").value;

  if(metode === ""){
    alert("Pilih metode pembayaran!");
    return false;
  }

  if(!nomor || nomor.value.trim() === ""){
    alert("Masukkan nomor pembayaran!");
    return false;
  }

  if(kurir === ""){
    alert("Pilih jasa pengiriman!");
    return false;
  }

  return true;
}
</script>

<?php include 'footer.php'; ?>

</body>
</html>