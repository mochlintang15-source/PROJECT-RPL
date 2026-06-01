<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/order_helpers.php';

requireLogin();

$cart = getCartItems();
if (!$cart) {
    redirect('../index.php?page=keranjang&error=empty_cart');
}

$nama = trim((string) postParam('nama'));
$email = trim((string) postParam('email'));
$alamat = trim((string) postParam('alamat'));
$noHp = trim((string) postParam('no_hp'));
$metode = trim((string) postParam('metode_pembayaran'));
$nomorPembayaran = trim((string) postParam('nomor_pembayaran'));
$shipping = explode('|', (string) postParam('shipping'));

if ($nama === '' || $email === '' || $alamat === '' || $noHp === '' || $metode === '' || $nomorPembayaran === '' || count($shipping) !== 3) {
    redirect('../index.php?page=checkout&error=invalid');
}

[$kurir, $layanan, $ongkirRaw] = $shipping;
$ongkir = (int) $ongkirRaw;
$idUser = (int) ($_SESSION['user']['id_user'] ?? 0);

if ($idUser <= 0) {
    redirect('../index.php?page=login&error=login_required');
}

mysqli_begin_transaction($conn);

try {
    $ids = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));
    $stmt = mysqli_prepare($conn, "SELECT id_product, nama_produk, harga, stok FROM products WHERE id_product IN ($placeholders) FOR UPDATE");
    if (!$stmt) {
        throw new RuntimeException('invalid');
    }
    mysqli_stmt_bind_param($stmt, $types, ...$ids);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $products = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $products[(int) $row['id_product']] = $row;
    }

    $subtotal = 0;
    foreach ($cart as $idProduct => $qty) {
        if (!isset($products[$idProduct])) {
            throw new RuntimeException('invalid');
        }
        if ((int) $products[$idProduct]['stok'] < (int) $qty) {
            throw new RuntimeException('stock_not_enough');
        }
        $subtotal += (float) $products[$idProduct]['harga'] * (int) $qty;
    }

    $total = $subtotal + $ongkir;

    $orderStmt = mysqli_prepare($conn, '
        INSERT INTO orders (id_user, nama, email, alamat, total_harga, ongkir, kurir, layanan, status_order, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, "pending", NOW())
    ');
    if (!$orderStmt) {
        throw new RuntimeException('invalid');
    }
    mysqli_stmt_bind_param($orderStmt, 'isssdiss', $idUser, $nama, $email, $alamat, $total, $ongkir, $kurir, $layanan);
    mysqli_stmt_execute($orderStmt);
    $idOrder = (int) mysqli_insert_id($conn);

    $itemStmt = mysqli_prepare($conn, 'INSERT INTO order_items (id_order, id_product, jumlah, harga_satuan) VALUES (?, ?, ?, ?)');
    if (!$itemStmt) {
        throw new RuntimeException('invalid');
    }

    foreach ($cart as $idProduct => $qty) {
        $harga = (float) $products[$idProduct]['harga'];
        $qty = (int) $qty;
        $idProduct = (int) $idProduct;
        mysqli_stmt_bind_param($itemStmt, 'iiid', $idOrder, $idProduct, $qty, $harga);
        mysqli_stmt_execute($itemStmt);
    }

    $paymentStmt = mysqli_prepare($conn, '
        INSERT INTO payments (id_order, metode_pembayaran, status_pembayaran, tanggal_bayar, nomor_pembayaran, bukti_transfer)
        VALUES (?, ?, "pending", NOW(), ?, NULL)
    ');
    if (!$paymentStmt) {
        throw new RuntimeException('invalid');
    }
    mysqli_stmt_bind_param($paymentStmt, 'iss', $idOrder, $metode, $nomorPembayaran);
    mysqli_stmt_execute($paymentStmt);

    mysqli_commit($conn);
    saveCartItems([]);
    redirect('../index.php?page=upload-bukti&id=' . $idOrder . '&success=checkout');
} catch (Throwable $e) {
    mysqli_rollback($conn);
    $error = $e->getMessage() === 'stock_not_enough' ? 'stock_not_enough' : 'invalid';
    redirect('../index.php?page=checkout&error=' . $error);
}
