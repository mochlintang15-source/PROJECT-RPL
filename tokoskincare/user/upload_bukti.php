<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/order_helpers.php';
require_once __DIR__ . '/../layout/store_navbar.php';

$idOrder = (int) getParam('id', 0);
$idUser = (int) ($_SESSION['user']['id_user'] ?? 0);

$stmt = mysqli_prepare($conn, '
    SELECT orders.*, payments.metode_pembayaran, payments.nomor_pembayaran, payments.status_pembayaran, payments.bukti_transfer
    FROM orders
    LEFT JOIN payments ON payments.id_order = orders.id_order
    WHERE orders.id_order = ? AND (orders.id_user = ? OR ? = 1)
    LIMIT 1
');
$isAdminFlag = isAdmin() ? 1 : 0;
mysqli_stmt_bind_param($stmt, 'iii', $idOrder, $idUser, $isAdminFlag);
mysqli_stmt_execute($stmt);
$order = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$itemStmt = mysqli_prepare($conn, '
    SELECT oi.*, p.nama_produk, p.gambar
    FROM order_items oi
    LEFT JOIN products p ON p.id_product = oi.id_product
    WHERE oi.id_order = ?
');
mysqli_stmt_bind_param($itemStmt, 'i', $idOrder);
mysqli_stmt_execute($itemStmt);
$items = mysqli_stmt_get_result($itemStmt);

renderStoreNavbar('keranjang');
?>

<div class="container py-5">
    <?php if (!$order): ?>
        <div class="alert alert-danger rounded-4 text-center">Pesanan tidak ditemukan atau bukan milik akun ini.</div>
    <?php else: ?>
        <?php
        $proof = (string) ($order['bukti_transfer'] ?? '');
        $proofUrl = $proof !== '' ? findPaymentProofUrl($proof) : '';
        $ongkir = (int) ($order['ongkir'] ?? 0);
        $subtotal = (float) $order['total_harga'] - $ongkir;
        ?>
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h2 class="fw-bold mb-0">Upload Bukti Pembayaran</h2>
                <p class="text-muted mb-0">Invoice #INV-<?= e($order['id_order']) ?></p>
            </div>
            <a href="index.php?page=status-pesanan&id=<?= e($order['id_order']) ?>" class="btn btn-outline-primary rounded-3">Lihat Status</a>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                        <div>
                            <div class="text-muted small">Total Pembayaran</div>
                            <div class="display-6 fw-bold text-primary"><?= rupiah($order['total_harga']) ?></div>
                        </div>
                        <span class="badge text-bg-warning rounded-pill px-3 py-2"><?= e(ucfirst($order['status_pembayaran'] ?? 'pending')) ?></span>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="text-muted small">Metode Pembayaran</div>
                            <div class="fw-semibold"><?= e($order['metode_pembayaran'] ?? '-') ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Nomor Pembayaran</div>
                            <div class="fw-semibold"><?= e($order['nomor_pembayaran'] ?? '-') ?></div>
                        </div>
                    </div>

                    <?php if ($proof !== ''): ?>
                        <div class="alert alert-info rounded-4">
                            Bukti pembayaran sebelumnya sudah tersimpan: <a href="<?= e($proofUrl) ?>" target="_blank" class="alert-link">lihat bukti</a>. Kamu tetap bisa upload ulang jika salah file.
                        </div>
                    <?php endif; ?>

                    <form action="process/upload_bukti.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_order" value="<?= e($order['id_order']) ?>">
                        <label class="form-label fw-semibold">File Bukti Pembayaran</label>
                        <input type="file" name="bukti" class="form-control rounded-3 mb-2" accept=".jpg,.jpeg,.png,.webp,.pdf" required>
                        <div class="form-text mb-3">Format: JPG, PNG, WEBP, atau PDF. Maksimal 4 MB.</div>
                        <button type="submit" class="btn btn-primary rounded-3"><i class="bi bi-upload me-1"></i> Upload Bukti</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-3">Ringkasan Pesanan</h5>
                    <div class="d-flex flex-column gap-3 mb-3">
                        <?php while ($item = mysqli_fetch_assoc($items)): ?>
                            <div class="d-flex justify-content-between gap-3">
                                <div>
                                    <div class="fw-semibold"><?= e($item['nama_produk'] ?? 'Produk tidak ditemukan') ?></div>
                                    <div class="text-muted small"><?= e($item['jumlah']) ?> x <?= rupiah($item['harga_satuan']) ?></div>
                                </div>
                                <div class="fw-semibold"><?= rupiah((float) $item['jumlah'] * (float) $item['harga_satuan']) ?></div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Subtotal</span><span><?= rupiah($subtotal) ?></span></div>
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Ongkir</span><span><?= rupiah($ongkir) ?></span></div>
                    <div class="d-flex justify-content-between fs-5 fw-bold"><span>Total</span><span class="text-primary"><?= rupiah($order['total_harga']) ?></span></div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
