<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/order_helpers.php';
require_once __DIR__ . '/../layout/sidebar.php';

$status = trim((string) getParam('status'));
$keyword = trim((string) getParam('q'));
$allowedPaymentStatus = ['pending', 'lunas', 'gagal'];

$sql = "
    SELECT
        payments.*,
        orders.id_user,
        orders.nama AS nama_pemesan,
        orders.email AS email_pemesan,
        orders.total_harga,
        orders.status_order,
        orders.tanggal_order,
        users.nama AS nama_user,
        COALESCE(order_detail.total_jumlah, 0) AS total_jumlah,
        order_detail.daftar_barang
    FROM payments
    INNER JOIN orders ON orders.id_order = payments.id_order
    LEFT JOIN users ON users.id_user = orders.id_user
    LEFT JOIN (
        SELECT
            order_items.id_order,
            SUM(order_items.jumlah) AS total_jumlah,
            GROUP_CONCAT(
                CONCAT(COALESCE(products.nama_produk, 'Produk tidak ditemukan'), ' x ', order_items.jumlah)
                ORDER BY products.nama_produk ASC
                SEPARATOR '||'
            ) AS daftar_barang
        FROM order_items
        LEFT JOIN products ON products.id_product = order_items.id_product
        GROUP BY order_items.id_order
    ) AS order_detail ON order_detail.id_order = orders.id_order
";

$where = [];
$params = [];
$types = '';

if ($status !== '' && in_array($status, $allowedPaymentStatus, true)) {
    $where[] = 'payments.status_pembayaran = ?';
    $params[] = $status;
    $types .= 's';
}

if ($keyword !== '') {
    $where[] = '(orders.nama LIKE ? OR orders.email LIKE ? OR users.nama LIKE ? OR payments.metode_pembayaran LIKE ? OR payments.nomor_pembayaran LIKE ? OR payments.id_order = ?)';
    $like = '%' . $keyword . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = (int) $keyword;
    $types .= 'sssssi';
}

if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}

$sql .= ' ORDER BY payments.id_payment DESC';

$stmt = mysqli_prepare($conn, $sql);
if ($stmt && $params) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

if ($stmt) {
    mysqli_stmt_execute($stmt);
    $data = mysqli_stmt_get_result($stmt);
} else {
    $data = false;
}

$statusClass = [
    'pending' => 'warning',
    'lunas' => 'success',
    'gagal' => 'danger',
];
?>

