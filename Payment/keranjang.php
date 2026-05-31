<?php
session_start();
include 'koneksi.php';
include 'navbar.php';

if(!isset($_SESSION['cart'])){
  $_SESSION['cart'] = [];
}

// ambil produk dari DB
$products = [];
$q = mysqli_query($conn, "SELECT * FROM products");

while($r = mysqli_fetch_assoc($q)){
  $products[$r['id_product']] = $r;
}

// ACTION (+ - hapus)
if(isset($_GET['action']) && isset($_GET['id'])){
  $id = intval($_GET['id']);

  foreach($_SESSION['cart'] as $k => $item){
    if($item['id'] == $id){

      if($_GET['action']=="plus"){
        $_SESSION['cart'][$k]['qty']++;
      }

      if($_GET['action']=="minus"){
        $_SESSION['cart'][$k]['qty']--;
        if($_SESSION['cart'][$k]['qty'] <= 0){
          unset($_SESSION['cart'][$k]);
        }
      }

      if($_GET['action']=="remove"){
        unset($_SESSION['cart'][$k]);
      }
    }
  }

  $_SESSION['cart'] = array_values($_SESSION['cart']);
  header("Location: keranjang.php");
  exit;
}

$total = 0;
$count = 0;
?>

<!DOCTYPE html>
<html>
<head>
  <title>Keranjang</title>
  <link rel="stylesheet" href="keranjang.css">
</head>
<body>

<h2 class="title">Keranjang Belanja</h2>

<div class="checkout-container">

<!-- ================= LEFT ================= -->
<div class="left">

<?php if(empty($_SESSION['cart'])): ?>
  <p>Keranjang kosong</p>
<?php else: ?>

<?php foreach($_SESSION['cart'] as $item): 
  if(!isset($products[$item['id']])) continue;

  $p = $products[$item['id']];
  $subtotal = $p['harga'] * $item['qty'];
  $total += $subtotal;
  $count += $item['qty'];
?>

<div style="display:flex; gap:15px; margin-bottom:15px; border-bottom:1px solid #eee; padding-bottom:10px;">

  <!-- GAMBAR -->
  <img src="<?= $p['gambar'] ?>" width="80" style="border-radius:8px;">

  <!-- DETAIL -->
  <div style="flex:1;">
    <h4><?= $p['nama_produk'] ?></h4>
    <p class="price">Rp<?= number_format($p['harga']) ?></p>

    <!-- QTY -->
    <div style="display:flex; align-items:center; gap:10px;">
      <a href="?action=minus&id=<?= $item['id'] ?>" class="btn">Kurang</a>
      <span><?= $item['qty'] ?></span>
      <a href="?action=plus&id=<?= $item['id'] ?>" class="btn">Tambah</a>
    </div>

    <p>Subtotal: <b>Rp<?= number_format($subtotal) ?></b></p>

    <a href="?action=remove&id=<?= $item['id'] ?>" style="color:red;">Hapus</a>
  </div>

</div>

<?php endforeach; ?>

<?php endif; ?>

</div>

<!-- ================= RIGHT ================= -->
<div class="right">

<h3>Ringkasan</h3>

<div style="display:flex; justify-content:space-between;">
  <span>Total Item</span>
  <span><?= $count ?></span>
</div>

<div style="display:flex; justify-content:space-between;">
  <span>Total Harga</span>
  <span>Rp<?= number_format($total) ?></span>
</div>

<hr>

<?php if(!empty($_SESSION['cart'])): ?>
  <a href="checkout.php">
    <button class="pay-btn">Checkout</button>
  </a>
<?php else: ?>
  <button class="btn-disabled">Keranjang Kosong</button>
<?php endif; ?>

</div>

</div>

</body>
<?php
include 'footer.php';
?>

</html>

