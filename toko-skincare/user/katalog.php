<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/koneksi.php';

$data = mysqli_query($conn, '
    SELECT products.*, categories.nama_kategori, brands.nama_brand
    FROM products
    LEFT JOIN categories ON categories.id_kategori = products.id_kategori
    LEFT JOIN brands ON brands.id_brand = products.id_brand
    ORDER BY products.id_product DESC
');
?>

<nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">✨ All Day Skincare</a>
        <div class="d-flex gap-2">
            <?php if (isLoggedIn() && isAdmin()): ?>
                <a href="index.php?page=admin-dashboard" class="btn btn-primary btn-sm rounded-3">Dashboard</a>
            <?php else: ?>
                <a href="index.php?page=login" class="btn btn-outline-primary btn-sm rounded-3">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="text-center mb-5">
        <span class="badge rounded-pill text-bg-primary mb-2">Katalog Produk</span>
        <h2 class="fw-bold">Skincare & Kosmetik</h2>
        <p class="text-muted mb-0">Lihat daftar produk yang tersedia.</p>
    </div>

    <div class="row g-4">
        <?php if ($data && mysqli_num_rows($data) > 0): ?>
            <?php while ($p = mysqli_fetch_assoc($data)): ?>
                <?php
                $image = $p['gambar'] ?? '-';
                $imagePath = ($image && $image !== '-') ? 'uploads/products/' . $image : '';
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 card-hover overflow-hidden">
                        <?php if ($imagePath && file_exists(__DIR__ . '/../' . $imagePath)): ?>
                            <img src="<?= e($imagePath) ?>" alt="<?= e($p['nama_produk']) ?>" style="height: 220px; object-fit: cover;">
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center bg-light" style="height: 220px; font-size: 4rem;">🧴</div>
                        <?php endif; ?>
                        <div class="card-body p-4">
                            <div class="d-flex gap-2 flex-wrap mb-2">
                                <span class="badge text-bg-light border"><?= e($p['nama_kategori'] ?? 'Tanpa kategori') ?></span>
                                <span class="badge text-bg-light border"><?= e($p['nama_brand'] ?? 'Tanpa brand') ?></span>
                            </div>
                            <h5 class="fw-bold mb-2"><?= e($p['nama_produk']) ?></h5>
                            <p class="text-muted small mb-3"><?= e($p['deskripsi'] ?? 'Produk skincare berkualitas.') ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-primary"><?= rupiah($p['harga']) ?></span>
                                <span class="badge text-bg-light border">Stok: <?= e($p['stok']) ?></span>
                            </div>
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