<div class="admin-layout d-flex" style="min-height: 100vh;">
    <?php renderSidebar('bukti'); ?>

    <main class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold mb-0">Bukti Pembayaran</h3>
                <small class="text-muted">Lihat bukti transfer/e-wallet dari pelanggan dan validasi pembayaran.</small>
            </div>
            <a href="index.php?page=admin-order" class="btn btn-outline-primary rounded-3"><i class="bi bi-bag-check me-1"></i> Manajemen Order</a>
        </div>

        <?php if (getParam('success') === 'verify_payment'): ?>
            <div class="alert alert-success rounded-4">Status pembayaran berhasil diperbarui.</div>
        <?php endif; ?>
        <?php if (getParam('error') === 'stock_not_enough'): ?>
            <div class="alert alert-danger rounded-4">Pembayaran belum bisa divalidasi karena stok produk tidak cukup.</div>
        <?php elseif (getParam('error') === 'invalid'): ?>
            <div class="alert alert-danger rounded-4">Data pembayaran tidak valid atau gagal diproses.</div>
        <?php endif; ?>

        <form method="GET" class="card border-0 shadow-sm rounded-4 p-3 mb-4">
            <input type="hidden" name="page" value="admin-bukti-pembayaran">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Status Pembayaran</label>
                    <select name="status" class="form-select rounded-3">
                        <option value="">Semua Status</option>
                        <?php foreach ($allowedPaymentStatus as $item): ?>
                            <option value="<?= e($item) ?>" <?= $status === $item ? 'selected' : '' ?>><?= e(ucfirst($item)) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-semibold small">Cari</label>
                    <input type="text" name="q" value="<?= e($keyword) ?>" class="form-control rounded-3" placeholder="Cari nama, email, metode, nomor pembayaran, atau ID order">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-3"><i class="bi bi-search me-1"></i> Cari</button>
                    <?php if ($status !== '' || $keyword !== ''): ?>
                        <a href="index.php?page=admin-bukti-pembayaran" class="btn btn-secondary rounded-3">Reset</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>

        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="px-4 py-3">Order</th>
                            <th class="py-3">Pemesan</th>
                            <th class="py-3">Barang</th>
                            <th class="py-3">Pembayaran</th>
                            <th class="py-3">Bukti</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($data && mysqli_num_rows($data) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($data)): ?>
                                <?php
                                $items = !empty($row['daftar_barang']) ? explode('||', (string) $row['daftar_barang']) : [];
                                $proof = (string) ($row['bukti_transfer'] ?? '');
                                $proofUrl = $proof !== '' ? findPaymentProofUrl($proof) : '';
                                $badge = $statusClass[$row['status_pembayaran']] ?? 'secondary';
                                ?>
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="fw-bold">#<?= e($row['id_order']) ?></div>
                                        <div class="text-muted small"><?= !empty($row['tanggal_order']) ? e(date('d M Y H:i', strtotime($row['tanggal_order']))) : '-' ?></div>
                                        <div class="small">Total: <span class="fw-semibold"><?= rupiah($row['total_harga']) ?></span></div>
                                    </td>
                                    <td style="min-width: 180px;">
                                        <div class="fw-semibold"><?= e($row['nama_pemesan'] ?: ($row['nama_user'] ?? 'User tidak ditemukan')) ?></div>
                                        <div class="text-muted small"><?= e($row['email_pemesan'] ?? '-') ?></div>
                                    </td>
                                    <td style="min-width: 220px;">
                                        <?php if ($items): ?>
                                            <ul class="mb-1 ps-3 small">
                                                <?php foreach ($items as $item): ?>
                                                    <li><?= e($item) ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                            <span class="badge text-bg-light border">Total item: <?= e((int) $row['total_jumlah']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted small">Belum ada detail barang</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="min-width: 170px;">
                                        <div class="fw-semibold"><?= e($row['metode_pembayaran']) ?></div>
                                        <div class="text-muted small">No: <?= e($row['nomor_pembayaran'] ?? '-') ?></div>
                                    </td>
                                    <td style="min-width: 140px;">
                                        <?php if ($proof !== ''): ?>
                                            <a href="<?= e($proofUrl) ?>" target="_blank" class="btn btn-outline-primary btn-sm rounded-3 mb-2">
                                                <i class="bi bi-image me-1"></i> Lihat Bukti
                                            </a>
                                            <div class="text-muted small" style="max-width:160px; word-break:break-word;"><?= e($proof) ?></div>
                                        <?php else: ?>
                                            <span class="badge text-bg-light border">Belum upload</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge text-bg-<?= e($badge) ?> rounded-pill px-3 py-2"><?= e(ucfirst($row['status_pembayaran'])) ?></span>
                                        <div class="text-muted small mt-1">Order: <?= e(ucfirst($row['status_order'])) ?></div>
                                    </td>
                                    <td class="text-center" style="min-width: 210px;">
                                        <form method="POST" action="process/verify_payment.php" class="d-flex gap-1 justify-content-center flex-wrap">
                                            <input type="hidden" name="id_order" value="<?= e($row['id_order']) ?>">
                                            <button type="submit" name="action" value="approve" class="btn btn-success btn-sm rounded-3" onclick="return confirm('Validasi pembayaran order #<?= e($row['id_order']) ?>?')">
                                                <i class="bi bi-check2-circle me-1"></i> Valid
                                            </button>
                                            <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm rounded-3" onclick="return confirm('Tolak pembayaran order #<?= e($row['id_order']) ?>?')">
                                                <i class="bi bi-x-circle me-1"></i> Tolak
                                            </button>
                                            <a href="index.php?page=update-order&id=<?= e($row['id_order']) ?>" class="btn btn-info btn-sm rounded-3 text-white">
                                                Detail
                                            </a>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada data pembayaran.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
