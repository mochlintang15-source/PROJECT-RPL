<?php
session_start();
include 'koneksi.php';

$count = 0;
if(isset($_SESSION['cart'])){
  foreach($_SESSION['cart'] as $item){
    $count += $item['qty'];
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Produk</title>
</head>
<body>

<h2>Produk Skincare</h2>

<a href="keranjang.php">🛒 Keranjang (<?= $count ?>)</a>

<hr>

<?php
$data = mysqli_query($conn, "SELECT * FROM products");

while($row = mysqli_fetch_assoc($data)) {
?>

<div style="border:1px solid #ccc; padding:10px; margin:10px;">

  <h3><?= $row['nama_produk'] ?></h3>
  <p>Rp<?= number_format($row['harga']) ?></p>
  <p>Stok: <?= $row['stok'] ?></p>

  <?php if($row['stok'] > 0): ?>
    <form action="add_to_cart.php" method="POST">
      <input type="hidden" name="id_product" value="<?= $row['id_product'] ?>">
      <button>Tambah ke Keranjang</button>
    </form>
  <?php else: ?>
    <button disabled>Stok Habis</button>
  <?php endif; ?>

</div>

<?php } ?>

</body>
</html>