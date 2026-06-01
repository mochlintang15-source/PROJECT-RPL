<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/order_helpers.php';

requireAdmin();

$id = (int) postParam('id_order', 0);
$status = (string) postParam('status_order');

if ($id <= 0 || !in_array($status, orderAllowedStatuses(), true)) {
    redirect('../index.php?page=admin-order&error=invalid');
}

mysqli_begin_transaction($conn);

try {
    updateOrderStatusWithStock($conn, $id, $status);
    syncPaymentStatusByOrderStatus($conn, $id, $status);

    mysqli_commit($conn);
    redirect('../index.php?page=admin-order&success=update_order');
} catch (Throwable $e) {
    mysqli_rollback($conn);
    $error = $e->getMessage() === 'stock_not_enough' ? 'stock_not_enough' : 'invalid';
    redirect('../index.php?page=admin-order&error=' . $error);
}
