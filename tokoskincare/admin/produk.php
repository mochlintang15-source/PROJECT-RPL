<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../layout/sidebar.php';

$search = trim((string) getParam('search'));
$sql = '
    SELECT products.*, categories.nama_kategori, brands.nama_brand
    FROM products
    LEFT JOIN categories ON categories.id_kategori = products.id_kategori
    LEFT JOIN brands ON brands.id_brand = products.id_brand
';
$params = [];
$types = '';

if ($search !== '') {
    $sql .= ' WHERE products.nama_produk LIKE ? OR products.deskripsi LIKE ? OR categories.nama_kategori LIKE ? OR brands.nama_brand LIKE ?';
    $keyword = '%' . $search . '%';
    $params[] = $keyword;
    $params[] = $keyword;
    $params[] = $keyword;
    $params[] = $keyword;
    $types .= 'ssss';
}
$sql .= ' ORDER BY products.id_product DESC';

$stmt = mysqli_prepare($conn, $sql);
if ($stmt && $params) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
if ($stmt) {
    mysqli_stmt_execute($stmt);
    $data = mysqli_stmt_get_result($stmt);
} else {
    $data = mysqli_query($conn, 'SELECT * FROM products ORDER BY id_product DESC');
}
?>

<div class="admin-layout d-flex" style="min-height: 100vh;">
    <?php renderSidebar('produk'); ?>

    <main class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold mb-0">Manajemen Produk</h3>
                <small class="text-muted">Kelola data produk sesuai tabel products, categories, dan brands</small>
            </div>
            <a href="index.php?page=tambah-produk" class="btn btn-primary rounded-3"><i class="bi bi-plus-circle me-1"></i> Tambah Produk</a>
        </div>

        <form method="GET" class="mb-4">
            <input type="hidden" name="page" value="admin-produk">
            <div class="d-flex gap-2 flex-wrap">
                <input type="text" name="search" value="<?= e($search) ?>" class="form-control rounded-3" placeholder="Cari produk, kategori, brand..." style="max-width: 320px;">
                <button type="submit" class="btn btn-primary rounded-3"><i class="bi bi-search me-1"></i> Cari</button>
                <?php if ($search !== ''): ?>
                    <a href="index.php?page=admin-produk" class="btn btn-secondary rounded-3">Reset</a>
                <?php endif; ?>
            </div>
        </form>

        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="px-4 py-3">Produk</th>
                            <th class="py-3">Kategori</th>
                            <th class="py-3">Brand</th>
                            <th class="py-3">Harga</th>
                            <th class="py-3">Stok</th>
                            <th class="py-3">Deskripsi</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($data && mysqli_num_rows($data) > 0): ?>
                            <?php while ($p = mysqli_fetch_assoc($data)): ?>
                                <?php
                                $image = $p['gambar'] ?? '-';
                                $imagePath = ($image && $image !== '-') ? 'uploads/products/' . $image : '';
                                ?>
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <?php if ($imagePath && file_exists(__DIR__ . '/../' . $imagePath)): ?>
                                                <img src="<?= e($imagePath) ?>" class="product-img" alt="<?= e($p['nama_produk']) ?>">
                                            <?php else: ?>
                                                <div class="product-img d-flex align-items-center justify-content-center text-muted">🧴</div>
                                            <?php endif; ?>
                                            <div class="fw-semibold"><?= e($p['nama_produk']) ?></div>
                                        </div>
                                    </td>
                                    <td><?= e($p['nama_kategori'] ?? '-') ?></td>
                                    <td><?= e($p['nama_brand'] ?? '-') ?></td>
                                    <td><?= rupiah($p['harga']) ?></td>
                                    <td><span class="badge text-bg-light border"><?= e($p['stok']) ?></span></td>
                                    <td class="text-muted" style="max-width: 280px;"><?= e($p['deskripsi'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <a href="index.php?page=edit-produk&id=<?= e($p['id_product']) ?>" class="btn btn-warning btn-sm rounded-3"><i class="bi bi-pencil-fill"></i></a>
                                        <a href="process/delete_produk.php?id=<?= e($p['id_product']) ?>" class="btn btn-danger btn-sm rounded-3" onclick="return confirm('Yakin hapus produk ini?')"><i class="bi bi-trash-fill"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada data produk.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
