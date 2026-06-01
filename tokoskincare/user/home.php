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

<!-- SLIDER -->
<div class="slider-container">
    <div class="slider">
        <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=1600" class="slide active" alt="Beauty skincare collection">
        <img src="https://images.unsplash.com/photo-1515377905703-c4788e51af15?w=1600" class="slide" alt="Makeup product display">
        <img src="https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?w=1600" class="slide" alt="Beauty portrait">
        <img src="https://images.unsplash.com/photo-1496747611176-843222e1e57c?w=1600" class="slide" alt="Beauty lifestyle">
    </div>
</div>

<div class="homepage-wrapper pb-5">
    <section class="beauty-card p-4 p-lg-5 mb-5 text-center" id="about-us">
        <span class="badge rounded-pill text-bg-primary mb-3">ALLIE BEAUTY</span>
        <h1 class="display-5 fw-bold mb-3 store-page-title">Beauty Always, Every Day</h1>
        <p class="lead text-muted mx-auto mb-4" style="max-width: 760px;">
            Belanja skincare dan makeup lebih mudah. Pilih produk dari katalog, masukkan ke keranjang, checkout, lalu upload bukti pembayaran untuk divalidasi admin.
        </p>
        <div class="d-flex justify-content-center gap-2 flex-wrap">
            <a href="index.php?page=katalog" class="btn btn-primary btn-lg rounded-4 px-4">
                <i class="bi bi-bag-heart me-1"></i> Belanja Sekarang
            </a>
            <a href="index.php?page=status-pesanan" class="btn btn-outline-primary btn-lg rounded-4 px-4">
                <i class="bi bi-receipt me-1"></i> Status Pesanan
            </a>
        </div>
    </section>

    <section class="mb-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="beauty-card p-4 h-100 card-hover">
                    <h5 class="fw-bold mb-2">Skincare</h5>
                    <p class="text-muted mb-0">Produk perawatan harian seperti cleanser, serum, moisturizer, dan sunscreen.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="beauty-card p-4 h-100 card-hover">
                    <h5 class="fw-bold mb-2">Makeup</h5>
                    <p class="text-muted mb-0">Pilihan makeup wajah, mata, bibir, sampai aksesori pendukung.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="beauty-card p-4 h-100 card-hover">
                    <h5 class="fw-bold mb-2">Payment Proof</h5>
                    <p class="text-muted mb-0">Upload bukti pembayaran setelah checkout, lalu admin dapat validasi dari dashboard.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-end gap-3 flex-wrap mb-4">
            <div>
                <span class="badge rounded-pill text-bg-light border mb-2">Produk Unggulan</span>
                <h2 class="home-section-title mb-0">Rekomendasi untuk Kamu</h2>
            </div>
            <a href="index.php?page=katalog" class="btn btn-outline-primary rounded-4">Lihat Semua Produk</a>
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
                                    <button type="submit" class="btn btn-primary rounded-4" <?= $stok <= 0 ? 'disabled' : '' ?>>Tambah ke Keranjang</button>
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

    <section class="beauty-card p-4 p-lg-5">
        <div class="row g-4 align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-2">Sistem Toko Sudah Terhubung</h3>
                <p class="text-muted mb-0">User belanja dari katalog, checkout, upload bukti pembayaran, lalu admin melakukan validasi dari menu Bukti Pembayaran.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="index.php?page=admin-dashboard" class="btn btn-dark rounded-4 px-4">Masuk Admin</a>
            </div>
        </div>
    </section>
</div>
