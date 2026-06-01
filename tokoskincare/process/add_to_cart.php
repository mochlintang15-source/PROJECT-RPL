<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/order_helpers.php';

$idProduct = (int) postParam('id_product', 0);
$qty = max(1, (int) postParam('qty', 1));

if ($idProduct <= 0) {
    redirect('../index.php?page=katalog&error=invalid');
}

$stmt = mysqli_prepare($conn, 'SELECT stok FROM products WHERE id_product = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $idProduct);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

if (!$product || (int) $product['stok'] <= 0) {
    redirect('../index.php?page=katalog&error=stock_not_enough');
}

$cart = getCartItems();
$currentQty = $cart[$idProduct] ?? 0;
$newQty = min((int) $product['stok'], $currentQty + $qty);
$cart[$idProduct] = $newQty;
saveCartItems($cart);

redirect('../index.php?page=keranjang&success=add_cart');
