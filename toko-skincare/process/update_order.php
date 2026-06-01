<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/koneksi.php';

requireAdmin();

$id = (int) postParam('id_order', 0);
$status = (string) postParam('status_order');
$allowedStatus = ['pending', 'dibayar', 'dikirim', 'selesai', 'batal'];
$soldStatuses = ['dibayar', 'dikirim', 'selesai'];

if ($id <= 0 || !in_array($status, $allowedStatus, true)) {
    redirect('../index.php?page=admin-order&error=invalid');
}

mysqli_begin_transaction($conn);

try {
    $orderStmt = mysqli_prepare($conn, 'SELECT status_order FROM orders WHERE id_order = ? LIMIT 1 FOR UPDATE');
    mysqli_stmt_bind_param($orderStmt, 'i', $id);
    mysqli_stmt_execute($orderStmt);
    $orderResult = mysqli_stmt_get_result($orderStmt);
    $order = mysqli_fetch_assoc($orderResult);

    if (!$order) {
        throw new Exception('order_not_found');
    }

    $oldStatus = $order['status_order'];
    $wasSold = in_array($oldStatus, $soldStatuses, true);
    $willBeSold = in_array($status, $soldStatuses, true);

    // Stok dikurangi hanya 1 kali, yaitu saat status berubah dari belum laku
    // menjadi laku. Di sistem ini status laku dihitung dari: dibayar/dikirim/selesai.
    if (!$wasSold && $willBeSold) {
        $itemsStmt = mysqli_prepare($conn, '
            SELECT oi.id_product, oi.jumlah, p.stok, p.nama_produk
            FROM order_items oi
            INNER JOIN products p ON p.id_product = oi.id_product
            WHERE oi.id_order = ?
            FOR UPDATE
        ');
        mysqli_stmt_bind_param($itemsStmt, 'i', $id);
        mysqli_stmt_execute($itemsStmt);
        $itemsResult = mysqli_stmt_get_result($itemsStmt);

        $items = [];
        while ($item = mysqli_fetch_assoc($itemsResult)) {
            $items[] = $item;
            if ((int) $item['stok'] < (int) $item['jumlah']) {
                throw new Exception('stock_not_enough');
            }
        }

        foreach ($items as $item) {
            $updateStock = mysqli_prepare($conn, 'UPDATE products SET stok = stok - ?, updated_at = NOW() WHERE id_product = ?');
            $jumlah = (int) $item['jumlah'];
            $idProduct = (int) $item['id_product'];
            mysqli_stmt_bind_param($updateStock, 'ii', $jumlah, $idProduct);
            mysqli_stmt_execute($updateStock);
        }
    }

    // Jika order sudah pernah dianggap laku, lalu dibatalkan, stok dikembalikan.
    if ($wasSold && $status === 'batal') {
        $itemsStmt = mysqli_prepare($conn, 'SELECT id_product, jumlah FROM order_items WHERE id_order = ?');
        mysqli_stmt_bind_param($itemsStmt, 'i', $id);
        mysqli_stmt_execute($itemsStmt);
        $itemsResult = mysqli_stmt_get_result($itemsStmt);

        while ($item = mysqli_fetch_assoc($itemsResult)) {
            $updateStock = mysqli_prepare($conn, 'UPDATE products SET stok = stok + ?, updated_at = NOW() WHERE id_product = ?');
            $jumlah = (int) $item['jumlah'];
            $idProduct = (int) $item['id_product'];
            mysqli_stmt_bind_param($updateStock, 'ii', $jumlah, $idProduct);
            mysqli_stmt_execute($updateStock);
        }
    }

    $stmt = mysqli_prepare($conn, 'UPDATE orders SET status_order = ? WHERE id_order = ?');
    mysqli_stmt_bind_param($stmt, 'si', $status, $id);
    mysqli_stmt_execute($stmt);

    mysqli_commit($conn);
    redirect('../index.php?page=admin-order&success=update_order');
} catch (Throwable $e) {
    mysqli_rollback($conn);
    $error = $e->getMessage() === 'stock_not_enough' ? 'stock_not_enough' : 'invalid';
    redirect('../index.php?page=admin-order&error=' . $error);
}
