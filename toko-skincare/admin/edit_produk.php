<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../layout/sidebar.php';

$id = (int) getParam('id', 0);
$stmt = mysqli_prepare($conn, 'SELECT * FROM products WHERE id_product = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$produk = mysqli_fetch_assoc($result);

$categories = mysqli_query($conn, 'SELECT id_kategori, nama_kategori FROM categories ORDER BY nama_kategori ASC');
$brands = mysqli_query($conn, 'SELECT id_brand, nama_brand FROM brands ORDER BY nama_brand ASC');
?>

<div class="admin-layout d-flex" style="min-height: 100vh;">
    <?php renderSidebar('produk'); ?>

    <main class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold mb-0">Edit Produk</h3>
                <small class="text-muted">Perbarui data produk sesuai tabel products</small>
            </div>
            <a href="index.php?page=admin-produk" class="btn btn-outline-secondary rounded-3"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
        </div>

        <?php if (!$produk): ?>
            <div class="alert alert-danger rounded-4">Produk tidak ditemukan.</div>
        <?php else: ?>
            <div class="card border-0 shadow-sm rounded-4 p-4" style="max-width: 760px;">
                <form method="POST" action="process/update_produk.php" enctype="multipart/form-data">
                    <input type="hidden" name="id_product" value="<?= e($produk['id_product']) ?>">
                    <input type="hidden" name="gambar_lama" value="<?= e($produk['gambar'] ?? '-') ?>">

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control rounded-3" value="<?= e($produk['nama_produk']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select name="id_kategori" class="form-select rounded-3">
                                <option value="">Tanpa kategori</option>
                                <?php if ($categories): ?>
                                    <?php while ($category = mysqli_fetch_assoc($categories)): ?>
                                        <option value="<?= e($category['id_kategori']) ?>" <?= (string) ($produk['id_kategori'] ?? '') === (string) $category['id_kategori'] ? 'selected' : '' ?>><?= e($category['nama_kategori']) ?></option>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Brand</label>
                            <select name="id_brand" class="form-select rounded-3">
                                <option value="">Tanpa brand</option>
                                <?php if ($brands): ?>
                                    <?php while ($brand = mysqli_fetch_assoc($brands)): ?>
                                        <option value="<?= e($brand['id_brand']) ?>" <?= (string) ($produk['id_brand'] ?? '') === (string) $brand['id_brand'] ? 'selected' : '' ?>><?= e($brand['nama_brand']) ?></option>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga</label>
                            <input type="number" name="harga" class="form-control rounded-3" min="0" step="0.01" value="<?= e($produk['harga']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Stok</label>
                            <input type="number" name="stok" class="form-control rounded-3" min="0" value="<?= e($produk['stok']) ?>" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control rounded-3" rows="4" required><?= e($produk['deskripsi'] ?? '') ?></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Gambar Produk</label>
                            <input type="file" name="gambar" class="form-control rounded-3" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                            <small class="text-muted">Kosongkan jika gambar tidak ingin diganti.</small>

                            <?php
                            $image = $produk['gambar'] ?? '-';
                            $imagePath = ($image && $image !== '-') ? 'uploads/products/' . $image : '';
                            ?>
                            <?php if ($imagePath && file_exists(__DIR__ . '/../' . $imagePath)): ?>
                                <div class="mt-3">
                                    <img src="<?= e($imagePath) ?>" class="product-img" alt="Gambar produk">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary rounded-3"><i class="bi bi-save me-1"></i> Update</button>
                        <a href="index.php?page=admin-produk" class="btn btn-secondary rounded-3">Batal</a>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </main>
</div>
