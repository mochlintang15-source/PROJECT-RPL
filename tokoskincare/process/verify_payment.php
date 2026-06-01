<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/order_helpers.php';

requireAdmin();

$idOrder = (int) postParam('id_order', 0);
$action = (string) postParam('action');

$actionToOrderStatus = [
    'approve' => 'dibayar',
    'reject' => 'batal',
    'pending' => 'pending',
];

if ($idOrder <= 0 || !isset($actionToOrderStatus[$action])) {
    redirect('../index.php?page=admin-bukti-pembayaran&error=invalid');
}

$newOrderStatus = $actionToOrderStatus[$action];
$newPaymentStatus = paymentStatusFromOrderStatus($newOrderStatus);

mysqli_begin_transaction($conn);

try {
    updateOrderStatusWithStock($conn, $idOrder, $newOrderStatus);

    $stmt = mysqli_prepare($conn, 'UPDATE payments SET status_pembayaran = ? WHERE id_order = ?');
    if (!$stmt) {
        throw new RuntimeException('invalid');
    }
    mysqli_stmt_bind_param($stmt, 'si', $newPaymentStatus, $idOrder);
    mysqli_stmt_execute($stmt);

    mysqli_commit($conn);
    redirect('../index.php?page=admin-bukti-pembayaran&success=verify_payment');
} catch (Throwable $e) {
    mysqli_rollback($conn);
    $error = $e->getMessage() === 'stock_not_enough' ? 'stock_not_enough' : 'invalid';
    redirect('../index.php?page=admin-bukti-pembayaran&error=' . $error);
}
