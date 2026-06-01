<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/koneksi.php';

requireLogin();

$idOrder = (int) postParam('id_order', 0);
$idUser = (int) ($_SESSION['user']['id_user'] ?? 0);
$isAdminFlag = isAdmin() ? 1 : 0;

if ($idOrder <= 0 || empty($_FILES['bukti']) || $_FILES['bukti']['error'] !== UPLOAD_ERR_OK) {
    redirect('../index.php?page=upload-bukti&id=' . $idOrder . '&error=upload_failed');
}

$stmt = mysqli_prepare($conn, 'SELECT id_order, status_order FROM orders WHERE id_order = ? AND (id_user = ? OR ? = 1) LIMIT 1');
mysqli_stmt_bind_param($stmt, 'iii', $idOrder, $idUser, $isAdminFlag);
mysqli_stmt_execute($stmt);
$order = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$order) {
    redirect('../index.php?page=keranjang&error=invalid');
}

$maxSize = 4 * 1024 * 1024;
if ((int) $_FILES['bukti']['size'] > $maxSize) {
    redirect('../index.php?page=upload-bukti&id=' . $idOrder . '&error=upload_failed');
}

$allowedExt = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
$originalName = (string) $_FILES['bukti']['name'];
$extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

if (!in_array($extension, $allowedExt, true)) {
    redirect('../index.php?page=upload-bukti&id=' . $idOrder . '&error=upload_failed');
}

$targetDir = __DIR__ . '/../uploads/payment_proofs/';
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0775, true);
}

$newName = 'bukti_order_' . $idOrder . '_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
$targetPath = $targetDir . $newName;

if (!move_uploaded_file($_FILES['bukti']['tmp_name'], $targetPath)) {
    redirect('../index.php?page=upload-bukti&id=' . $idOrder . '&error=upload_failed');
}

$stmt = mysqli_prepare($conn, '
    UPDATE payments
    SET bukti_transfer = ?, status_pembayaran = "pending", tanggal_bayar = NOW()
    WHERE id_order = ?
');
mysqli_stmt_bind_param($stmt, 'si', $newName, $idOrder);
mysqli_stmt_execute($stmt);

if (!in_array((string) $order['status_order'], ['dibayar', 'dikirim', 'selesai'], true)) {
    $orderStmt = mysqli_prepare($conn, 'UPDATE orders SET status_order = "pending" WHERE id_order = ?');
    mysqli_stmt_bind_param($orderStmt, 'i', $idOrder);
    mysqli_stmt_execute($orderStmt);
}

redirect('../index.php?page=status-pesanan&id=' . $idOrder . '&success=upload_bukti');
