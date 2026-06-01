<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../layout/store_navbar.php';

$search = trim((string) getParam('q'));
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
    $params = [$keyword, $keyword, $keyword, $keyword];
    $types = 'ssss';
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

renderStoreNavbar('katalog');
?>

<div class="container py-5">
    <div class="text-center mb-4">
        <span class="badge rounded-pill text-bg-primary mb-2">Katalog Produk</span>
        <h2 class="fw-bold">Skincare & Kosmetik</h2>
        <p class="text-muted mb-0">Pilih produk, masukkan ke keranjang, lalu checkout.</p>
    </div>

    <form method="GET" class="row justify-content-center mb-5">
        <input type="hidden" name="page" value="katalog">
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" name="q" value="<?= e($search) ?>" class="form-control rounded-start-3" placeholder="Cari produk, brand, atau kategori...">
                <button class="btn btn-primary rounded-end-3" type="submit"><i class="bi bi-search me-1"></i> Cari</button>
            </div>
        </div>
    </form>

    <div class="row g-4">
        <?php if ($data && mysqli_num_rows($data) > 0): ?>
            <?php while ($p = mysqli_fetch_assoc($data)): ?>
                <?php
                $image = $p['gambar'] ?? '-';
                $imagePath = ($image && $image !== '-') ? 'uploads/products/' . $image : '';
                $stok = (int) ($p['stok'] ?? 0);
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 card-hover overflow-hidden">
                        <?php if ($imagePath && file_exists(__DIR__ . '/../' . $imagePath)): ?>
                            <img src="<?= e($imagePath) ?>" alt="<?= e($p['nama_produk']) ?>" style="height: 220px; object-fit: cover;">
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center bg-light" style="height: 220px; font-size: 4rem;">🧴</div>
                        <?php endif; ?>
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex gap-2 flex-wrap mb-2">
                                <span class="badge text-bg-light border"><?= e($p['nama_kategori'] ?? 'Tanpa kategori') ?></span>
                                <span class="badge text-bg-light border"><?= e($p['nama_brand'] ?? 'Tanpa brand') ?></span>
                            </div>
                            <h5 class="fw-bold mb-2"><?= e($p['nama_produk']) ?></h5>
                            <p class="text-muted small mb-3 flex-grow-1"><?= e($p['deskripsi'] ?? 'Produk skincare berkualitas.') ?></p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-bold text-primary"><?= rupiah($p['harga']) ?></span>
                                <span class="badge <?= $stok > 0 ? 'text-bg-light border' : 'text-bg-danger' ?>">Stok: <?= e($stok) ?></span>
                            </div>
                            <form method="POST" action="process/add_to_cart.php" class="d-flex gap-2">
                                <input type="hidden" name="id_product" value="<?= e($p['id_product']) ?>">
                                <input type="number" name="qty" min="1" max="<?= e(max($stok, 1)) ?>" value="1" class="form-control rounded-3" style="max-width:90px;" <?= $stok <= 0 ? 'disabled' : '' ?>>
                                <button type="submit" class="btn btn-primary rounded-3 flex-grow-1" <?= $stok <= 0 ? 'disabled' : '' ?>>
                                    <i class="bi bi-cart-plus me-1"></i> Tambah
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center rounded-4">Belum ada produk yang tersedia.</div>
            </div>
        <?php endif; ?>
    </div>
</div>
