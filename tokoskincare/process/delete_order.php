<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/koneksi.php';

requireAdmin();

$id = (int) getParam('id', 0);
$soldStatuses = ['dibayar', 'dikirim', 'selesai'];

if ($id <= 0) {
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

    // Jika order sudah dianggap laku lalu dihapus, stok dikembalikan agar data stok tidak minus/keliru.
    if (in_array($order['status_order'], $soldStatuses, true)) {
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

    $deleteStmt = mysqli_prepare($conn, 'DELETE FROM orders WHERE id_order = ?');
    mysqli_stmt_bind_param($deleteStmt, 'i', $id);
    mysqli_stmt_execute($deleteStmt);

    // Jika semua order sudah kosong, reset auto increment untuk kebutuhan testing/demo.
    // Jika masih ada data order, auto increment tidak dipaksa reset agar relasi foreign key tetap aman.
    $countResult = mysqli_query($conn, 'SELECT COUNT(*) AS total FROM orders');
    $countRow = mysqli_fetch_assoc($countResult);
    if ((int) ($countRow['total'] ?? 0) === 0) {
        mysqli_query($conn, 'ALTER TABLE order_items AUTO_INCREMENT = 1');
        mysqli_query($conn, 'ALTER TABLE orders AUTO_INCREMENT = 1');
    }

    mysqli_commit($conn);
    redirect('../index.php?page=admin-order&success=delete_order');
} catch (Throwable $e) {
    mysqli_rollback($conn);
    redirect('../index.php?page=admin-order&error=invalid');
}
