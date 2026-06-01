<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/order_helpers.php';

$idProduct = (int) getParam('id', 0);
$action = (string) getParam('action');
$cart = getCartItems();

if ($action === 'clear') {
    saveCartItems([]);
    redirect('../index.php?page=keranjang&success=cart_updated');
}

if ($idProduct <= 0 || !isset($cart[$idProduct])) {
    redirect('../index.php?page=keranjang&error=invalid');
}

if ($action === 'plus') {
    $stmt = mysqli_prepare($conn, 'SELECT stok FROM products WHERE id_product = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $idProduct);
    mysqli_stmt_execute($stmt);
    $product = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    $maxStock = (int) ($product['stok'] ?? 0);
    if ($cart[$idProduct] < $maxStock) {
        $cart[$idProduct]++;
    }
} elseif ($action === 'minus') {
    $cart[$idProduct]--;
    if ($cart[$idProduct] <= 0) {
        unset($cart[$idProduct]);
    }
} elseif ($action === 'remove') {
    unset($cart[$idProduct]);
} else {
    redirect('../index.php?page=keranjang&error=invalid');
}

saveCartItems($cart);
redirect('../index.php?page=keranjang&success=cart_updated');
