<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../layout/sidebar.php';

$id = (int) getParam('id', 0);
$stmt = mysqli_prepare($conn, '
    SELECT orders.*, users.nama, users.email, users.no_hp
    FROM orders
    LEFT JOIN users ON users.id_user = orders.id_user
    WHERE orders.id_order = ?
    LIMIT 1
');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($result);
$statuses = ['pending', 'dibayar', 'dikirim', 'selesai', 'batal'];

$itemStmt = mysqli_prepare($conn, '
    SELECT
        order_items.*,
        products.nama_produk,
        products.stok,
        (order_items.jumlah * order_items.harga_satuan) AS subtotal
    FROM order_items
    LEFT JOIN products ON products.id_product = order_items.id_product
    WHERE order_items.id_order = ?
');
if ($itemStmt) {
    mysqli_stmt_bind_param($itemStmt, 'i', $id);
    mysqli_stmt_execute($itemStmt);
    $itemsResult = mysqli_stmt_get_result($itemStmt);
} else {
    $itemsResult = false;
}
?>

<div class="admin-layout d-flex" style="min-height: 100vh;">
    <?php renderSidebar('order'); ?>

    <main class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold mb-0">Detail & Update Order</h3>
                <small class="text-muted">Lihat nama barang, jumlah order, lalu ubah status pesanan.</small>
            </div>
            <a href="index.php?page=admin-order" class="btn btn-outline-secondary rounded-3"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
        </div>

        <?php if (!$order): ?>
            <div class="alert alert-danger rounded-4">Order tidak ditemukan.</div>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <div class="mb-3">
                            <div class="text-muted small">ID Order</div>
                            <div class="fw-bold">#<?= e($order['id_order']) ?></div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted small">Nama Pemesan</div>
                            <div class="fw-bold"><?= e($order['nama'] ?? 'User tidak ditemukan') ?></div>
                            <?php if (!empty($order['email']) || !empty($order['no_hp'])): ?>
                                <div class="text-muted small"><?= e($order['email'] ?? '-') ?><?= !empty($order['no_hp']) ? ' | ' . e($order['no_hp']) : '' ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted small">Tanggal Order</div>
                            <div class="fw-bold"><?= !empty($order['tanggal_order']) ? e(date('d M Y H:i', strtotime($order['tanggal_order']))) : '-' ?></div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted small">Total Harga</div>
                            <div class="fw-bold"><?= rupiah($order['total_harga']) ?></div>
                        </div>

                        <form method="POST" action="process/update_order.php">
                            <input type="hidden" name="id_order" value="<?= e($order['id_order']) ?>">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Status Order</label>
                                <select name="status_order" class="form-select rounded-3" required>
                                    <?php foreach ($statuses as $item): ?>
                                        <option value="<?= e($item) ?>" <?= $order['status_order'] === $item ? 'selected' : '' ?>><?= e(ucfirst($item)) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">
                                    Jika status diubah menjadi <b>dibayar</b>, <b>dikirim</b>, atau <b>selesai</b>, stok produk akan dikurangi sesuai jumlah order. Jika order yang sudah laku dibatalkan, stok dikembalikan.
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary rounded-3"><i class="bi bi-check-lg me-1"></i> Update</button>
                                <a href="index.php?page=admin-order" class="btn btn-secondary rounded-3">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="p-3 border-bottom bg-light fw-semibold">Detail Barang yang Diorder</div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="px-3">Nama Barang</th>
                                        <th class="text-center">Jumlah Diorder</th>
                                        <th class="text-center">Stok Saat Ini</th>
                                        <th>Harga Satuan</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($itemsResult && mysqli_num_rows($itemsResult) > 0): ?>
                                        <?php while ($item = mysqli_fetch_assoc($itemsResult)): ?>
                                            <tr>
                                                <td class="px-3 fw-semibold"><?= e($item['nama_produk'] ?? 'Produk tidak ditemukan') ?></td>
                                                <td class="text-center"><?= e($item['jumlah']) ?></td>
                                                <td class="text-center"><?= e($item['stok'] ?? '-') ?></td>
                                                <td><?= rupiah($item['harga_satuan']) ?></td>
                                                <td><?= rupiah($item['subtotal']) ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">Belum ada barang pada order ini.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>
</div>
