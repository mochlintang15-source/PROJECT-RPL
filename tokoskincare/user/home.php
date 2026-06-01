<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../layout/store_navbar.php';

$featured = mysqli_query($conn, '
    SELECT products.*, categories.nama_kategori, brands.nama_brand
    FROM products
    LEFT JOIN categories ON categories.id_kategori = products.id_kategori
    LEFT JOIN brands ON brands.id_brand = products.id_brand
    ORDER BY products.id_product DESC
    LIMIT 4
');

renderStoreNavbar('home');
?>

<style>
    .hero-skin {
        background: linear-gradient(135deg, #fff7fb 0%, #eef2ff 52%, #ffffff 100%);
        border-radius: 2rem;
        overflow: hidden;
    }
    .hero-blob {
        min-height: 330px;
        background:
            radial-gradient(circle at 35% 35%, rgba(236, 72, 153, .23), transparent 32%),
            radial-gradient(circle at 70% 65%, rgba(108, 99, 255, .20), transparent 34%),
            linear-gradient(145deg, #ffffff, #f4f0ff);
    }
    .soft-icon {
        width: 54px;
        height: 54px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 1rem;
        background: #ffffff;
        box-shadow: 0 12px 30px rgba(15, 23, 42, .08);
        font-size: 1.5rem;
    }
    .category-card {
        border: 1px solid #eef2ff;
        background: #fff;
    }
</style>

<div class="container py-5">
    <section class="hero-skin shadow-sm p-4 p-lg-5 mb-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="badge rounded-pill text-bg-primary mb-3">Skincare & Kosmetik</span>
                <h1 class="display-5 fw-bold mb-3">Glow Your Skin Naturally</h1>
                <p class="lead text-muted mb-4">
                    Halaman awal, katalog, keranjang, checkout, upload bukti pembayaran, dan dashboard admin sudah berada dalam satu folder <b>tokoskincare</b>.
                </p>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="index.php?page=katalog" class="btn btn-primary btn-lg rounded-3">
                        <i class="bi bi-bag-heart me-1"></i> Belanja Sekarang
                    </a>
                    <a href="index.php?page=status-pesanan" class="btn btn-outline-primary btn-lg rounded-3">
                        <i class="bi bi-receipt me-1"></i> Status Pesanan
                    </a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="hero-blob rounded-5 d-flex align-items-center justify-content-center p-4">
                    <div class="text-center">
                        <div class="display-1 mb-3">🧴</div>
                        <div class="card border-0 shadow-sm rounded-4 p-3">
                            <h5 class="fw-bold mb-1">Payment Terhubung</h5>
                            <p class="text-muted small mb-0">User upload bukti, admin validasi dari menu Bukti Pembayaran.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-5">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="category-card rounded-4 p-4 h-100 card-hover">
                    <div class="soft-icon mb-3">🧼</div>
                    <h5 class="fw-bold">Skincare</h5>
                    <p class="text-muted mb-0">Produk perawatan harian untuk kulit lebih sehat dan terawat.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="category-card rounded-4 p-4 h-100 card-hover">
                    <div class="soft-icon mb-3">💄</div>
                    <h5 class="fw-bold">Makeup</h5>
                    <p class="text-muted mb-0">Pilihan kosmetik untuk tampilan natural sampai glam.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="category-card rounded-4 p-4 h-100 card-hover">
                    <div class="soft-icon mb-3">☀️</div>
                    <h5 class="fw-bold">Sunscreen</h5>
                    <p class="text-muted mb-0">Perlindungan kulit dari paparan sinar matahari.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-end gap-3 flex-wrap mb-4">
            <div>
                <span class="badge rounded-pill text-bg-light border mb-2">Produk Unggulan</span>
                <h2 class="fw-bold mb-0">Rekomendasi untuk Kamu</h2>
            </div>
            <a href="index.php?page=katalog" class="btn btn-outline-primary rounded-3">Lihat Semua Produk</a>
        </div>

        <div class="row g-4">
            <?php if ($featured && mysqli_num_rows($featured) > 0): ?>
                <?php while ($p = mysqli_fetch_assoc($featured)): ?>
                    <?php
                    $image = $p['gambar'] ?? '-';
                    $imagePath = ($image && $image !== '-') ? 'uploads/products/' . $image : '';
                    $stok = (int) ($p['stok'] ?? 0);
                    ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="card border-0 shadow-sm rounded-4 h-100 card-hover overflow-hidden">
                            <?php if ($imagePath && file_exists(__DIR__ . '/../' . $imagePath)): ?>
                                <img src="<?= e($imagePath) ?>" alt="<?= e($p['nama_produk']) ?>" style="height: 180px; object-fit: cover;">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center bg-light" style="height: 180px; font-size: 3.5rem;">🧴</div>
                            <?php endif; ?>
                            <div class="card-body p-3 d-flex flex-column">
                                <span class="badge text-bg-light border align-self-start mb-2"><?= e($p['nama_kategori'] ?? 'Produk') ?></span>
                                <h6 class="fw-bold mb-2"><?= e($p['nama_produk']) ?></h6>
                                <p class="text-muted small mb-3 flex-grow-1"><?= e($p['deskripsi'] ?? 'Produk skincare berkualitas.') ?></p>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fw-bold text-primary"><?= rupiah($p['harga']) ?></span>
                                    <span class="small text-muted">Stok <?= e($stok) ?></span>
                                </div>
                                <form method="POST" action="process/add_to_cart.php" class="d-grid">
                                    <input type="hidden" name="id_product" value="<?= e($p['id_product']) ?>">
                                    <input type="hidden" name="qty" value="1">
                                    <button type="submit" class="btn btn-primary rounded-3" <?= $stok <= 0 ? 'disabled' : '' ?>>Tambah ke Keranjang</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info rounded-4">Belum ada produk. Tambahkan produk melalui dashboard admin.</div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="rounded-4 bg-white shadow-sm p-4 p-lg-5">
        <div class="row g-4 align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-2">Alur Sistem Sudah Disatukan</h3>
                <p class="text-muted mb-0">User belanja dari katalog, checkout, upload bukti pembayaran, lalu admin melakukan validasi dari dashboard yang sama.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="index.php?page=admin-dashboard" class="btn btn-dark rounded-3">Masuk Admin</a>
            </div>
        </div>
    </section>
</div>
