<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/order_helpers.php';
require_once __DIR__ . '/../layout/store_navbar.php';

$idOrder = (int) getParam('id', 0);
$idUser = (int) ($_SESSION['user']['id_user'] ?? 0);
$isAdminFlag = isAdmin() ? 1 : 0;

$stmt = mysqli_prepare($conn, '
    SELECT orders.*, payments.metode_pembayaran, payments.nomor_pembayaran, payments.status_pembayaran, payments.bukti_transfer, payments.tanggal_bayar
    FROM orders
    LEFT JOIN payments ON payments.id_order = orders.id_order
    WHERE orders.id_order = ? AND (orders.id_user = ? OR ? = 1)
    LIMIT 1
');
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

$statusClass = [
    'pending' => 'warning',
    'dibayar' => 'primary',
    'dikirim' => 'info',
    'selesai' => 'success',
    'batal' => 'danger',
];

renderStoreNavbar('keranjang');
?>

<div class="container py-5">
    <?php if (!$order): ?>
        <div class="alert alert-danger rounded-4 text-center">Pesanan tidak ditemukan atau bukan milik akun ini.</div>
    <?php else: ?>
        <?php
        $orderStatus = (string) $order['status_order'];
        $paymentStatus = (string) ($order['status_pembayaran'] ?? 'pending');
        $proof = (string) ($order['bukti_transfer'] ?? '');
        $proofUrl = $proof !== '' ? findPaymentProofUrl($proof) : '';
        $ongkir = (int) ($order['ongkir'] ?? 0);
        $subtotal = (float) $order['total_harga'] - $ongkir;
        ?>
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h2 class="fw-bold mb-0">Status Pesanan</h2>
                <p class="text-muted mb-0">Invoice #INV-<?= e($order['id_order']) ?></p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <?php if ($paymentStatus !== 'lunas' && $orderStatus !== 'batal'): ?>
                    <a href="index.php?page=upload-bukti&id=<?= e($order['id_order']) ?>" class="btn btn-primary rounded-3"><i class="bi bi-upload me-1"></i> Upload Bukti</a>
                <?php endif; ?>
                <a href="index.php?page=katalog" class="btn btn-outline-secondary rounded-3">Belanja Lagi</a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                        <div>
                            <div class="text-muted small">Tanggal Pesanan</div>
                            <div class="fw-semibold"><?= !empty($order['tanggal_order']) ? e(date('d M Y H:i', strtotime($order['tanggal_order']))) : '-' ?></div>
                        </div>
                        <span class="badge text-bg-<?= e($statusClass[$orderStatus] ?? 'secondary') ?> rounded-pill px-3 py-2"><?= e(ucfirst($orderStatus)) ?></span>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted small">Nama Pemesan</div>
                            <div class="fw-semibold"><?= e($order['nama'] ?? '-') ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Email</div>
                            <div class="fw-semibold"><?= e($order['email'] ?? '-') ?></div>
                        </div>
                        <div class="col-12">
                            <div class="text-muted small">Alamat</div>
                            <div class="fw-semibold"><?= e($order['alamat'] ?? '-') ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Pengiriman</div>
                            <div class="fw-semibold"><?= e(($order['kurir'] ?? '-') . ' ' . ($order['layanan'] ?? '')) ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Pembayaran</div>
                            <div class="fw-semibold"><?= e($order['metode_pembayaran'] ?? '-') ?> · <?= e(ucfirst($paymentStatus)) ?></div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-4">Timeline</h5>
                    <?php
                    $steps = [
                        ['Pesanan Dibuat', true, 'Pesanan berhasil dibuat oleh pelanggan.'],
                        ['Bukti Pembayaran Diupload', $proof !== '', $proof !== '' ? 'Bukti pembayaran sudah masuk ke sistem.' : 'Menunggu pelanggan upload bukti.'],
                        ['Pembayaran Diverifikasi', in_array($orderStatus, ['dibayar', 'dikirim', 'selesai'], true), 'Admin memvalidasi bukti pembayaran.'],
                        ['Pesanan Dikirim', in_array($orderStatus, ['dikirim', 'selesai'], true), 'Pesanan dalam proses pengiriman.'],
                        ['Pesanan Selesai', $orderStatus === 'selesai', 'Pesanan selesai.'],
                    ];
                    if ($orderStatus === 'batal') {
                        $steps[] = ['Pesanan Dibatalkan', true, 'Pesanan dibatalkan atau pembayaran ditolak.'];
                    }
                    ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($steps as [$title, $done, $desc]): ?>
                            <div class="d-flex gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width:30px;height:30px;background:<?= $done ? '#22c55e' : '#facc15' ?>; flex:0 0 30px;">
                                    <?= $done ? '✓' : '…' ?>
                                </div>
                                <div>
                                    <div class="fw-semibold"><?= e($title) ?></div>
                                    <div class="text-muted small"><?= e($desc) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-3">Ringkasan Produk</h5>
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

                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-3">Bukti Pembayaran</h5>
                    <?php if ($proof !== ''): ?>
                        <a href="<?= e($proofUrl) ?>" target="_blank" class="btn btn-outline-primary rounded-3 mb-2"><i class="bi bi-image me-1"></i> Lihat Bukti</a>
                        <div class="text-muted small" style="word-break: break-word;"><?= e($proof) ?></div>
                    <?php else: ?>
                        <div class="alert alert-warning rounded-4 mb-0">Belum ada bukti pembayaran.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
