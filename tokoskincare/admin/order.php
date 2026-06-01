<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../layout/sidebar.php';
require_once __DIR__ . '/../config/order_helpers.php';

$status = trim((string) getParam('status'));
$allowedStatus = ['pending', 'dibayar', 'dikirim', 'selesai', 'batal'];

$sql = "
    SELECT
        orders.*,
        orders.nama AS nama_pemesan,
        users.nama AS nama_user,
        payments.status_pembayaran,
        payments.bukti_transfer,
        payments.metode_pembayaran,
        COALESCE(order_detail.total_jumlah, 0) AS total_jumlah,
        order_detail.daftar_barang
    FROM orders
    LEFT JOIN users ON users.id_user = orders.id_user
    LEFT JOIN payments ON payments.id_order = orders.id_order
    LEFT JOIN (
        SELECT
            order_items.id_order,
            SUM(order_items.jumlah) AS total_jumlah,
            GROUP_CONCAT(
                CONCAT(
                    COALESCE(products.nama_produk, 'Produk tidak ditemukan'),
                    ' x ',
                    order_items.jumlah
                )
                ORDER BY products.nama_produk ASC
                SEPARATOR '||'
            ) AS daftar_barang
        FROM order_items
        LEFT JOIN products ON products.id_product = order_items.id_product
        GROUP BY order_items.id_order
    ) AS order_detail ON order_detail.id_order = orders.id_order
";
$params = [];
$types = '';
if ($status !== '' && in_array($status, $allowedStatus, true)) {
    $sql .= ' WHERE orders.status_order = ?';
    $params[] = $status;
    $types .= 's';
}
$sql .= ' ORDER BY orders.id_order DESC';

$stmt = mysqli_prepare($conn, $sql);
if ($stmt && $params) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
if ($stmt) {
    mysqli_stmt_execute($stmt);
    $data = mysqli_stmt_get_result($stmt);
} else {
    $data = mysqli_query($conn, '
        SELECT
            orders.*,
            orders.nama AS nama_pemesan,
            users.nama AS nama_user,
            NULL AS status_pembayaran,
            NULL AS bukti_transfer,
            NULL AS metode_pembayaran,
            0 AS total_jumlah,
            NULL AS daftar_barang
        FROM orders
        LEFT JOIN users ON users.id_user = orders.id_user
        LEFT JOIN payments ON payments.id_order = orders.id_order
        ORDER BY orders.id_order DESC
    ');
}

$statusClass = [
    'pending' => 'secondary',
    'dibayar' => 'primary',
    'dikirim' => 'warning',
    'selesai' => 'success',
    'batal' => 'danger',
];
?>

<div class="admin-layout d-flex" style="min-height: 100vh;">
    <?php renderSidebar('order'); ?>

    <main class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold mb-0">Manajemen Order</h3>
                <small class="text-muted">Pantau nama barang, jumlah pesanan, dan ubah status pesanan</small>
            </div>
        </div>

        <?php if (getParam('success') === 'update_order'): ?>
            <div class="alert alert-success rounded-4">Status order berhasil diperbarui.</div>
        <?php endif; ?>

        <?php if (getParam('error') === 'stock_not_enough'): ?>
            <div class="alert alert-danger rounded-4">Stok produk tidak cukup, status order belum bisa diubah.</div>
        <?php elseif (getParam('error') === 'invalid'): ?>
            <div class="alert alert-danger rounded-4">Terjadi kesalahan saat memproses order.</div>
        <?php endif; ?>

        <form method="GET" class="mb-4">
            <input type="hidden" name="page" value="admin-order">
            <div class="d-flex gap-2 flex-wrap">
                <select name="status" class="form-select rounded-3" style="max-width: 220px;">
                    <option value="">Semua Status</option>
                    <?php foreach ($allowedStatus as $item): ?>
                        <option value="<?= e($item) ?>" <?= $status === $item ? 'selected' : '' ?>><?= e(ucfirst($item)) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary rounded-3"><i class="bi bi-funnel-fill me-1"></i> Filter</button>
                <?php if ($status !== ''): ?>
                    <a href="index.php?page=admin-order" class="btn btn-secondary rounded-3">Reset</a>
                <?php endif; ?>
            </div>
        </form>

        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="py-3">User</th>
                            <th class="py-3">Nama Barang</th>
                            <th class="py-3 text-center">Jumlah Barang</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3">Total</th>
                            <th class="py-3">Pembayaran</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($data && mysqli_num_rows($data) > 0): ?>
                            <?php $no = 1; ?>
                            <?php while ($o = mysqli_fetch_assoc($data)): ?>
                                <?php
                                    $badge = $statusClass[$o['status_order']] ?? 'secondary';
                                    $items = !empty($o['daftar_barang']) ? explode('||', (string) $o['daftar_barang']) : [];
                                ?>
                                <tr>
                                    <td class="px-4 py-3 fw-semibold"><?= e($no++) ?></td>
                                    <td><?= e($o['nama_pemesan'] ?: ($o['nama_user'] ?? 'User tidak ditemukan')) ?></td>
                                    <td style="min-width: 220px;">
                                        <?php if ($items): ?>
                                            <ul class="mb-0 ps-3 small">
                                                <?php foreach ($items as $item): ?>
                                                    <li><?= e($item) ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else: ?>
                                            <span class="text-muted small">Belum ada barang pada order ini</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center fw-semibold"><?= e((int) $o['total_jumlah']) ?></td>
                                    <td><?= !empty($o['tanggal_order']) ? e(date('d M Y H:i', strtotime($o['tanggal_order']))) : '-' ?></td>
                                    <td><?= rupiah($o['total_harga']) ?></td>
                                    <td>
                                        <?php $proofUrl = !empty($o['bukti_transfer']) ? findPaymentProofUrl((string) $o['bukti_transfer']) : ''; ?>
                                        <div class="fw-semibold small"><?= e($o['metode_pembayaran'] ?? '-') ?></div>
                                        <span class="badge text-bg-<?= ($o['status_pembayaran'] ?? '') === 'lunas' ? 'success' : (($o['status_pembayaran'] ?? '') === 'gagal' ? 'danger' : 'warning') ?> rounded-pill"><?= e(ucfirst($o['status_pembayaran'] ?? 'pending')) ?></span>
                                        <?php if (!empty($o['bukti_transfer'])): ?>
                                            <div><a href="<?= e($proofUrl) ?>" target="_blank" class="small">Lihat bukti</a></div>
                                        <?php else: ?>
                                            <div class="text-muted small">Belum upload</div>
                                        <?php endif; ?>
                                    </td>
                                    <td><span class="badge text-bg-<?= e($badge) ?> rounded-pill px-3 py-2"><?= e(ucfirst($o['status_order'])) ?></span></td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                                            <a href="index.php?page=update-order&id=<?= e($o['id_order']) ?>" class="btn btn-info btn-sm rounded-3 text-white"><i class="bi bi-pencil-square me-1"></i> Detail / Update</a>
                                            <a href="process/delete_order.php?id=<?= e($o['id_order']) ?>" class="btn btn-danger btn-sm rounded-3" onclick="return confirm('Yakin ingin menghapus order ini? Jika order sudah dibayar/dikirim/selesai, stok akan dikembalikan.');"><i class="bi bi-trash me-1"></i> Hapus</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Belum ada data order.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
