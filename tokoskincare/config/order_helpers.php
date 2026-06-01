<?php
require_once __DIR__ . '/helpers.php';

function orderAllowedStatuses(): array
{
    return ['pending', 'dibayar', 'dikirim', 'selesai', 'batal'];
}

function orderSoldStatuses(): array
{
    return ['dibayar', 'dikirim', 'selesai'];
}

function paymentStatusFromOrderStatus(string $status): string
{
    if (in_array($status, orderSoldStatuses(), true)) {
        return 'lunas';
    }

    if ($status === 'batal') {
        return 'gagal';
    }

    return 'pending';
}

function updateOrderStatusWithStock(mysqli $conn, int $idOrder, string $newStatus): void
{
    if ($idOrder <= 0 || !in_array($newStatus, orderAllowedStatuses(), true)) {
        throw new InvalidArgumentException('invalid');
    }

    $soldStatuses = orderSoldStatuses();

    $orderStmt = mysqli_prepare($conn, 'SELECT status_order FROM orders WHERE id_order = ? LIMIT 1 FOR UPDATE');
    if (!$orderStmt) {
        throw new RuntimeException('invalid');
    }

    mysqli_stmt_bind_param($orderStmt, 'i', $idOrder);
    mysqli_stmt_execute($orderStmt);
    $orderResult = mysqli_stmt_get_result($orderStmt);
    $order = mysqli_fetch_assoc($orderResult);

    if (!$order) {
        throw new RuntimeException('order_not_found');
    }

    $oldStatus = (string) $order['status_order'];
    $wasSold = in_array($oldStatus, $soldStatuses, true);
    $willBeSold = in_array($newStatus, $soldStatuses, true);

    if (!$wasSold && $willBeSold) {
        $itemsStmt = mysqli_prepare($conn, '
            SELECT oi.id_product, oi.jumlah, p.stok, p.nama_produk
            FROM order_items oi
            INNER JOIN products p ON p.id_product = oi.id_product
            WHERE oi.id_order = ?
            FOR UPDATE
        ');
        if (!$itemsStmt) {
            throw new RuntimeException('invalid');
        }

        mysqli_stmt_bind_param($itemsStmt, 'i', $idOrder);
        mysqli_stmt_execute($itemsStmt);
        $itemsResult = mysqli_stmt_get_result($itemsStmt);

        $items = [];
        while ($item = mysqli_fetch_assoc($itemsResult)) {
            $items[] = $item;
            if ((int) $item['stok'] < (int) $item['jumlah']) {
                throw new RuntimeException('stock_not_enough');
            }
        }

        if (!$items) {
            throw new RuntimeException('invalid');
        }

        foreach ($items as $item) {
            $updateStock = mysqli_prepare($conn, 'UPDATE products SET stok = stok - ?, updated_at = NOW() WHERE id_product = ?');
            if (!$updateStock) {
                throw new RuntimeException('invalid');
            }
            $jumlah = (int) $item['jumlah'];
            $idProduct = (int) $item['id_product'];
            mysqli_stmt_bind_param($updateStock, 'ii', $jumlah, $idProduct);
            mysqli_stmt_execute($updateStock);
        }
    }

    if ($wasSold && !$willBeSold) {
        $itemsStmt = mysqli_prepare($conn, 'SELECT id_product, jumlah FROM order_items WHERE id_order = ?');
        if (!$itemsStmt) {
            throw new RuntimeException('invalid');
        }

        mysqli_stmt_bind_param($itemsStmt, 'i', $idOrder);
        mysqli_stmt_execute($itemsStmt);
        $itemsResult = mysqli_stmt_get_result($itemsStmt);

        while ($item = mysqli_fetch_assoc($itemsResult)) {
            $updateStock = mysqli_prepare($conn, 'UPDATE products SET stok = stok + ?, updated_at = NOW() WHERE id_product = ?');
            if (!$updateStock) {
                throw new RuntimeException('invalid');
            }
            $jumlah = (int) $item['jumlah'];
            $idProduct = (int) $item['id_product'];
            mysqli_stmt_bind_param($updateStock, 'ii', $jumlah, $idProduct);
            mysqli_stmt_execute($updateStock);
        }
    }

    $stmt = mysqli_prepare($conn, 'UPDATE orders SET status_order = ? WHERE id_order = ?');
    if (!$stmt) {
        throw new RuntimeException('invalid');
    }
    mysqli_stmt_bind_param($stmt, 'si', $newStatus, $idOrder);
    mysqli_stmt_execute($stmt);
}

function syncPaymentStatusByOrderStatus(mysqli $conn, int $idOrder, string $orderStatus): void
{
    $paymentStatus = paymentStatusFromOrderStatus($orderStatus);
    $stmt = mysqli_prepare($conn, 'UPDATE payments SET status_pembayaran = ? WHERE id_order = ?');
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'si', $paymentStatus, $idOrder);
        mysqli_stmt_execute($stmt);
    }
}

function findPaymentProofUrl(string $filename): string
{
    $filename = trim($filename);
    if ($filename === '') {
        return '';
    }

    $encodedName = implode('/', array_map('rawurlencode', explode('/', str_replace('\\', '/', $filename))));

    $paths = [
        [__DIR__ . '/../uploads/payment_proofs/' . $filename, 'uploads/payment_proofs/' . $encodedName],
        [__DIR__ . '/../uploads/' . $filename, 'uploads/' . $encodedName],
    ];

    foreach ($paths as [$filePath, $url]) {
        if (is_file($filePath)) {
            return $url;
        }
    }

    // Tetap kembalikan lokasi default supaya file baru dari sistem ini langsung terbaca.
    return 'uploads/payment_proofs/' . $encodedName;
}

function getCartItems(): array
{
    $cart = $_SESSION['cart'] ?? [];
    $items = [];

    foreach ($cart as $key => $value) {
        if (is_array($value)) {
            $id = (int) ($value['id'] ?? $key);
            $qty = (int) ($value['qty'] ?? 0);
        } else {
            $id = (int) $key;
            $qty = (int) $value;
        }

        if ($id > 0 && $qty > 0) {
            $items[$id] = ($items[$id] ?? 0) + $qty;
        }
    }

    return $items;
}

function saveCartItems(array $items): void
{
    $clean = [];
    foreach ($items as $id => $qty) {
        $id = (int) $id;
        $qty = (int) $qty;
        if ($id > 0 && $qty > 0) {
            $clean[$id] = $qty;
        }
    }

    $_SESSION['cart'] = $clean;
}

function currentCartCount(): int
{
    return array_sum(getCartItems());
}
